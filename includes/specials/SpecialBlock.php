<?php
/**
 * Implements Special:Block
 *
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
 * @ingroup SpecialPage
 */

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\User\UserIdentity;
use Wikimedia\IPUtils;

/**
 * A special page that allows users with 'block' right to block users from
 * editing pages and other actions
 *
 * @ingroup SpecialPage
 */
class SpecialBlock extends FormSpecialPage {
	/**
	 * @var PermissionManager
	 */
	private $permissionManager;

	/** @var User|string|null User to be blocked, as passed either by parameter (url?wpTarget=Foo)
	 * or as subpage (Special:Block/Foo)
	 */
	protected $target;

	/** @var int DatabaseBlock::TYPE_ constant */
	protected $type;

	/** @var User|string The previous block target */
	protected $previousTarget;

	/** @var bool Whether the previous submission of the form asked for HideUser */
	protected $requestedHideUser;

	/** @var bool */
	protected $alreadyBlocked;

	/** @var array */
	protected $preErrors = [];

	public function __construct( PermissionManager $permissionManager ) {
		parent::__construct( 'Block', 'block' );

		$this->permissionManager = $permissionManager;
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * Checks that the user can unblock themselves if they are trying to do so
	 *
	 * @param User $user
	 * @throws ErrorPageError
	 */
	protected function checkExecutePermissions( User $user ) {
		parent::checkExecutePermissions( $user );
		# T17810: blocked admins should have limited access here
		$status = self::checkUnblockSelf( $this->target, $user );
		if ( $status !== true ) {
			throw new ErrorPageError( 'badaccess', $status );
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
		# Extract variables from the request.  Try not to get into a situation where we
		# need to extract *every* variable from the form just for processing here, but
		# there are legitimate uses for some variables
		$request = $this->getRequest();
		list( $this->target, $this->type ) = self::getTargetAndType( $par, $request );
		if ( $this->target instanceof User ) {
			# Set the 'relevant user' in the skin, so it displays links like Contributions,
			# User logs, UserRights, etc.
			$this->getSkin()->setRelevantUser( $this->target );
		}

		list( $this->previousTarget, /*...*/ ) =
			DatabaseBlock::parseTarget( $request->getVal( 'wpPreviousTarget' ) );
		$this->requestedHideUser = $request->getBool( 'wpHideUser' );
	}

	/**
	 * Customizes the HTMLForm a bit
	 *
	 * @param HTMLForm $form
	 */
	protected function alterForm( HTMLForm $form ) {
		$form->setHeaderText( '' );
		$form->setSubmitDestructive();

		$msg = $this->alreadyBlocked ? 'ipb-change-block' : 'ipbsubmit';
		$form->setSubmitTextMsg( $msg );

		$this->addHelpLink( 'Help:Blocking users' );

		# Don't need to do anything if the form has been posted
		if ( !$this->getRequest()->wasPosted() && $this->preErrors ) {
			$s = $form->formatErrors( $this->preErrors );
			if ( $s ) {
				$form->addHeaderText( Html::rawElement(
					'div',
					[ 'class' => 'error' ],
					$s
				) );
			}
		}
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	/**
	 * Get the HTMLForm descriptor array for the block form
	 * @return array
	 */
	protected function getFormFields() {
		$conf = $this->getConfig();
		$blockAllowsUTEdit = $conf->get( 'BlockAllowsUTEdit' );

		$this->getOutput()->enableOOUI();

		$user = $this->getUser();

		$suggestedDurations = self::getSuggestedDurations();

		$a = [];

		$a['Target'] = [
			'type' => 'user',
			'ipallowed' => true,
			'iprange' => true,
			'id' => 'mw-bi-target',
			'size' => '45',
			'autofocus' => true,
			'required' => true,
			'validation-callback' => [ __CLASS__, 'validateTargetField' ],
			'section' => 'target',
		];

		$a['Editing'] = [
			'type' => 'check',
			'label-message' => 'block-prevent-edit',
			'default' => true,
			'section' => 'actions',
		];

		$a['EditingRestriction'] = [
			'type' => 'radio',
			'cssclass' => 'mw-block-editing-restriction',
			'default' => 'sitewide',
			'options' => [
				$this->msg( 'ipb-sitewide' )->escaped() .
					new \OOUI\LabelWidget( [
						'classes' => [ 'oo-ui-inline-help' ],
						'label' => $this->msg( 'ipb-sitewide-help' )->text(),
					] ) => 'sitewide',
				$this->msg( 'ipb-partial' )->escaped() .
					new \OOUI\LabelWidget( [
						'classes' => [ 'oo-ui-inline-help' ],
						'label' => $this->msg( 'ipb-partial-help' )->text(),
					] ) => 'partial',
			],
			'section' => 'actions',
		];

		$a['PageRestrictions'] = [
			'type' => 'titlesmultiselect',
			'label' => $this->msg( 'ipb-pages-label' )->text(),
			'exists' => true,
			'max' => 10,
			'cssclass' => 'mw-block-restriction',
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
			'cssclass' => 'mw-block-restriction',
			'input' => [
				'autocomplete' => false
			],
			'section' => 'actions',
		];

		$a['CreateAccount'] = [
			'type' => 'check',
			'label-message' => 'ipbcreateaccount',
			'default' => true,
			'section' => 'actions',
		];

		if ( self::canBlockEmail( $user ) ) {
			$a['DisableEmail'] = [
				'type' => 'check',
				'label-message' => 'ipbemailban',
				'section' => 'actions',
			];
		}

		if ( $blockAllowsUTEdit ) {
			$a['DisableUTEdit'] = [
				'type' => 'check',
				'label-message' => 'ipb-disableusertalk',
				'default' => false,
				'section' => 'actions',
			];
		}

		$defaultExpiry = $this->msg( 'ipb-default-expiry' )->inContentLanguage();
		if ( $this->type === DatabaseBlock::TYPE_RANGE || $this->type === DatabaseBlock::TYPE_IP ) {
			$defaultExpiryIP = $this->msg( 'ipb-default-expiry-ip' )->inContentLanguage();
			if ( !$defaultExpiryIP->isDisabled() ) {
				$defaultExpiry = $defaultExpiryIP;
			}
		}

		$a['Expiry'] = [
			'type' => 'expiry',
			'required' => true,
			'options' => $suggestedDurations,
			'default' => $defaultExpiry->text(),
			'section' => 'expiry',
		];

		$a['Reason'] = [
			'type' => 'selectandother',
			// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
			// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
			// Unicode codepoints.
			'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
			'maxlength-unit' => 'codepoints',
			'options-message' => 'ipbreason-dropdown',
			'section' => 'reason',
		];

		$a['AutoBlock'] = [
			'type' => 'check',
			'label-message' => 'ipbenableautoblock',
			'default' => true,
			'section' => 'options',
		];

		# Allow some users to hide name from block log, blocklist and listusers
		if ( $this->permissionManager->userHasRight( $user, 'hideuser' ) ) {
			$a['HideUser'] = [
				'type' => 'check',
				'label-message' => 'ipbhidename',
				'cssclass' => 'mw-block-hideuser',
				'section' => 'options',
			];
		}

		# Watchlist their user page? (Only if user is logged in)
		if ( $user->isLoggedIn() ) {
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

		# This is basically a copy of the Target field, but the user can't change it, so we
		# can see if the warnings we maybe showed to the user before still apply
		$a['PreviousTarget'] = [
			'type' => 'hidden',
			'default' => false,
		];

		# We'll turn this into a checkbox if we need to
		$a['Confirm'] = [
			'type' => 'hidden',
			'default' => '',
			'label-message' => 'ipb-confirm',
			'cssclass' => 'mw-block-confirm',
		];

		$this->maybeAlterFormDefaults( $a );

		// Allow extensions to add more fields
		$this->getHookRunner()->onSpecialBlockModifyFormFields( $this, $a );

		return $a;
	}

	/**
	 * If the user has already been blocked with similar settings, load that block
	 * and change the defaults for the form fields to match the existing settings.
	 * @param array &$fields HTMLForm descriptor array
	 */
	protected function maybeAlterFormDefaults( &$fields ) {
		# This will be overwritten by request data
		$fields['Target']['default'] = (string)$this->target;

		if ( $this->target ) {
			$status = self::validateTarget( $this->target, $this->getUser() );
			if ( !$status->isOK() ) {
				$errors = $status->getErrorsArray();
				$this->preErrors = array_merge( $this->preErrors, $errors );
			}
		}

		# This won't be
		$fields['PreviousTarget']['default'] = (string)$this->target;

		$block = DatabaseBlock::newFromTarget( $this->target );

		// Populate fields if there is a block that is not an autoblock; if it is a range
		// block, only populate the fields if the range is the same as $this->target
		if ( $block instanceof DatabaseBlock && $block->getType() !== DatabaseBlock::TYPE_AUTO
			&& ( $this->type != DatabaseBlock::TYPE_RANGE
				|| $block->getTarget() == $this->target )
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

			// If the username was hidden (ipb_deleted == 1), don't show the reason
			// unless this user also has rights to hideuser: T37839
			if ( !$block->getHideName() || $this->permissionManager
					->userHasRight( $this->getUser(), 'hideuser' )
			) {
				$fields['Reason']['default'] = $block->getReasonComment()->text;
			} else {
				$fields['Reason']['default'] = '';
			}

			if ( $this->getRequest()->wasPosted() ) {
				# Ok, so we got a POST submission asking us to reblock a user.  So show the
				# confirm checkbox; the user will only see it if they haven't previously
				$fields['Confirm']['type'] = 'check';
			} else {
				# We got a target, but it wasn't a POST request, so the user must have gone
				# to a link like [[Special:Block/User]].  We don't need to show the checkbox
				# as long as they go ahead and block *that* user
				$fields['Confirm']['default'] = 1;
			}

			if ( $block->getExpiry() == 'infinity' ) {
				$fields['Expiry']['default'] = 'infinite';
			} else {
				$fields['Expiry']['default'] = wfTimestamp( TS_RFC2822, $block->getExpiry() );
			}

			if ( !$block->isSitewide() ) {
				$fields['EditingRestriction']['default'] = 'partial';

				$pageRestrictions = [];
				$namespaceRestrictions = [];
				foreach ( $block->getRestrictions() as $restriction ) {
					if ( $restriction instanceof PageRestriction && $restriction->getTitle() ) {
						$pageRestrictions[] = $restriction->getTitle()->getPrefixedText();
					} elseif ( $restriction instanceof NamespaceRestriction ) {
						$namespaceRestrictions[] = $restriction->getValue();
					}
				}

				// Sort the restrictions so they are in alphabetical order.
				sort( $pageRestrictions );
				$fields['PageRestrictions']['default'] = implode( "\n", $pageRestrictions );
				sort( $namespaceRestrictions );
				$fields['NamespaceRestrictions']['default'] = implode( "\n", $namespaceRestrictions );

				if (
					// @phan-suppress-next-line PhanImpossibleCondition
					empty( $pageRestrictions ) &&
					// @phan-suppress-next-line PhanImpossibleCondition
					empty( $namespaceRestrictions )
				) {
					$fields['Editing']['default'] = false;
				}
			}

			$this->alreadyBlocked = true;
			$this->preErrors[] = [ 'ipb-needreblock', wfEscapeWikiText( (string)$block->getTarget() ) ];
		}

		if ( $this->alreadyBlocked || $this->getRequest()->wasPosted()
			|| $this->getRequest()->getCheck( 'wpCreateAccount' )
		) {
			$this->getOutput()->addJsConfigVars( 'wgCreateAccountDirty', true );
		}

		# We always need confirmation to do HideUser
		if ( $this->requestedHideUser ) {
			$fields['Confirm']['type'] = 'check';
			unset( $fields['Confirm']['default'] );
			$this->preErrors[] = [ 'ipb-confirmhideuser', 'ipb-confirmaction' ];
		}

		# Or if the user is trying to block themselves
		if ( (string)$this->target === $this->getUser()->getName() ) {
			$fields['Confirm']['type'] = 'check';
			unset( $fields['Confirm']['default'] );
			$this->preErrors[] = [ 'ipb-blockingself', 'ipb-confirmaction' ];
		}
	}

	/**
	 * Add header elements like block log entries, etc.
	 * @return string
	 */
	protected function preText() {
		$this->getOutput()->addModuleStyles( [
			'mediawiki.widgets.TagMultiselectWidget.styles',
			'mediawiki.special',
		] );
		$this->getOutput()->addModules( [ 'mediawiki.special.block' ] );

		$blockCIDRLimit = $this->getConfig()->get( 'BlockCIDRLimit' );
		$text = $this->msg( 'blockiptext', $blockCIDRLimit['IPv4'], $blockCIDRLimit['IPv6'] )->parse();

		$otherBlockMessages = [];
		if ( $this->target !== null ) {
			$targetName = $this->target;
			if ( $this->target instanceof User ) {
				$targetName = $this->target->getName();
			}
			# Get other blocks, i.e. from GlobalBlocking or TorBlock extension
			$this->getHookRunner()->onOtherBlockLogLink(
				$otherBlockMessages, $targetName );

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
	protected function postText() {
		$links = [];

		$this->getOutput()->addModuleStyles( 'mediawiki.special' );

		$linkRenderer = $this->getLinkRenderer();
		# Link to the user's contributions, if applicable
		if ( $this->target instanceof User ) {
			$contribsPage = SpecialPage::getTitleFor( 'Contributions', $this->target->getName() );
			$links[] = $linkRenderer->makeLink(
				$contribsPage,
				$this->msg( 'ipb-blocklist-contribs', $this->target->getName() )->text()
			);
		}

		# Link to unblock the specified user, or to a blank unblock form
		if ( $this->target instanceof User ) {
			$message = $this->msg(
				'ipb-unblock-addr',
				wfEscapeWikiText( $this->target->getName() )
			)->parse();
			$list = SpecialPage::getTitleFor( 'Unblock', $this->target->getName() );
		} else {
			$message = $this->msg( 'ipb-unblock' )->parse();
			$list = SpecialPage::getTitleFor( 'Unblock' );
		}
		$links[] = $linkRenderer->makeKnownLink(
			$list,
			new HtmlArmor( $message )
		);

		# Link to the block list
		$links[] = $linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'BlockList' ),
			$this->msg( 'ipb-blocklist' )->text()
		);

		$user = $this->getUser();

		# Link to edit the block dropdown reasons, if applicable
		if ( $this->permissionManager->userHasRight( $user, 'editinterface' ) ) {
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

		$userTitle = self::getTargetUserTitle( $this->target );
		if ( $userTitle ) {
			# Get relevant extracts from the block and suppression logs, if possible
			$out = '';

			LogEventsList::showLogExtract(
				$out,
				'block',
				$userTitle,
				'',
				[
					'lim' => 10,
					'msgKey' => [ 'blocklog-showlog', $userTitle->getText() ],
					'showIfEmpty' => false
				]
			);
			$text .= $out;

			# Add suppression block entries if allowed
			if ( $this->permissionManager->userHasRight( $user, 'suppressionlog' ) ) {
				LogEventsList::showLogExtract(
					$out,
					'suppress',
					$userTitle,
					'',
					[
						'lim' => 10,
						'conds' => [ 'log_action' => [ 'block', 'reblock', 'unblock' ] ],
						'msgKey' => [ 'blocklog-showsuppresslog', $userTitle->getText() ],
						'showIfEmpty' => false
					]
				);

				$text .= $out;
			}
		}

		return $text;
	}

	/**
	 * Get a user page target for things like logs.
	 * This handles account and IP range targets.
	 * @param User|string $target
	 * @return Title|null
	 */
	protected static function getTargetUserTitle( $target ) {
		if ( $target instanceof User ) {
			return $target->getUserPage();
		} elseif ( IPUtils::isIPAddress( $target ) ) {
			return Title::makeTitleSafe( NS_USER, $target );
		}

		return null;
	}

	/**
	 * Determine the target of the block, and the type of target
	 * @todo Should be in DatabaseBlock.php?
	 * @param string $par Subpage parameter passed to setup, or data value from
	 *     the HTMLForm
	 * @param WebRequest|null $request Optionally try and get data from a request too
	 * @return array [ User|string|null, DatabaseBlock::TYPE_ constant|null ]
	 * @phan-return array{0:User|string|null,1:int|null}
	 */
	public static function getTargetAndType( $par, WebRequest $request = null ) {
		$i = 0;
		$target = null;

		while ( true ) {
			switch ( $i++ ) {
				case 0:
					# The HTMLForm will check wpTarget first and only if it doesn't get
					# a value use the default, which will be generated from the options
					# below; so this has to have a higher precedence here than $par, or
					# we could end up with different values in $this->target and the HTMLForm!
					if ( $request instanceof WebRequest ) {
						$target = $request->getText( 'wpTarget', null );
					}
					break;
				case 1:
					$target = $par;
					break;
				case 2:
					if ( $request instanceof WebRequest ) {
						$target = $request->getText( 'ip', null );
					}
					break;
				case 3:
					# B/C @since 1.18
					if ( $request instanceof WebRequest ) {
						$target = $request->getText( 'wpBlockAddress', null );
					}
					break;
				case 4:
					break 2;
			}

			list( $target, $type ) = DatabaseBlock::parseTarget( $target );

			if ( $type !== null ) {
				return [ $target, $type ];
			}
		}

		return [ null, null ];
	}

	/**
	 * HTMLForm field validation-callback for Target field.
	 * @since 1.18
	 * @param string $value
	 * @param array $alldata
	 * @param HTMLForm $form
	 * @return Message|true
	 */
	public static function validateTargetField( $value, $alldata, $form ) {
		$status = self::validateTarget( $value, $form->getUser() );
		if ( !$status->isOK() ) {
			$errors = $status->getErrorsArray();

			return $form->msg( ...$errors[0] );
		} else {
			return true;
		}
	}

	/**
	 * Validate a block target.
	 *
	 * @since 1.21
	 * @param string $value Block target to check
	 * @param User $user Performer of the block
	 * @return Status
	 */
	public static function validateTarget( $value, User $user ) {
		global $wgBlockCIDRLimit;

		/** @var User $target */
		list( $target, $type ) = self::getTargetAndType( $value );
		$status = Status::newGood( $target );

		if ( $type == DatabaseBlock::TYPE_USER ) {
			if ( $target->isAnon() ) {
				$status->fatal(
					'nosuchusershort',
					wfEscapeWikiText( $target->getName() )
				);
			}

			$unblockStatus = self::checkUnblockSelf( $target, $user );
			if ( $unblockStatus !== true ) {
				$status->fatal( 'badaccess', $unblockStatus );
			}
		} elseif ( $type == DatabaseBlock::TYPE_RANGE ) {
			list( $ip, $range ) = explode( '/', $target, 2 );

			if (
				( IPUtils::isIPv4( $ip ) && $wgBlockCIDRLimit['IPv4'] == 32 ) ||
				( IPUtils::isIPv6( $ip ) && $wgBlockCIDRLimit['IPv6'] == 128 )
			) {
				// Range block effectively disabled
				$status->fatal( 'range_block_disabled' );
			}

			if (
				( IPUtils::isIPv4( $ip ) && $range > 32 ) ||
				( IPUtils::isIPv6( $ip ) && $range > 128 )
			) {
				// Dodgy range
				$status->fatal( 'ip_range_invalid' );
			}

			if ( IPUtils::isIPv4( $ip ) && $range < $wgBlockCIDRLimit['IPv4'] ) {
				$status->fatal( 'ip_range_toolarge', $wgBlockCIDRLimit['IPv4'] );
			}

			if ( IPUtils::isIPv6( $ip ) && $range < $wgBlockCIDRLimit['IPv6'] ) {
				$status->fatal( 'ip_range_toolarge', $wgBlockCIDRLimit['IPv6'] );
			}
		} elseif ( $type == DatabaseBlock::TYPE_IP ) {
			# All is well
		} else {
			$status->fatal( 'badipaddress' );
		}

		return $status;
	}

	/**
	 * Given the form data, actually implement a block. This is also called from ApiBlock.
	 *
	 * @param array $data
	 * @param IContextSource $context
	 * @return bool|array
	 */
	public static function processForm( array $data, IContextSource $context ) {
		$performer = $context->getUser();
		$isPartialBlock = isset( $data['EditingRestriction'] ) &&
			$data['EditingRestriction'] === 'partial';

		# This might have been a hidden field or a checkbox, so interesting data
		# can come from it
		$data['Confirm'] = !in_array( $data['Confirm'], [ '', '0', null, false ], true );

		/** @var User $target */
		list( $target, $type ) = self::getTargetAndType( $data['Target'] );
		if ( $type == DatabaseBlock::TYPE_USER ) {
			$user = $target;
			$target = $user->getName();
			$userId = $user->getId();

			# Give admins a heads-up before they go and block themselves.  Much messier
			# to do this for IPs, but it's pretty unlikely they'd ever get the 'block'
			# permission anyway, although the code does allow for it.
			# Note: Important to use $target instead of $data['Target']
			# since both $data['PreviousTarget'] and $target are normalized
			# but $data['target'] gets overridden by (non-normalized) request variable
			# from previous request.
			if ( $target === $performer->getName() &&
				( $data['PreviousTarget'] !== $target || !$data['Confirm'] )
			) {
				return [ 'ipb-blockingself', 'ipb-confirmaction' ];
			}
		} elseif ( $type == DatabaseBlock::TYPE_RANGE ) {
			$user = null;
			$userId = 0;
		} elseif ( $type == DatabaseBlock::TYPE_IP ) {
			$user = null;
			$target = $target->getName();
			$userId = 0;
		} else {
			# This should have been caught in the form field validation
			return [ 'badipaddress' ];
		}

		// Reason, to be passed to the block object. For default values of reason, see
		// HTMLSelectAndOtherField::getDefault
		// @phan-suppress-next-line PhanPluginDuplicateConditionalNullCoalescing
		$blockReason = isset( $data['Reason'][0] ) ? $data['Reason'][0] : '';

		$expiryTime = self::parseExpiryInput( $data['Expiry'] );

		if (
			// an expiry time is needed
			( strlen( $data['Expiry'] ) == 0 ) ||
			// can't be a larger string as 50 (it should be a time format in any way)
			( strlen( $data['Expiry'] ) > 50 ) ||
			// check, if the time could be parsed
			!$expiryTime
		) {
			return [ 'ipb_expiry_invalid' ];
		}

		// an expiry time should be in the future, not in the
		// past (wouldn't make any sense) - bug T123069
		if ( $expiryTime < wfTimestampNow() ) {
			return [ 'ipb_expiry_old' ];
		}

		if ( !isset( $data['DisableEmail'] ) ) {
			$data['DisableEmail'] = false;
		}

		# If the user has done the form 'properly', they won't even have been given the
		# option to suppress-block unless they have the 'hideuser' permission
		if ( !isset( $data['HideUser'] ) ) {
			$data['HideUser'] = false;
		}

		if ( $data['HideUser'] ) {
			if ( !MediaWikiServices::getInstance()
				->getPermissionManager()
				->userHasRight( $performer, 'hideuser' )
			) {
				# this codepath is unreachable except by a malicious user spoofing forms,
				# or by race conditions (user has hideuser and block rights, loads block form,
				# and loses hideuser rights before submission); so need to fail completely
				# rather than just silently disable hiding
				return [ 'badaccess-group0' ];
			}

			if ( $isPartialBlock ) {
				return [ 'ipb_hide_partial' ];
			}

			# Recheck params here...
			$hideUserContribLimit = $context->getConfig()->get( 'HideUserContribLimit' );
			if ( $type != DatabaseBlock::TYPE_USER ) {
				$data['HideUser'] = false; # IP users should not be hidden
			} elseif ( !wfIsInfinity( $data['Expiry'] ) ) {
				# Bad expiry.
				return [ 'ipb_expiry_temp' ];
			} elseif ( $hideUserContribLimit !== false
				&& $user->getEditCount() > $hideUserContribLimit
			) {
				# Typically, the user should have a handful of edits.
				# Disallow hiding users with many edits for performance.
				return [ [ 'ipb_hide_invalid',
					Message::numParam( $hideUserContribLimit ) ] ];
			} elseif ( !$data['Confirm'] ) {
				return [ 'ipb-confirmhideuser', 'ipb-confirmaction' ];
			}
		}

		// Check whether the user can edit their own user talk page.
		$blockAllowsUTEdit = $context->getConfig()->get( 'BlockAllowsUTEdit' );
		$isUserTalkNamespaceBlock = !$isPartialBlock ||
			in_array( NS_USER_TALK, explode( "\n", $data['NamespaceRestrictions'] ) );
		if ( $isUserTalkNamespaceBlock ) {
			// If the block blocks the user talk namespace, disallow own user talk edit if
			// the global config disallows it; otherwise use the form field value.
			$userTalkEditAllowed = $blockAllowsUTEdit ? !$data['DisableUTEdit'] : false;
		} else {
			// If the block doesn't block the user talk namespace, then it can't block own
			// user talk edit, regardless of the config or field (T210475). Return error
			// message if the field tries to disallow own user talk edit.
			if ( isset( $data['DisableUTEdit'] ) && $data['DisableUTEdit'] ) {
				return [ 'ipb-prevent-user-talk-edit' ];
			}
			$userTalkEditAllowed = true;
		}

		// A block is empty if it is a partial block, the page restrictions are empty, the
		// namespace restrictions are empty, and none of the actions are enabled
		if ( $isPartialBlock &&
			!( isset( $data['PageRestrictions'] ) && $data['PageRestrictions'] !== '' ) &&
			!( isset( $data['NamespaceRestrictions'] ) && $data['NamespaceRestrictions'] !== '' ) &&
			$data['DisableEmail'] === false &&
			( $userTalkEditAllowed || !$blockAllowsUTEdit ) &&
			!$data['CreateAccount']
		) {
			return [ 'ipb-empty-block' ];
		}

		# Create block object.
		$block = new DatabaseBlock();
		$block->setTarget( $target );
		$block->setBlocker( $performer );
		$block->setReason( $blockReason );
		$block->setExpiry( $expiryTime );
		$block->isCreateAccountBlocked( $data['CreateAccount'] );
		$block->isUsertalkEditAllowed( $userTalkEditAllowed );
		$block->isEmailBlocked( $data['DisableEmail'] );
		$block->isHardblock( $data['HardBlock'] );
		$block->isAutoblocking( $data['AutoBlock'] );
		$block->setHideName( $data['HideUser'] );

		if ( $isPartialBlock ) {
			$block->isSitewide( false );
		}

		$reason = [ 'hookaborted' ];
		if ( !Hooks::runner()->onBlockIp( $block, $performer, $reason ) ) {
			return $reason;
		}

		$pageRestrictions = [];
		$namespaceRestrictions = [];
		if ( isset( $data['PageRestrictions'] ) && $data['PageRestrictions'] !== '' ) {
			$pageRestrictions = array_map( function ( $text ) {
				$title = Title::newFromText( $text );
				// Use the link cache since the title has already been loaded when
				// the field was validated.
				$restriction = new PageRestriction( 0, $title->getArticleID() );
				$restriction->setTitle( $title );
				return $restriction;
			}, explode( "\n", $data['PageRestrictions'] ) );
		}
		if ( isset( $data['NamespaceRestrictions'] ) && $data['NamespaceRestrictions'] !== '' ) {
			$namespaceRestrictions = array_map( function ( $id ) {
				return new NamespaceRestriction( 0, $id );
			}, explode( "\n", $data['NamespaceRestrictions'] ) );
		}

		$restrictions = ( array_merge( $pageRestrictions, $namespaceRestrictions ) );
		$block->setRestrictions( $restrictions );

		$priorBlock = null;
		# Try to insert block. Is there a conflicting block?
		$status = $block->insert();
		if ( !$status ) {
			# Indicates whether the user is confirming the block and is aware of
			# the conflict (did not change the block target in the meantime)
			$blockNotConfirmed = !$data['Confirm'] || ( array_key_exists( 'PreviousTarget', $data )
				&& $data['PreviousTarget'] !== $target );

			# Special case for API - T34434
			$reblockNotAllowed = ( array_key_exists( 'Reblock', $data ) && !$data['Reblock'] );

			# Show form unless the user is already aware of this...
			if ( $blockNotConfirmed || $reblockNotAllowed ) {
				return [ [ 'ipb_already_blocked', $block->getTarget() ] ];
				# Otherwise, try to update the block...
			} else {
				# This returns direct blocks before autoblocks/rangeblocks, since we should
				# be sure the user is blocked by now it should work for our purposes
				$currentBlock = DatabaseBlock::newFromTarget( $target );
				if ( !$currentBlock instanceof DatabaseBlock ) {
					$logger = LoggerFactory::getInstance( 'BlockManager' );
					$logger->warning( 'Block could not be inserted. No existing block was found.' );
					return [ [ 'ipb-block-not-found', $block->getTarget() ] ];
				}
				if ( $block->equals( $currentBlock ) ) {
					return [ [ 'ipb_already_blocked', $block->getTarget() ] ];
				}
				# If the name was hidden and the blocking user cannot hide
				# names, then don't allow any block changes...
				if ( $currentBlock->getHideName() && !MediaWikiServices::getInstance()
						->getPermissionManager()
						->userHasRight( $performer, 'hideuser' )
				) {
					return [ 'cant-see-hidden-user' ];
				}

				$priorBlock = clone $currentBlock;
				$currentBlock->setBlocker( $performer );
				$currentBlock->isHardblock( $block->isHardblock() );
				$currentBlock->isCreateAccountBlocked( $block->isCreateAccountBlocked() );
				$currentBlock->setExpiry( $block->getExpiry() );
				$currentBlock->isAutoblocking( $block->isAutoblocking() );
				$currentBlock->setHideName( $block->getHideName() );
				$currentBlock->isEmailBlocked( $block->isEmailBlocked() );
				$currentBlock->isUsertalkEditAllowed( $block->isUsertalkEditAllowed() );
				$currentBlock->setReason( $block->getReasonComment() );

				// Maintain the sitewide status. If partial blocks is not enabled,
				// saving the block will result in a sitewide block.
				$currentBlock->isSitewide( $block->isSitewide() );

				// Set the block id of the restrictions.
				$blockRestrictionStore = MediaWikiServices::getInstance()->getBlockRestrictionStore();
				$currentBlock->setRestrictions(
					$blockRestrictionStore->setBlockId( $currentBlock->getId(), $restrictions )
				);

				$status = $currentBlock->update();
				// TODO handle failure

				$logaction = 'reblock';

				# Unset _deleted fields if requested
				if ( $currentBlock->getHideName() && !$data['HideUser'] ) {
					RevisionDeleteUser::unsuppressUserName( $target, $userId );
				}

				# If hiding/unhiding a name, this should go in the private logs
				if ( (bool)$currentBlock->getHideName() ) {
					$data['HideUser'] = true;
				}

				$block = $currentBlock;
			}
		} else {
			$logaction = 'block';
		}

		Hooks::runner()->onBlockIpComplete( $block, $performer, $priorBlock );

		# Set *_deleted fields if requested
		if ( $data['HideUser'] ) {
			RevisionDeleteUser::suppressUserName( $target, $userId );
		}

		# Can't watch a rangeblock
		if ( $type != DatabaseBlock::TYPE_RANGE && $data['Watch'] ) {
			WatchAction::doWatch(
				Title::makeTitle( NS_USER, $target ),
				$performer,
				User::IGNORE_USER_RIGHTS
			);
		}

		# DatabaseBlock constructor sanitizes certain block options on insert
		$data['BlockEmail'] = $block->isEmailBlocked();
		$data['AutoBlock'] = $block->isAutoblocking();

		# Prepare log parameters
		$logParams = [];

		$rawExpiry = $data['Expiry'];
		$logExpiry = wfIsInfinity( $rawExpiry ) ? 'infinity' : $rawExpiry;

		$logParams['5::duration'] = $logExpiry;
		$logParams['6::flags'] = self::blockLogFlags( $data, $type );
		$logParams['sitewide'] = $block->isSitewide();

		if ( !$block->isSitewide() ) {
			if ( $data['PageRestrictions'] !== '' ) {
				$logParams['7::restrictions']['pages'] = explode( "\n", $data['PageRestrictions'] );
			}

			if ( $data['NamespaceRestrictions'] !== '' ) {
				$logParams['7::restrictions']['namespaces'] = explode( "\n", $data['NamespaceRestrictions'] );
			}
		}

		# Make log entry, if the name is hidden, put it in the suppression log
		$log_type = $data['HideUser'] ? 'suppress' : 'block';
		$logEntry = new ManualLogEntry( $log_type, $logaction );
		$logEntry->setTarget( Title::makeTitle( NS_USER, $target ) );
		$logEntry->setComment( $blockReason );
		$logEntry->setPerformer( $performer );
		$logEntry->setParameters( $logParams );
		# Relate log ID to block ID (T27763)
		$logEntry->setRelations( [ 'ipb_id' => $block->getId() ] );
		$logId = $logEntry->insert();

		if ( !empty( $data['Tags'] ) ) {
			$logEntry->addTags( $data['Tags'] );
		}

		$logEntry->publish( $logId );

		return true;
	}

	/**
	 * Get an array of suggested block durations from MediaWiki:Ipboptions
	 * @todo FIXME: This uses a rather odd syntax for the options, should it be converted
	 *     to the standard "**<duration>|<displayname>" format?
	 * @param Language|null $lang The language to get the durations in, or null to use
	 *     the wiki's content language
	 * @param bool $includeOther Whether to include the 'other' option in the list of
	 *     suggestions
	 * @return string[]
	 */
	public static function getSuggestedDurations( Language $lang = null, $includeOther = true ) {
		$msg = $lang === null
			? wfMessage( 'ipboptions' )->inContentLanguage()->text()
			: wfMessage( 'ipboptions' )->inLanguage( $lang )->text();

		if ( $msg == '-' ) {
			return [];
		}

		$a = XmlSelect::parseOptionsMessage( $msg );

		if ( $a && $includeOther ) {
			// if options exist, add other to the end instead of the begining (which
			// is what happens by default).
			$a[ wfMessage( 'ipbother' )->text() ] = 'other';
		}

		return $a;
	}

	/**
	 * Convert a submitted expiry time, which may be relative ("2 weeks", etc) or absolute
	 * ("24 May 2034", etc), into an absolute timestamp we can put into the database.
	 *
	 * @todo strtotime() only accepts English strings. This means the expiry input
	 *       can only be specified in English.
	 * @see https://www.php.net/manual/en/function.strtotime.php
	 *
	 * @param string $expiry Whatever was typed into the form
	 * @return string|bool Timestamp or 'infinity' or false on error.
	 */
	public static function parseExpiryInput( $expiry ) {
		if ( wfIsInfinity( $expiry ) ) {
			return 'infinity';
		}

		$expiry = strtotime( $expiry );

		if ( $expiry < 0 || $expiry === false ) {
			return false;
		}

		return wfTimestamp( TS_MW, $expiry );
	}

	/**
	 * Can we do an email block?
	 * @param UserIdentity $user The sysop wanting to make a block
	 * @return bool
	 */
	public static function canBlockEmail( UserIdentity $user ) {
		global $wgEnableUserEmail;

		return ( $wgEnableUserEmail && MediaWikiServices::getInstance()
				->getPermissionManager()
				->userHasRight( $user, 'blockemail' ) );
	}

	/**
	 * T17810: Sitewide blocked admins should not be able to block/unblock
	 * others with one exception; they can block the user who blocked them,
	 * to reduce advantage of a malicious account blocking all admins (T150826).
	 *
	 * T208965: Partially blocked admins can block and unblock others as normal.
	 *
	 * @param User|string|null $target Target to block or unblock; could be a User object,
	 *   or username/IP address, or null when the target is not known yet (e.g. when
	 *   displaying Special:Block)
	 * @param User $performer User doing the request
	 * @return bool|string True or error message key
	 */
	public static function checkUnblockSelf( $target, User $performer ) {
		return MediaWikiServices::getInstance()
			->getBlockPermissionCheckerFactory()
			->newBlockPermissionChecker( $target, $performer )
			->checkBlockPermissions();
	}

	/**
	 * Return a comma-delimited list of "flags" to be passed to the log
	 * reader for this block, to provide more information in the logs
	 * @param array $data From HTMLForm data
	 * @param int $type DatabaseBlock::TYPE_ constant (USER, RANGE, or IP)
	 * @return string
	 */
	protected static function blockLogFlags( array $data, $type ) {
		$config = RequestContext::getMain()->getConfig();

		$blockAllowsUTEdit = $config->get( 'BlockAllowsUTEdit' );

		$flags = [];

		# when blocking a user the option 'anononly' is not available/has no effect
		# -> do not write this into log
		if ( !$data['HardBlock'] && $type != DatabaseBlock::TYPE_USER ) {
			// For grepping: message block-log-flags-anononly
			$flags[] = 'anononly';
		}

		if ( $data['CreateAccount'] ) {
			// For grepping: message block-log-flags-nocreate
			$flags[] = 'nocreate';
		}

		# Same as anononly, this is not displayed when blocking an IP address
		if ( !$data['AutoBlock'] && $type == DatabaseBlock::TYPE_USER ) {
			// For grepping: message block-log-flags-noautoblock
			$flags[] = 'noautoblock';
		}

		if ( $data['DisableEmail'] ) {
			// For grepping: message block-log-flags-noemail
			$flags[] = 'noemail';
		}

		if ( $blockAllowsUTEdit && $data['DisableUTEdit'] ) {
			// For grepping: message block-log-flags-nousertalk
			$flags[] = 'nousertalk';
		}

		if ( $data['HideUser'] ) {
			// For grepping: message block-log-flags-hiddenname
			$flags[] = 'hiddenname';
		}

		return implode( ',', $flags );
	}

	/**
	 * Process the form on POST submission.
	 * @param array $data
	 * @param HTMLForm|null $form
	 * @return bool|array True for success, false for didn't-try, array of errors on failure
	 */
	public function onSubmit( array $data, HTMLForm $form = null ) {
		// If "Editing" checkbox is unchecked, the block must be a partial block affecting
		// actions other than editing, and there must be no restrictions.
		if ( isset( $data['Editing'] ) && $data['Editing'] === false ) {
			$data['EditingRestriction'] = 'partial';
			$data['PageRestrictions'] = '';
			$data['NamespaceRestrictions'] = '';
		}
		return self::processForm( $data, $form->getContext() );
	}

	/**
	 * Do something exciting on successful processing of the form, most likely to show a
	 * confirmation message
	 */
	public function onSuccess() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'blockipsuccesssub' ) );
		$out->addWikiMsg( 'blockipsuccesstext', wfEscapeWikiText( $this->target ) );
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
		$user = User::newFromName( $search );
		if ( !$user ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return UserNamePrefixSearch::search( 'public', $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'users';
	}
}
