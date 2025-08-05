<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Block\AnonIpBlockTarget;
use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockPermissionCheckerFactory;
use MediaWiki\Block\BlockTarget;
use MediaWiki\Block\BlockTargetFactory;
use MediaWiki\Block\BlockTargetWithIp;
use MediaWiki\Block\BlockTargetWithUserPage;
use MediaWiki\Block\BlockUserFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\MultiblocksException;
use MediaWiki\Block\RangeBlockTarget;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\UserBlockTarget;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Watchlist\WatchlistManager;
use OOUI\FieldLayout;
use OOUI\HtmlSnippet;
use OOUI\LabelWidget;
use OOUI\Widget;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Message\MessageSpecifier;

/**
 * Allow users with 'block' user right to block IPs and user accounts from
 * editing pages and other actions.
 *
 * @ingroup SpecialPage
 */
class SpecialBlock extends FormSpecialPage {

	private BlockTargetFactory $blockTargetFactory;
	private BlockPermissionCheckerFactory $blockPermissionCheckerFactory;
	private BlockUserFactory $blockUserFactory;
	private DatabaseBlockStore $blockStore;
	private UserNameUtils $userNameUtils;
	private UserNamePrefixSearch $userNamePrefixSearch;
	private BlockActionInfo $blockActionInfo;
	private TitleFormatter $titleFormatter;
	private NamespaceInfo $namespaceInfo;
	private UserOptionsLookup $userOptionsLookup;
	private WatchlistManager $watchlistManager;

	/** @var BlockTarget|null User to be blocked, as passed either by parameter
	 * (url?wpTarget=Foo) or as subpage (Special:Block/Foo)
	 */
	protected $target;

	/** @var BlockTarget|null The previous block target */
	protected $previousTarget;

	/** @var bool Whether the previous submission of the form asked for HideUser */
	protected $requestedHideUser;

	/** @var bool */
	protected $alreadyBlocked;

	/**
	 * @var MessageSpecifier[]
	 */
	protected $preErrors = [];

	/**
	 * @var array <mixed,mixed> An associative array used to pass vars to Codex form
	 */
	protected array $codexFormData = [];

	public function __construct(
		BlockTargetFactory $blockTargetFactory,
		BlockPermissionCheckerFactory $blockPermissionCheckerFactory,
		BlockUserFactory $blockUserFactory,
		DatabaseBlockStore $blockStore,
		UserNameUtils $userNameUtils,
		UserNamePrefixSearch $userNamePrefixSearch,
		BlockActionInfo $blockActionInfo,
		TitleFormatter $titleFormatter,
		NamespaceInfo $namespaceInfo,
		UserOptionsLookup $userOptionsLookup,
		WatchlistManager $watchlistManager
	) {
		parent::__construct( 'Block', 'block' );

		$this->blockTargetFactory = $blockTargetFactory;
		$this->blockPermissionCheckerFactory = $blockPermissionCheckerFactory;
		$this->blockUserFactory = $blockUserFactory;
		$this->blockStore = $blockStore;
		$this->userNameUtils = $userNameUtils;
		$this->userNamePrefixSearch = $userNamePrefixSearch;
		$this->blockActionInfo = $blockActionInfo;
		$this->titleFormatter = $titleFormatter;
		$this->namespaceInfo = $namespaceInfo;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->watchlistManager = $watchlistManager;
	}

	public function getDescription(): Message {
		return $this->msg( $this->getConfig()->get( MainConfigNames::EnableMultiBlocks )
			? 'block-manage-blocks' : 'block' );
	}

	/**
	 * @inheritDoc
	 */
	public function execute( $par ) {
		parent::execute( $par );

		if ( $this->getConfig()->get( MainConfigNames::UseCodexSpecialBlock )
			|| $this->getRequest()->getBool( 'usecodex' )
		) {
			// Ensure wgUseCodexSpecialBlock is set when ?usecodex=1 is used.
			$this->codexFormData[ 'wgUseCodexSpecialBlock' ] = true;
			$this->codexFormData[ 'blockEnableMultiblocks' ] =
				$this->getConfig()->get( MainConfigNames::EnableMultiBlocks ) ||
				$this->getRequest()->getBool( 'multiblocks' );
			$this->codexFormData[ 'blockTargetUser' ] =
				$this->target ? $this->target->toString() : null;
			$this->codexFormData[ 'blockId' ] =
				$this->target ? $this->getRequest()->getInt( 'id' ) : null;
			$authority = $this->getAuthority();
			$this->codexFormData[ 'blockShowSuppressLog' ] = $authority->isAllowed( 'suppressionlog' );
			$this->codexFormData[ 'blockCanDeleteLogEntry' ] = $authority->isAllowed( 'deletelogentry' );
			$this->codexFormData[ 'blockCanEditInterface' ] = $authority->isAllowed( 'editinterface' );
			$this->codexFormData[ 'blockCIDRLimit' ] = $this->getConfig()->get( MainConfigNames::BlockCIDRLimit );
			$this->getOutput()->addJsConfigVars( $this->codexFormData );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function doesWrites() {
		return true;
	}

	/**
	 * Check that the user can unblock themselves if they are trying to do so
	 *
	 * @param User $user
	 * @throws ErrorPageError
	 */
	protected function checkExecutePermissions( User $user ) {
		parent::checkExecutePermissions( $user );
		if ( $this->target ) {
			// T17810: blocked admins should have limited access here
			$status = $this->blockPermissionCheckerFactory
				->newChecker( $user )
				->checkBlockPermissions( $this->target );
			if ( $status !== true ) {
				throw new ErrorPageError( 'badaccess', $status );
			}
		}
	}

	/**
	 * We allow certain special cases where user is blocked
	 *
	 * @return bool
	 */
	public function requiresUnblock() {
		return false;
	}

	/**
	 * Handle some magic here
	 *
	 * @param string $par
	 */
	protected function setParameter( $par ) {
		// Extract variables from the request.  Try not to get into a situation where we
		// need to extract *every* variable from the form just for processing here, but
		// there are legitimate uses for some variables
		$request = $this->getRequest();
		$this->target = $this->getTargetInternal( $par, $request );
		if ( $this->target instanceof BlockTargetWithUserPage ) {
			// Set the 'relevant user' in the skin, so it displays links like Contributions,
			// User logs, UserRights, etc.
			$this->getSkin()->setRelevantUser( $this->target->getUserIdentity() );
		}

		$this->previousTarget = $this->blockTargetFactory
			->newFromString( $request->getVal( 'wpPreviousTarget' ) );
		$this->requestedHideUser = $request->getBool( 'wpHideUser' );

		if ( $this->getConfig()->get( MainConfigNames::UseCodexSpecialBlock ) || $request->getBool( 'usecodex' ) ) {
			// Parse wpExpiry param
			$givenExpiry = $request->getVal( 'wpExpiry', '' );
			if ( wfIsInfinity( $givenExpiry ) ) {
				$this->codexFormData[ 'blockExpiryPreset' ] = 'infinite';
			} else {
				$expiry = date_parse( $givenExpiry );
				$this->codexFormData[ 'blockExpiryPreset' ] = isset( $expiry[ 'relative' ] ) ?
					// Relative expiry (e.g. '1 week')
					$givenExpiry :
					// Absolute expiry, formatted for <input type="datetime-local">
					$this->formatExpiryForHtml( $request->getVal( 'wpExpiry', '' ) );
			}

			$this->codexFormData[ 'blockTypePreset' ] =
				$request->getRawVal( 'wpEditingRestriction' ) === 'partial' ?
				'partial' :
				'sitewide';

			$reasonPreset = $request->getVal( 'wpReason' );
			$reasonOtherPreset = $request->getVal( 'wpReason-other' );
			if ( $reasonPreset && $reasonOtherPreset ) {
				$this->codexFormData[ 'blockReasonPreset' ] = $reasonPreset .
					$this->msg( 'colon-separator' )->text() . $reasonOtherPreset;
			} else {
				$this->codexFormData[ 'blockReasonPreset' ] =
					$reasonPreset ?: $reasonOtherPreset ?: '';
			}

			$this->codexFormData[ 'blockRemovalReasonPreset' ] = $request->getVal( 'wpRemovalReason' );
			$blockAdditionalDetailsPreset = $blockDetailsPreset = [];

			// Default is to always block account creation.
			if ( $request->getBool( 'wpCreateAccount', true ) ) {
				$blockDetailsPreset[] = 'wpCreateAccount';
			}

			if ( $request->getBool( 'wpDisableEmail' ) ) {
				$blockDetailsPreset[] = 'wpDisableEmail';
			}

			if ( $request->getBool( 'wpDisableUTEdit' ) ) {
				$blockDetailsPreset[] = 'wpDisableUTEdit';
			}

			if ( $request->getRawVal( 'wpAutoBlock' ) !== '0' ) {
				$blockAdditionalDetailsPreset[] = 'wpAutoBlock';
			}

			if ( $request->getBool( 'wpWatch' ) ) {
				$blockAdditionalDetailsPreset[] = 'wpWatch';
			}

			if ( $request->getBool( 'wpHideUser' ) ) {
				$blockAdditionalDetailsPreset[] = 'wpHideUser';
			}

			if ( $request->getBool( 'wpHardBlock' ) ) {
				$blockAdditionalDetailsPreset[] = 'wpHardBlock';
			}

			$this->codexFormData[ 'blockDetailsPreset' ] = $blockDetailsPreset;
			$this->codexFormData[ 'blockAdditionalDetailsPreset' ] = $blockAdditionalDetailsPreset;
			$this->codexFormData[ 'blockPageRestrictions' ] = $request->getVal( 'wpPageRestrictions' );
			$this->codexFormData[ 'blockNamespaceRestrictions' ] = $request->getVal( 'wpNamespaceRestrictions' );
		}
	}

	/**
	 * Customizes the HTMLForm a bit
	 */
	protected function alterForm( HTMLForm $form ) {
		$form->setHeaderHtml( '' );
		$form->setSubmitDestructive();
		$form->setId( 'mw-block-form' );

		$msg = $this->alreadyBlocked ? 'ipb-change-block' : 'ipbsubmit';
		$form->setSubmitTextMsg( $msg );

		$useCodex = $this->getConfig()->get( MainConfigNames::UseCodexSpecialBlock )
			|| $this->getRequest()->getBool( 'usecodex' );

		$this->addHelpLink( $useCodex ? 'Help:Manage blocks' : 'Help:Blocking users' );

		// Don't need to do anything if the form has been posted, or if there were no pre-errors.
		if ( $this->getRequest()->wasPosted() || !$this->preErrors ) {
			return;
		}

		if ( $useCodex ) {
			$this->codexFormData[ 'blockPreErrors' ] = array_map( function ( $errMsg ) {
				return $this->msg( $errMsg )->parse();
			}, $this->preErrors );

			// Mimic Codex error messages later generated by SpecialBlock.vue
			$form->addHeaderHtml(
				Html::rawElement(
					'div',
					[ 'class' => 'mw-block-messages' ],
					array_reduce( $this->preErrors, function ( $carry, $errMsg ) {
						return $carry . Html::errorBox(
								$this->msg( $errMsg )->parse(),
								'',
								'cdx-message--inline'
							);
					}, '' )
				)
			);
		} else {
			// Mimic error messages normally generated by the form
			$form->addHeaderHtml( (string)new FieldLayout(
				new Widget( [] ),
				[
					'align' => 'top',
					'errors' => array_map( function ( $errMsg ) {
						return new HtmlSnippet( $this->msg( $errMsg )->parse() );
					}, $this->preErrors ),
				]
			) );
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function getDisplayFormat() {
		return $this->getConfig()->get( MainConfigNames::UseCodexSpecialBlock ) ||
			$this->getRequest()->getBool( 'usecodex' ) ? 'codex' : 'ooui';
	}

	/**
	 * Get the HTMLForm descriptor array for the block form
	 * @return array
	 */
	protected function getFormFields() {
		$conf = $this->getConfig();
		$blockAllowsUTEdit = $conf->get( MainConfigNames::BlockAllowsUTEdit );
		$useCodex = $conf->get( MainConfigNames::UseCodexSpecialBlock ) || $this->getRequest()->getBool( 'usecodex' );

		if ( !$useCodex ) {
			$this->getOutput()->enableOOUI();
		}

		$user = $this->getUser();

		$suggestedDurations = $this->getLanguage()->getBlockDurations();

		$a = [];

		$a['Target'] = [
			'type' => 'user',
			'ipallowed' => true,
			'iprange' => true,
			'id' => 'mw-bi-target',
			'size' => '45',
			'autofocus' => true,
			'required' => true,
			'placeholder' => $this->msg( 'block-target-placeholder' )->text(),
			'validation-callback' => function ( $value, $alldata, $form ) {
				$status = $this->blockTargetFactory->newFromString( $value )->validateForCreation();
				if ( !$status->isOK() ) {
					$errors = $status->getMessages();
					return $form->msg( $errors[0] );
				}
				return true;
			},
			'section' => 'target',
		];

		$editingRestrictionOptions = $useCodex ?
			// If we're using Codex, use the option-descriptions feature, which is only supported by Codex
			[
				'options-messages' => [
					'ipb-sitewide' => 'sitewide',
					'ipb-partial' => 'partial'
				],
				'option-descriptions-messages' => [
					'sitewide' => 'ipb-sitewide-help',
					'partial' => 'ipb-partial-help'
				],
				'option-descriptions-messages-parse' => true,
			] :
			// Otherwise, if we're using OOUI, add the options' descriptions as part of their labels
			[
				'options' => [
					$this->msg( 'ipb-sitewide' )->escaped() .
						new LabelWidget( [
							'classes' => [ 'oo-ui-inline-help' ],
							'label' => new HtmlSnippet( $this->msg( 'ipb-sitewide-help' )->parse() ),
						] ) => 'sitewide',
					$this->msg( 'ipb-partial' )->escaped() .
						new LabelWidget( [
							'classes' => [ 'oo-ui-inline-help' ],
							'label' => new HtmlSnippet( $this->msg( 'ipb-partial-help' )->parse() ),
						] ) => 'partial',
				]
			];

		$a['EditingRestriction'] = [
			'type' => 'radio',
			'cssclass' => 'mw-block-editing-restriction',
			'default' => 'sitewide',
			'section' => 'actions',
		] + $editingRestrictionOptions;

		$a['PageRestrictions'] = [
			'type' => 'titlesmultiselect',
			'label' => $this->msg( 'ipb-pages-label' )->text(),
			'exists' => true,
			'max' => 10,
			'cssclass' => 'mw-htmlform-checkradio-indent mw-block-partial-restriction',
			'default' => '',
			'showMissing' => false,
			'excludeDynamicNamespaces' => true,
			'input' => [
				'autocomplete' => false
			],
			'section' => 'actions',
		];

		$a['NamespaceRestrictions'] = [
			'type' => 'namespacesmultiselect',
			'label' => $this->msg( 'ipb-namespaces-label' )->text(),
			'exists' => true,
			'cssclass' => 'mw-htmlform-checkradio-indent mw-block-partial-restriction',
			'default' => '',
			'input' => [
				'autocomplete' => false
			],
			'section' => 'actions',
		];

		if ( $conf->get( MainConfigNames::EnablePartialActionBlocks ) ) {
			$blockActions = $this->blockActionInfo->getAllBlockActions();
			$optionMessages = array_combine(
				array_map( static function ( $action ) {
					return "ipb-action-$action";
				}, array_keys( $blockActions ) ),
				$blockActions
			);

			$this->codexFormData[ 'partialBlockActionOptions'] = $optionMessages;

			$a['ActionRestrictions'] = [
				'type' => 'multiselect',
				'cssclass' => 'mw-htmlform-checkradio-indent mw-block-partial-restriction mw-block-action-restriction',
				'options-messages' => $optionMessages,
				'section' => 'actions',
			];
		}

		$a['CreateAccount'] = [
			'type' => 'check',
			'cssclass' => 'mw-block-restriction',
			'label-message' => 'ipbcreateaccount',
			'default' => true,
			'section' => 'details',
		];

		if ( $this->blockPermissionCheckerFactory
			->newChecker( $user )
			->checkEmailPermissions()
		) {
			$a['DisableEmail'] = [
				'type' => 'check',
				'cssclass' => 'mw-block-restriction',
				'label-message' => 'ipbemailban',
				'section' => 'details',
			];

			$this->codexFormData[ 'blockDisableEmailVisible'] = true;
		}

		if ( $blockAllowsUTEdit ) {
			$a['DisableUTEdit'] = [
				'type' => 'check',
				'cssclass' => 'mw-block-restriction',
				'label-message' => 'ipb-disableusertalk',
				'default' => false,
				'section' => 'details',
			];

			$this->codexFormData[ 'blockDisableUTEditVisible'] = true;
		}

		$defaultExpiry = $this->msg( 'ipb-default-expiry' )->inContentLanguage();
		if ( $this->target instanceof BlockTargetWithIp ) {
			$defaultExpiryIP = $this->msg( 'ipb-default-expiry-ip' )->inContentLanguage();
			if ( !$defaultExpiryIP->isDisabled() ) {
				$defaultExpiry = $defaultExpiryIP;
			}
		} elseif (
			$this->target instanceof UserBlockTarget &&
			$this->userNameUtils->isTemp( $this->target->getUserIdentity()->getName() )
		) {
			$defaultExpiryTemporaryAccount = $this->msg( 'ipb-default-expiry-temporary-account' )
				->inContentLanguage();
			if ( !$defaultExpiryTemporaryAccount->isDisabled() ) {
				$defaultExpiry = $defaultExpiryTemporaryAccount;
			}
		}

		$a['Expiry'] = [
			'type' => 'expiry',
			'required' => true,
			'options' => $suggestedDurations,
			'default' => $defaultExpiry->text(),
			'section' => 'expiry',
		];
		$this->codexFormData[ 'blockExpiryOptions' ] = $suggestedDurations;
		$this->codexFormData[ 'blockExpiryDefault' ] = $defaultExpiry->text();

		$a['Reason'] = [
			'type' => 'selectandother',
			// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
			// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
			// Unicode codepoints.
			'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
			'maxlength-unit' => 'codepoints',
			'options-message' => 'ipbreason-dropdown',
			'section' => 'reason',
			'help-message' => 'block-reason-help',
		];

		if ( $useCodex ) {
			$blockReasonOptions = Html::listDropdownOptionsCodex(
				Html::listDropdownOptions( $this->msg( 'ipbreason-dropdown' )->inContentLanguage()->plain(),
					[ 'other' => $this->msg( 'htmlform-selectorother-other' )->text() ]
			) );
			$this->codexFormData[ 'blockReasonOptions' ] = $blockReasonOptions;
			$this->codexFormData[ 'blockReasonMaxLength' ] = CommentStore::COMMENT_CHARACTER_LIMIT;
		}

		$a['AutoBlock'] = [
			'type' => 'check',
			'label-message' => [
				'ipbenableautoblock',
				Message::durationParam( $conf->get( MainConfigNames::AutoblockExpiry ) )
			],
			'default' => true,
			'section' => 'options',
		];
		$this->codexFormData['blockAutoblockExpiry'] = $this->getLanguage()
			->formatDuration( $conf->get( MainConfigNames::AutoblockExpiry ) );

		// Allow some users to hide name from block log, blocklist and listusers
		if ( $this->getAuthority()->isAllowed( 'hideuser' ) ) {
			$a['HideUser'] = [
				'type' => 'check',
				'label-message' => 'ipbhidename',
				'cssclass' => 'mw-block-hideuser',
				'section' => 'options',
			];

			$this->codexFormData['blockHideUser'] = true;
		}

		// Watchlist their user page? (Only if user is logged in)
		if ( $user->isRegistered() ) {
			$a['Watch'] = [
				'type' => 'check',
				'label-message' => 'ipbwatchuser',
				'section' => 'options',
			];
		}

		$a['HardBlock'] = [
			'type' => 'check',
			'label-message' => 'ipb-hardblock',
			'default' => false,
			'section' => 'options',
		];

		// This is basically a copy of the Target field, but the user can't change it, so we
		// can see if the warnings we maybe showed to the user before still apply
		$a['PreviousTarget'] = [
			'type' => 'hidden',
			'default' => false,
		];

		// We'll turn this into a checkbox if we need to
		$a['Confirm'] = [
			'type' => 'hidden',
			'default' => '',
			'label-message' => 'ipb-confirm',
			'cssclass' => 'mw-block-confirm',
		];

		$this->validateTarget();

		// (T382496) Only load the modified defaults from a previous
		// block if multiblocks are not enabled
		if ( !$this->getConfig()->get( MainConfigNames::EnableMultiBlocks )
			|| $this->getRequest()->getBool( 'multiblocks' )
		) {
			$this->maybeAlterFormDefaults( $a );
		}

		// Allow extensions to add more fields
		$this->getHookRunner()->onSpecialBlockModifyFormFields( $this, $a );

		if ( $useCodex ) {
			$default = (string)$this->target;
			$a['Target']['default'] = $default;
			$a['Target']['disabled'] = true;
			// Remove all fields except Target for Codex. (T377529)
			// This is a temporary measure until Codex PHP is available.
			$a = array_intersect_key( $a, [ 'Target' => true ] );
		}

		return $a;
	}

	/**
	 * Validate the target, setting preErrors if necessary.
	 *
	 * @param WebRequest|null $request For testing purposes.
	 */
	private function validateTarget( ?WebRequest $request = null ): void {
		$request ??= $this->getRequest();
		if ( !$this->target ) {
			if ( $request->getVal( 'id' ) ) {
				$this->preErrors[] = $this->msg( 'block-invalid-id' );
			}
			return;
		}

		$status = $this->target->validateForCreation();
		$this->codexFormData[ 'blockTargetExists' ] = true;

		if ( !$status->isOK() ) {
			$errors = $status->getMessages( 'error' );
			$this->preErrors = array_merge( $this->preErrors, $errors );

			// Remove top-level errors that are later handled per-field in Codex.
			if ( $this->getConfig()->get( MainConfigNames::UseCodexSpecialBlock ) || $request->getBool( 'usecodex' ) ) {
				$this->preErrors = array_filter( $this->preErrors, function ( $error ) {
					if ( $error->getKey() === 'nosuchusershort' || $error->getKey() === 'ip_range_toolarge' ) {
						// Avoids us having to re-query the API to validate the user.
						$this->codexFormData[ 'blockTargetExists' ] = false;
						return false;
					}
					return true;
				} );
			}
		}
	}

	/**
	 * If the user has already been blocked with similar settings, load that block
	 * and change the defaults for the form fields to match the existing settings.
	 * @param array &$fields HTMLForm descriptor array
	 */
	protected function maybeAlterFormDefaults( &$fields ) {
		// This will be overwritten by request data
		$fields['Target']['default'] = (string)$this->target;

		// This won't be
		$fields['PreviousTarget']['default'] = (string)$this->target;

		$block = $this->blockStore->newFromTarget(
			$this->target, null, false, DatabaseBlockStore::AUTO_NONE );

		// Populate fields if there is a block that is not an autoblock; if it is a range
		// block, only populate the fields if the range is the same as $this->target
		if ( $block instanceof DatabaseBlock
			&& ( !( $this->target instanceof RangeBlockTarget )
				|| $block->isBlocking( $this->target ) )
		) {
			$fields['HardBlock']['default'] = $block->isHardblock();
			$fields['CreateAccount']['default'] = $block->isCreateAccountBlocked();
			$fields['AutoBlock']['default'] = $block->isAutoblocking();

			if ( isset( $fields['DisableEmail'] ) ) {
				$fields['DisableEmail']['default'] = $block->isEmailBlocked();
			}

			if ( isset( $fields['HideUser'] ) ) {
				$fields['HideUser']['default'] = $block->getHideName();
			}

			if ( isset( $fields['DisableUTEdit'] ) ) {
				$fields['DisableUTEdit']['default'] = !$block->isUsertalkEditAllowed();
			}

			// If the username was hidden (bl_deleted == 1), don't show the reason
			// unless this user also has rights to hideuser: T37839
			if ( !$block->getHideName() || $this->getAuthority()->isAllowed( 'hideuser' ) ) {
				$fields['Reason']['default'] = $block->getReasonComment()->text;
			} else {
				$fields['Reason']['default'] = '';
			}

			if ( $this->getRequest()->wasPosted() ) {
				// Ok, so we got a POST submission asking us to reblock a user.  So show the
				// confirm checkbox; the user will only see it if they haven't previously
				$fields['Confirm']['type'] = 'check';
			} else {
				// We got a target, but it wasn't a POST request, so the user must have gone
				// to a link like [[Special:Block/User]].  We don't need to show the checkbox
				// as long as they go ahead and block *that* user
				$fields['Confirm']['default'] = 1;
			}

			if ( $block->getExpiry() == 'infinity' ) {
				$fields['Expiry']['default'] = $this->codexFormData[ 'blockExpiryDefault' ] = 'infinite';
			} else {
				$fields['Expiry']['default'] = wfTimestamp( TS_RFC2822, $block->getExpiry() );

				// Don't overwrite if expiry was specified in the URL
				if ( !isset( $this->codexFormData[ 'blockExpiryPreset' ] ) ) {
					$this->codexFormData[ 'blockExpiryPreset' ] = $this->formatExpiryForHtml( $block->getExpiry() );
				}
			}

			if ( !$block->isSitewide() ) {
				$fields['EditingRestriction']['default'] =
					$this->codexFormData[ 'blockTypePreset' ] = 'partial';

				$pageRestrictions = [];
				$namespaceRestrictions = [];
				foreach ( $block->getRestrictions() as $restriction ) {
					if ( $restriction instanceof PageRestriction && $restriction->getTitle() ) {
						$pageRestrictions[] = $restriction->getTitle()->getPrefixedText();
					} elseif ( $restriction instanceof NamespaceRestriction &&
						$this->namespaceInfo->exists( $restriction->getValue() )
					) {
						$namespaceRestrictions[] = $restriction->getValue();
					}
				}

				// Sort the restrictions so they are in alphabetical order.
				sort( $pageRestrictions );
				$fields['PageRestrictions']['default'] =
					$this->codexFormData[ 'blockPageRestrictions' ] = implode( "\n", $pageRestrictions );
				sort( $namespaceRestrictions );
				$fields['NamespaceRestrictions']['default'] =
					$this->codexFormData[ 'blockNamespaceRestrictions' ] = implode( "\n", $namespaceRestrictions );

				if ( $this->getConfig()->get( MainConfigNames::EnablePartialActionBlocks ) ) {
					$actionRestrictions = [];
					foreach ( $block->getRestrictions() as $restriction ) {
						if ( $restriction instanceof ActionRestriction ) {
							$actionRestrictions[] = $restriction->getValue();
						}
					}
					$fields['ActionRestrictions']['default'] = $actionRestrictions;
				}
			}

			$this->alreadyBlocked = true;
			$this->codexFormData[ 'blockAlreadyBlocked' ] = $this->alreadyBlocked;
			$this->preErrors[] = $this->msg(
				'ipb-needreblock',
				'<bdi>' . wfEscapeWikiText( $block->getTargetName() ) . '</bdi>'
			);
		}

		if ( $this->alreadyBlocked || $this->getRequest()->wasPosted()
			|| $this->getRequest()->getCheck( 'wpCreateAccount' )
		) {
			$this->getOutput()->addJsConfigVars( 'wgCreateAccountDirty', true );
		}

		// We always need confirmation to do HideUser
		if ( $this->requestedHideUser && $this->getAuthority()->isAllowed( 'hideuser' ) ) {
			$fields['Confirm']['type'] = 'check';
			unset( $fields['Confirm']['default'] );
			$this->preErrors[] = $this->msg( 'ipb-confirmhideuser', 'ipb-confirmaction' );
		}

		// Or if the user is trying to block themselves
		if ( (string)$this->target === $this->getUser()->getName() ) {
			$fields['Confirm']['type'] = 'check';
			unset( $fields['Confirm']['default'] );
			$this->preErrors[] = $this->msg( 'ipb-blockingself', 'ipb-confirmaction' );
		}
	}

	/**
	 * Format a date string for use by <input type="datetime-local">
	 *
	 * @param string $expiry
	 * @return string Formatted as YYYY-MM-DDTHH:mm
	 */
	private function formatExpiryForHtml( string $expiry ): string {
		if ( preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/', $expiry ) === 1 ) {
			// YYYY-MM-DDTHH:mm which is accepted by <input type="datetime-local">, but not by MediaWiki.
			return substr( $expiry, 0, 16 );
		} elseif ( $expiry === '' ) {
			// No expiry specified
			return '';
		}
		return substr( wfTimestamp( TS_ISO_8601, $expiry ), 0, 16 );
	}

	/**
	 * Add header elements like block log entries, etc.
	 * @return string
	 */
	protected function preHtml() {
		$this->getOutput()->addModuleStyles( [ 'mediawiki.special' ] );
		if ( $this->getConfig()->get( MainConfigNames::UseCodexSpecialBlock )
			|| $this->getRequest()->getBool( 'usecodex' )
		) {
			$this->getOutput()->addModules( [ 'mediawiki.special.block.codex' ] );
			$this->getOutput()->addElement( 'noscript', [],
				$this->msg( 'block-javascript-required' )->text()
			);
		} else {
			$this->getOutput()->addModules( [ 'mediawiki.special.block' ] );
			$this->getOutput()->addBodyClasses( 'mw-special-Block--legacy' );
		}

		$blockCIDRLimit = $this->getConfig()->get( MainConfigNames::BlockCIDRLimit );
		$text = $this->msg( 'blockiptext', $blockCIDRLimit['IPv4'], $blockCIDRLimit['IPv6'] )->parse();

		$otherBlockMessages = [];
		if ( $this->target !== null ) {
			// Get other blocks, i.e. from GlobalBlocking or TorBlock extension
			$this->getHookRunner()->onOtherBlockLogLink(
				$otherBlockMessages, $this->target->toString() );

			if ( count( $otherBlockMessages ) ) {
				$s = Html::rawElement(
					'h2',
					[],
					$this->msg( 'ipb-otherblocks-header', count( $otherBlockMessages ) )->parse()
				) . "\n";

				$list = '';

				foreach ( $otherBlockMessages as $link ) {
					$list .= Html::rawElement( 'li', [], $link ) . "\n";
				}

				$s .= Html::rawElement(
					'ul',
					[ 'class' => 'mw-blockip-alreadyblocked' ],
					$list
				) . "\n";

				$text .= $s;
			}
		}

		return $text;
	}

	/**
	 * Add footer elements to the form
	 * @return string
	 */
	protected function postHtml() {
		$links = [];

		$this->getOutput()->addModuleStyles( 'mediawiki.special' );

		$linkRenderer = $this->getLinkRenderer();
		// Link to the user's contributions, if applicable
		if ( $this->target instanceof BlockTargetWithUserPage ) {
			$contribsPage = SpecialPage::getTitleFor( 'Contributions', (string)$this->target );
			$links[] = $linkRenderer->makeLink(
				$contribsPage,
				$this->msg( 'ipb-blocklist-contribs', (string)$this->target )->text()
			);
		}

		// Link to unblock the specified user, or to a blank unblock form
		if ( $this->target instanceof BlockTargetWithUserPage ) {
			$message = $this->msg(
				'ipb-unblock-addr',
				wfEscapeWikiText( (string)$this->target )
			)->parse();
			$list = SpecialPage::getTitleFor( 'Unblock', (string)$this->target );
		} else {
			$message = $this->msg( 'ipb-unblock' )->parse();
			$list = SpecialPage::getTitleFor( 'Unblock' );
		}
		$links[] = $linkRenderer->makeKnownLink(
			$list,
			new HtmlArmor( $message )
		);

		// Link to the block list
		$links[] = $linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'BlockList' ),
			$this->msg( 'ipb-blocklist' )->text()
		);

		// Link to edit the block dropdown reasons, if applicable
		if ( $this->getAuthority()->isAllowed( 'editinterface' ) ) {
			$links[] = $linkRenderer->makeKnownLink(
				$this->msg( 'ipbreason-dropdown' )->inContentLanguage()->getTitle(),
				$this->msg( 'ipb-edit-dropdown' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
		}

		$text = Html::rawElement(
			'p',
			[ 'class' => 'mw-ipb-conveniencelinks' ],
			$this->getLanguage()->pipeList( $links )
		);

		if ( $this->target ) {
			$userPage = $this->target->getLogPage();
			// Get relevant extracts from the block and suppression logs, if possible
			$out = '';

			LogEventsList::showLogExtract(
				$out,
				'block',
				$userPage,
				'',
				[
					'lim' => 10,
					'msgKey' => [
						'blocklog-showlog',
						$this->titleFormatter->getText( $userPage ),
					],
					'showIfEmpty' => false
				]
			);
			$text .= $out;

			// Add suppression block entries if allowed
			if ( $this->getAuthority()->isAllowed( 'suppressionlog' ) ) {
				LogEventsList::showLogExtract(
					$out,
					'suppress',
					$userPage,
					'',
					[
						'lim' => 10,
						'conds' => [ 'log_action' => [ 'block', 'reblock', 'unblock' ] ],
						'msgKey' => [
							'blocklog-showsuppresslog',
							$this->titleFormatter->getText( $userPage ),
						],
						'showIfEmpty' => false
					]
				);

				$text .= $out;
			}
		}

		return $text;
	}

	/**
	 * Get the target and type, given the request and the subpage parameter.
	 * Several parameters are handled for backwards compatability. A block ID
	 * is prioritized, followed by 'wpTarget' since it matches the HTML form.
	 *
	 * @param string|null $par Subpage parameter passed to setup, or data value from
	 *  the HTMLForm
	 * @param WebRequest $request Try and get data from a request too
	 * @return BlockTarget|null
	 */
	private function getTargetInternal( ?string $par, WebRequest $request ) {
		// Passing in a block ID gets priority.
		$blockId = $request->getInt( 'id', 0 );
		if ( $blockId > 0 ) {
			$block = $this->blockStore->newFromId( $blockId );
			if ( $block ) {
				return $block->getRedactedTarget();
			}
		}

		$possibleTargets = [
			$request->getVal( 'wpTarget', null ),
			$par,
			$request->getVal( 'ip', null ),
			// B/C @since 1.18
			$request->getVal( 'wpBlockAddress', null ),
		];
		foreach ( $possibleTargets as $possibleTarget ) {
			$target = $this->blockTargetFactory
				->newFromString( $possibleTarget );
			// If type is not null then target is valid
			if ( $target !== null ) {
				break;
			}
		}
		return $target;
	}

	/**
	 * Process the form on POST submission.
	 * @param array $data
	 * @param HTMLForm|null $form
	 * @return bool|string|array|Status As documented for HTMLForm::trySubmit.
	 */
	public function onSubmit( array $data, ?HTMLForm $form = null ) {
		if ( $this->getConfig()->get( MainConfigNames::UseCodexSpecialBlock )
			|| $this->getRequest()->getBool( 'usecodex' )
		) {
			// Treat as no submission for the JS-only Codex form.
			// This happens if the form is submitted before any JS is loaded.
			return false;
		}
		// Temporarily access service container until the feature flag is removed: T280532
		$enablePartialActionBlocks = $this->getConfig()
			->get( MainConfigNames::EnablePartialActionBlocks );

		$isPartialBlock = isset( $data['EditingRestriction'] ) &&
			$data['EditingRestriction'] === 'partial';

		// This might have been a hidden field or a checkbox, so interesting data
		// can come from it
		$data['Confirm'] = !in_array( $data['Confirm'], [ '', '0', null, false ], true );

		// If the user has done the form 'properly', they won't even have been given the
		// option to suppress-block unless they have the 'hideuser' permission
		if ( !isset( $data['HideUser'] ) ) {
			$data['HideUser'] = false;
		}

		/** @var User $target */
		$target = $this->blockTargetFactory->newFromString( $data['Target'] );
		if ( $target instanceof UserBlockTarget ) {
			// Give admins a heads-up before they go and block themselves.  Much messier
			// to do this for IPs, but it's pretty unlikely they'd ever get the 'block'
			// permission anyway, although the code does allow for it.
			// Note: Important to use $target instead of $data['Target']
			// since both $data['PreviousTarget'] and $target are normalized
			// but $data['Target'] gets overridden by (non-normalized) request variable
			// from previous request.
			if ( $target->toString() === $this->getUser()->getName() &&
				( $data['PreviousTarget'] !== $target->toString() || !$data['Confirm'] )
			) {
				return [ 'ipb-blockingself', 'ipb-confirmaction' ];
			}

			if ( $data['HideUser'] && !$data['Confirm'] ) {
				return [ 'ipb-confirmhideuser', 'ipb-confirmaction' ];
			}
		} elseif ( !( $target instanceof AnonIpBlockTarget || $target instanceof RangeBlockTarget ) ) {
			// This should have been caught in the form field validation
			return [ 'badipaddress' ];
		}

		// Reason, to be passed to the block object. For default values of reason, see
		// HTMLSelectAndOtherField::getDefault
		$blockReason = $data['Reason'][0] ?? '';

		$pageRestrictions = [];
		$namespaceRestrictions = [];
		$actionRestrictions = [];
		if ( $isPartialBlock ) {
			if ( isset( $data['PageRestrictions'] ) && $data['PageRestrictions'] !== '' ) {
				$titles = explode( "\n", $data['PageRestrictions'] );
				foreach ( $titles as $title ) {
					$pageRestrictions[] = PageRestriction::newFromTitle( $title );
				}
			}
			if ( isset( $data['NamespaceRestrictions'] ) && $data['NamespaceRestrictions'] !== '' ) {
				$namespaceRestrictions = array_map( static function ( $id ) {
					return new NamespaceRestriction( 0, (int)$id );
				}, explode( "\n", $data['NamespaceRestrictions'] ) );
			}
			if (
				$enablePartialActionBlocks &&
				isset( $data['ActionRestrictions'] ) &&
				$data['ActionRestrictions'] !== ''
			) {
				$actionRestrictions = array_map( static function ( $id ) {
					return new ActionRestriction( 0, $id );
				}, $data['ActionRestrictions'] );
			}
		}
		$restrictions = array_merge( $pageRestrictions, $namespaceRestrictions, $actionRestrictions );

		if ( !isset( $data['Tags'] ) ) {
			$data['Tags'] = [];
		}

		$blockOptions = [
			'isCreateAccountBlocked' => $data['CreateAccount'],
			'isHardBlock' => $data['HardBlock'],
			'isAutoblocking' => $data['AutoBlock'],
			'isHideUser' => $data['HideUser'],
			'isPartial' => $isPartialBlock,
		];

		if ( isset( $data['DisableUTEdit'] ) ) {
			$blockOptions['isUserTalkEditBlocked'] = $data['DisableUTEdit'];
		}
		if ( isset( $data['DisableEmail'] ) ) {
			$blockOptions['isEmailBlocked'] = $data['DisableEmail'];
		}

		$blockUser = $this->blockUserFactory->newBlockUser(
			$target,
			$this->getAuthority(),
			$data['Expiry'],
			$blockReason,
			$blockOptions,
			$restrictions,
			$data['Tags']
		);

		// Indicates whether the user is confirming the block and is aware of
		// the conflict (did not change the block target in the meantime)
		$blockNotConfirmed = !$data['Confirm'] || ( array_key_exists( 'PreviousTarget', $data )
			&& $data['PreviousTarget'] !== $target->toString() );

		// Special case for API - T34434
		$reblockNotAllowed = ( array_key_exists( 'Reblock', $data ) && !$data['Reblock'] );

		$doReblock = !$blockNotConfirmed && !$reblockNotAllowed;

		try {
			$status = $blockUser->placeBlock( $doReblock );
		} catch ( MultiblocksException ) {
			$status = Status::newFatal( 'block-reblock-multi-legacy' );
		}

		if ( !$status->isOK() ) {
			return $status;
		}

		if (
			// Can't watch a range block
			$target instanceof BlockTargetWithUserPage

			// Technically a wiki can be configured to allow anonymous users to place blocks,
			// in which case the 'Watch' field isn't included in the form shown, and we should
			// not try to access it.
			&& array_key_exists( 'Watch', $data )
			&& $data['Watch']
		) {
			$this->watchlistManager->addWatchIgnoringRights(
				$this->getUser(),
				Title::newFromPageReference( $target->getUserPage() )
			);
		}

		return true;
	}

	/**
	 * Do something exciting on successful processing of the form, most likely to show a
	 * confirmation message
	 */
	public function onSuccess() {
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'blockipsuccesssub' ) );
		$out->addWikiMsg( 'blockipsuccesstext', wfEscapeWikiText( (string)$this->target ) );
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		$search = $this->userNameUtils->getCanonical( $search );
		if ( !$search ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return $this->userNamePrefixSearch
			->search( UserNamePrefixSearch::AUDIENCE_PUBLIC, $search, $limit, $offset );
	}

	/**
	 * @inheritDoc
	 */
	protected function getGroupName() {
		return 'users';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialBlock::class, 'SpecialBlock' );
