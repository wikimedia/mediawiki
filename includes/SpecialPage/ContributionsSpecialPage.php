<?php
/**
 * Implements Special:Contributions
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\SpecialPage;

use MediaWiki\Block\Block;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLMultiSelectField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\MainConfigNames;
use MediaWiki\Pager\ContribsPager;
use MediaWiki\Pager\ContributionsPager;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\PoolCounter\PoolCounterWorkViaCallback;
use MediaWiki\Specials\Contribute\ContributeFactory;
use MediaWiki\Status\Status;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\ExternalUserNames;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupAssignmentService;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserRigorOptions;
use OOUI\ButtonWidget;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Show user contributions in a paged list.
 *
 * This was refactored out from SpecialContributions to make it easier to
 * add new special pages with similar functionality and similar output.
 * Hooks formerly for SpecialContributions are run here to avoid needing
 * to duplicate hooks for each subclass.
 *
 * The subclass must provide an implementation of ::getPager, and may
 * disable syndication feed functionality by overriding ::providesFeeds.
 *
 * @stable to extend
 * @ingroup SpecialPage
 * @since 1.43 Refactored from SpecialContributions
 */
class ContributionsSpecialPage extends IncludableSpecialPage {

	use ContributionsRangeTrait;

	/** @var array */
	protected $opts = [];
	/** @var bool */
	protected $formErrors = false;

	protected IConnectionProvider $dbProvider;
	protected NamespaceInfo $namespaceInfo;
	protected PermissionManager $permissionManager;
	protected UserNameUtils $userNameUtils;
	protected UserNamePrefixSearch $userNamePrefixSearch;
	protected UserOptionsLookup $userOptionsLookup;
	protected UserFactory $userFactory;
	protected UserIdentityLookup $userIdentityLookup;
	protected DatabaseBlockStore $blockStore;
	protected UserGroupAssignmentService $userGroupAssignmentService;

	/**
	 * @param PermissionManager $permissionManager
	 * @param IConnectionProvider $dbProvider
	 * @param NamespaceInfo $namespaceInfo
	 * @param UserNameUtils $userNameUtils
	 * @param UserNamePrefixSearch $userNamePrefixSearch
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param UserFactory $userFactory
	 * @param UserIdentityLookup $userIdentityLookup
	 * @param DatabaseBlockStore $blockStore
	 * @param UserGroupAssignmentService $userGroupAssignmentService
	 * @param string $name
	 * @param string $restriction
	 */
	public function __construct(
		PermissionManager $permissionManager,
		IConnectionProvider $dbProvider,
		NamespaceInfo $namespaceInfo,
		UserNameUtils $userNameUtils,
		UserNamePrefixSearch $userNamePrefixSearch,
		UserOptionsLookup $userOptionsLookup,
		UserFactory $userFactory,
		UserIdentityLookup $userIdentityLookup,
		DatabaseBlockStore $blockStore,
		UserGroupAssignmentService $userGroupAssignmentService,
		$name,
		$restriction = ''
	) {
		parent::__construct( $name, $restriction );
		$this->permissionManager = $permissionManager;
		$this->dbProvider = $dbProvider;
		$this->namespaceInfo = $namespaceInfo;
		$this->userNameUtils = $userNameUtils;
		$this->userNamePrefixSearch = $userNamePrefixSearch;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->userFactory = $userFactory;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->blockStore = $blockStore;
		$this->userGroupAssignmentService = $userGroupAssignmentService;
	}

	/**
	 * @inheritDoc
	 */
	public function execute( $par ) {
		$request = $this->getRequest();
		$target = $request->getText( 'target' );

		if ( $target !== '' ) {
			// Update the value in the request so that code reading it
			// directly form the request gets the trimmed value (T378279).
			$request->setVal( 'target', trim( $target ) );
		}

		$target = trim( $par ?? $target );

		$this->setHeaders();
		$this->outputHeader();
		$this->checkPermissions();
		$out = $this->getOutput();
		// Modules required for viewing the list of contributions (also when included on other pages)
		$out->addModuleStyles( [
			'jquery.makeCollapsible.styles',
			'mediawiki.interface.helpers.styles',
			'mediawiki.special',
			'mediawiki.special.changeslist',
		] );
		$out->addBodyClasses( 'mw-special-ContributionsSpecialPage' );
		$out->addModules( [
			// Certain skins e.g. Minerva might have disabled this module.
			'mediawiki.page.ready'
		] );
		$this->addHelpLink( 'Help:User contributions' );

		$this->opts['deletedOnly'] = $request->getBool( 'deletedOnly' );

		// Explicitly check for empty string as this needs to account for
		// the rare case where the target parameter is '0' which is a valid
		// target but resolves to false in boolean context (T379515).
		if ( $target === '' ) {
			$out->addHTML( $this->getForm( $this->opts ) );

			return;
		}

		$user = $this->getUser();

		$this->opts['limit'] = $request->getInt( 'limit', $this->userOptionsLookup->getIntOption( $user, 'rclimit' ) );
		$this->opts['target'] = $target;
		$this->opts['topOnly'] = $request->getBool( 'topOnly' );
		$this->opts['newOnly'] = $request->getBool( 'newOnly' );
		$this->opts['hideMinor'] = $request->getBool( 'hideMinor' );

		$ns = $request->getVal( 'namespace', null );
		if ( $ns !== null && $ns !== '' && $ns !== 'all' ) {
			$this->opts['namespace'] = intval( $ns );
		} else {
			$this->opts['namespace'] = '';
		}

		// Backwards compatibility: Before using OOUI form the old HTML form had
		// fields for nsInvert and associated. These have now been replaced with the
		// wpFilters query string parameters. These are retained to keep old URIs working.
		$this->opts['associated'] = $request->getBool( 'associated' );
		$this->opts['nsInvert'] = (bool)$request->getVal( 'nsInvert' );
		$nsFilters = $request->getArray( 'wpfilters', null );
		if ( $nsFilters !== null ) {
			$this->opts['associated'] = in_array( 'associated', $nsFilters );
			$this->opts['nsInvert'] = in_array( 'nsInvert', $nsFilters );
		}

		$this->opts['tagfilter'] = array_filter( explode(
			'|',
			(string)$request->getVal( 'tagfilter' )
		), static function ( $el ) {
			return $el !== '';
		} );
		$this->opts['tagInvert'] = $request->getBool( 'tagInvert' );

		// Allows reverts to have the bot flag in recent changes. It is just here to
		// be passed in the form at the top of the page
		if ( $this->permissionManager->userHasRight( $user, 'markbotedits' ) && $request->getBool( 'bot' ) ) {
			$this->opts['bot'] = '1';
		}

		$this->opts['year'] = $request->getIntOrNull( 'year' );
		$this->opts['month'] = $request->getIntOrNull( 'month' );
		$this->opts['start'] = $request->getVal( 'start' );
		$this->opts['end'] = $request->getVal( 'end' );

		$notExternal = !ExternalUserNames::isExternal( $target );
		if ( $notExternal ) {
			$nt = Title::makeTitleSafe( NS_USER, $target );
			if ( !$nt ) {
				$out->addHTML( $this->getForm( $this->opts ) );
				return;
			}
			$target = $nt->getText();
			if ( IPUtils::isValidRange( $target ) ) {
				$target = IPUtils::sanitizeRange( $target );
			}
		}

		$userObj = $this->userFactory->newFromName( $target, UserRigorOptions::RIGOR_NONE );
		if ( !$userObj ) {
			$out->addHTML( $this->getForm( $this->opts ) );
			return;
		}
		// Add warning message if user doesn't exist
		$this->addContributionsSubWarning( $userObj );

		$out->addSubtitle( $this->contributionsSub( $userObj, $target ) );
		$out->setPageTitleMsg(
			$this->msg( $this->getResultsPageTitleMessageKey( $userObj ) )
				->rawParams( Html::element( 'bdi', [], $target ) )
				->params( $target )
		);

		// "+ New contribution" button
		$contributeEnabled = ContributeFactory::isEnabledOnCurrentSkin(
			$this->getSkin(),
			$this->getConfig()->get( MainConfigNames::SpecialContributeSkinsEnabled )
		);
		$isOwnContributionPage = $user->getName() === $target;
		if ( $contributeEnabled && $isOwnContributionPage ) {
			$out->enableOOUI();
			$out->addHTML( ( new ButtonWidget( [
				'id' => 'mw-specialcontributions-newcontribution',
				'href' => SpecialPage::getTitleFor( 'Contribute' )->getLinkURL(),
				'label' => $this->msg( 'sp-contributions-newcontribution' )->text(),
				'icon' => 'add',
				'framed' => true,
				'flags' => 'progressive',
			] ) )->toString() );
		}

		# For IP ranges, we want the contributionsSub, but not the skin-dependent
		# links under 'Tools', which may include irrelevant links like 'Logs'.
		if ( $notExternal && !IPUtils::isValidRange( $target ) &&
			( $this->userNameUtils->isIP( $target ) || $userObj->isRegistered() )
		) {
			// Don't add non-existent users, because hidden users
			// that we add here will be removed later to pretend
			// that they don't exist, and if users that actually don't
			// exist are added here and then not removed, it exposes
			// which users exist and are hidden vs. which actually don't
			// exist. But, do set the relevant user for single IPs.
			$this->getSkin()->setRelevantUser( $userObj );
		}

		$this->opts = ContribsPager::processDateFilter( $this->opts );

		if ( $this->opts['namespace'] !== '' && $this->opts['namespace'] < NS_MAIN ) {
			$this->getOutput()->wrapWikiMsg(
				"<div class=\"mw-negative-namespace-not-supported error\">\n\$1\n</div>",
				[ 'negative-namespace-not-supported' ]
			);
			$out->addHTML( $this->getForm( $this->opts ) );
			return;
		}

		if ( $this->providesFeeds() ) {
			$feedType = $request->getVal( 'feed' );

			$feedParams = [
				'action' => 'feedcontributions',
				'user' => $target,
			];
			if ( $this->opts['topOnly'] ) {
				$feedParams['toponly'] = true;
			}
			if ( $this->opts['newOnly'] ) {
				$feedParams['newonly'] = true;
			}
			if ( $this->opts['hideMinor'] ) {
				$feedParams['hideminor'] = true;
			}
			if ( $this->opts['deletedOnly'] ) {
				$feedParams['deletedonly'] = true;
			}

			if ( $this->opts['tagfilter'] !== [] ) {
				$feedParams['tagfilter'] = $this->opts['tagfilter'];
			}
			if ( $this->opts['namespace'] !== '' ) {
				$feedParams['namespace'] = $this->opts['namespace'];
			}
			// Don't use year and month for the feed URL, but pass them on if
			// we redirect to API (if $feedType is specified)
			if ( $feedType && isset( $this->opts['year'] ) ) {
				$feedParams['year'] = $this->opts['year'];
			}
			if ( $feedType && isset( $this->opts['month'] ) ) {
				$feedParams['month'] = $this->opts['month'];
			}

			if ( $feedType ) {
				// Maintain some level of backwards compatibility
				// If people request feeds using the old parameters, redirect to API
				$feedParams['feedformat'] = $feedType;
				$url = wfAppendQuery( wfScript( 'api' ), $feedParams );

				$out->redirect( $url, '301' );

				return;
			}

			// Add RSS/atom links
			$this->addFeedLinks( $feedParams );
		}

		if ( $this->getHookRunner()->onSpecialContributionsBeforeMainOutput(
			$notExternal ? $userObj->getId() : 0, $userObj, $this )
		) {
			$out->addHTML( $this->getForm( $this->opts ) );
			if ( $this->formErrors ) {
				return;
			}
			// We want a pure UserIdentity for imported actors, so the first letter
			// of them is in lowercase and queryable.
			$userIdentity = $notExternal ? $userObj :
				$this->userIdentityLookup->getUserIdentityByName( $target ) ?? $userObj;
			$pager = $this->getPager( $userIdentity );
			if (
				IPUtils::isValidRange( $target ) &&
				!$this->isQueryableRange( $target, $this->getConfig() )
			) {
				// Valid range, but outside CIDR limit.
				$limits = $this->getQueryableRangeLimit( $this->getConfig() );
				$limit = $limits[ IPUtils::isIPv4( $target ) ? 'IPv4' : 'IPv6' ];
				$out->addWikiMsg( 'sp-contributions-outofrange', $limit );
			} else {
				// @todo We just want a wiki ID here, not a "DB domain", but
				// current status of MediaWiki conflates the two. See T235955.
				$poolKey = $this->dbProvider->getReplicaDatabase()->getDomainID() . ':Special' . $this->mName . ':';
				if ( $this->getUser()->isAnon() ) {
					$poolKey .= 'a:' . $this->getUser()->getName();
				} else {
					$poolKey .= 'u:' . $this->getUser()->getId();
				}
				$work = new PoolCounterWorkViaCallback( 'Special' . $this->mName, $poolKey, [
					'doWork' => function () use ( $pager, $out ) {
						# Show a message about replica DB lag, if applicable
						$lag = $pager->getDatabase()->getSessionLagStatus()['lag'];
						if ( $lag > 0 ) {
							$out->showLagWarning( $lag );
						}

						$output = $pager->getBody();
						if ( !$this->including() ) {
							$output = $pager->getNavigationBar() .
								$output .
								$pager->getNavigationBar();
						}
						$out->addHTML( $output );
					},
					'error' => function () use ( $out ) {
						$msg = $this->getUser()->isAnon()
							? 'sp-contributions-concurrency-ip'
							: 'sp-contributions-concurrency-user';
						$out->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
						$out->addHTML(
							Html::errorBox(
								$out->msg( $msg )->parse()
							)
						);
					}
				] );
				$work->execute();
			}

			$out->setPreventClickjacking( $pager->getPreventClickjacking() );

			# Show the appropriate "footer" message - WHOIS tools, etc.
			if ( $this->isQueryableRange( $target, $this->getConfig() )
			) {
				$message = 'sp-contributions-footer-anon-range';
			} elseif ( IPUtils::isIPAddress( $target ) ) {
				$message = 'sp-contributions-footer-anon';
			} elseif ( $userObj->isTemp() ) {
				$message = 'sp-contributions-footer-temp';
				if ( $this->msg( $message )->isDisabled() ) {
					// As temp accounts and named accounts have similar properties,
					// fall back to the "registered" version of the footer
					$message = 'sp-contributions-footer';
				}
			} elseif ( $userObj->isAnon() ) {
				// No message for non-existing users
				$message = '';
			} elseif ( $userObj->isHidden() &&
				!$this->permissionManager->userHasRight( $this->getUser(), 'hideuser' )
			) {
				// User is registered, but make sure that the viewer can't see them, to avoid
				// having different behavior for missing and hidden users; see T120883
				$message = '';
			} else {
				// Not hidden, or hidden but the viewer can still see it
				$message = 'sp-contributions-footer';
			}

			if ( $message && !$this->including() && !$this->msg( $message, $target )->isDisabled() ) {
				$out->wrapWikiMsg(
					"<div class='mw-contributions-footer'>\n$1\n</div>",
					[ $message, $target ] );
			}
		}
	}

	/**
	 * Generates the subheading with links
	 * @param User $userObj User object for the target
	 * @param string $targetName This mostly the same as $userObj->getName() but
	 * normalization may make it differ. // T272225
	 * @return string Appropriately-escaped HTML to be output literally
	 */
	protected function contributionsSub( $userObj, $targetName ) {
		$out = $this->getOutput();
		$user = $this->getUserLink( $userObj );

		$links = '';
		if ( $this->shouldDisplayActionLinks( $userObj ) ) {
			$tools = $this->getUserLinks(
				$this,
				$userObj
			);
			$links = Html::openElement( 'span', [ 'class' => 'mw-changeslist-links' ] );
			foreach ( $tools as $tool ) {
				$links .= Html::rawElement( 'span', [], $tool ) . ' ';
			}
			$links = trim( $links ) . Html::closeElement( 'span' );

			// If the user is blocked, display the latest active block log entry
			if ( $this->shouldShowBlockLogExtract( $userObj ) ) {
				$blockLogBox = LogEventsList::getBlockLogWarningBox(
					$this->blockStore,
					$this->namespaceInfo,
					$this,
					$this->getLinkRenderer(),
					$userObj,
					null,
					static function ( array $data ): array {
						// Allow local styling overrides for different types of block
						$class = $data['sitewide'] ?
							'mw-contributions-blocked-notice' :
							'mw-contributions-blocked-notice-partial';
						return [
							'wrap' => Html::rawElement(
								'div',
								[ 'class' => $class ],
								'$1'
							)
						];
					}
				);
				if ( $blockLogBox !== null ) {
					$out->addHTML( $blockLogBox );
				}
			}
		}

		// First subheading. "For Username (talk | block log | logs | etc.)"
		$userName = $userObj->getName();
		$subHeadingsHtml = Html::rawElement( 'div', [ 'class' => 'mw-contributions-user-tools' ],
			$this->msg( 'contributions-subtitle' )->rawParams(
				Html::rawElement( 'bdi', [], $user )
			)->params( $userName )
			. ' ' . $links
		);

		// Second subheading. "A user with 37,208 edits. Account created on 2008-09-17."
		if ( $this->shouldDisplayAccountInformation( $userObj ) ) {
			$editCount = $userObj->getEditCount();
			$userInfo = $this->msg( 'contributions-edit-count' )
				->params( $userName )
				->numParams( $editCount )
				->escaped();

			$accountCreationDate = $userObj->getRegistration();
			if ( $accountCreationDate ) {
				$date = $this->getLanguage()->date( $accountCreationDate, true );
				$userInfo .= $this->msg( 'word-separator' )
					->escaped();
				$userInfo .= $this->msg( 'contributions-account-creation-date' )
					->plaintextParams( $date )
					->escaped();
			}

			$subHeadingsHtml .= Html::rawElement(
				'div',
				[ 'class' => 'mw-contributions-editor-info' ],
				$userInfo
			);
		}

		return $subHeadingsHtml;
	}

	/**
	 * Generate and append the "user not registered" warning message if the target does not exist and is a username
	 *
	 * @param User $userObj User object for the target
	 */
	protected function addContributionsSubWarning( $userObj ) {
		$out = $this->getOutput();
		$isAnon = $userObj->isAnon();

		// Show a warning message that the user being searched for doesn't exist.
		// UserNameUtils::isIP returns true for IP address and usemod IPs like '123.123.123.xxx',
		// but returns false for IP ranges. We don't want to suggest either of these are
		// valid usernames which we would with the 'contributions-userdoesnotexist' message.
		if (
			$isAnon &&
			!$this->userNameUtils->isIP( $userObj->getName() )
			&& !IPUtils::isValidRange( $userObj->getName() )
		) {
			$out->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
			$out->addHTML( Html::warningBox(
				$out->msg( 'contributions-userdoesnotexist',
					wfEscapeWikiText( $userObj->getName() ) )->parse(),
				'mw-userpage-userdoesnotexist'
			) );
			if ( !$this->including() ) {
				$out->setStatusCode( 404 );
			}
		}
	}

	/**
	 * Determine whether or not to show the user action links
	 *
	 * @param User $userObj User object for the target
	 * @return bool
	 */
	protected function shouldDisplayActionLinks( User $userObj ): bool {
		// T211910. Don't show action links if a range is outside block limit
		$showForIp = $this->isValidIPOrQueryableRange( $userObj->getName(), $this->getConfig() );

		$talk = $userObj->getTalkPage();

		// T276306. if the user is hidden and the viewer cannot see hidden, pretend that it does not exist
		$registeredAndVisible = $userObj->isRegistered() && ( !$userObj->isHidden()
				|| $this->permissionManager->userHasRight( $this->getUser(), 'hideuser' ) );

		return $talk && ( $registeredAndVisible || $showForIp );
	}

	/**
	 * Determine whether or not to show account information
	 *
	 * @param User $userObj User object for the target
	 * @return bool
	 */
	protected function shouldDisplayAccountInformation( User $userObj ): bool {
		$talk = $userObj->getTalkPage();

		// T276306. if the user is hidden and the viewer cannot see hidden, pretend that it does not exist
		$registeredAndVisible = $userObj->isRegistered() && (
			!$userObj->isHidden() ||
			$this->permissionManager->userHasRight( $this->getUser(), 'hideuser' )
		);

		return $talk && $registeredAndVisible;
	}

	/**
	 * Get a link to the user if they exist
	 *
	 * @param User $userObj Target user object
	 * @return string
	 */
	protected function getUserLink( User $userObj ): string {
		if (
			$userObj->isAnon() ||
			( $userObj->isHidden() && !$this->getAuthority()->isAllowed( 'hideuser' ) )
		) {
			return htmlspecialchars( $userObj->getName() );
		} else {
			return $this->getLinkRenderer()->makeLink( $userObj->getUserPage(), $userObj->getName() );
		}
	}

	/**
	 * Links to different places.
	 *
	 * @note This function is also called in DeletedContributionsPage
	 * @param SpecialPage $sp SpecialPage instance, for context
	 * @param User $target Target user object
	 * @return array
	 */
	protected function getUserLinks(
		SpecialPage $sp,
		User $target
	) {
		$id = $target->getId();
		$username = $target->getName();
		$userpage = $target->getUserPage();
		$talkpage = $target->getTalkPage();
		$isIP = IPUtils::isValid( $username );
		$isRange = IPUtils::isValidRange( $username );

		$linkRenderer = $sp->getLinkRenderer();

		$tools = [];
		# No talk pages for IP ranges.
		if ( !$isRange ) {
			$tools['user-talk'] = $linkRenderer->makeLink(
				$talkpage,
				$sp->msg( 'sp-contributions-talk' )->text(),
				[ 'class' => 'mw-contributions-link-talk' ]
			);
		}

		# Block links
		if ( $this->permissionManager->userHasRight( $sp->getUser(), 'block' ) ) {
			if ( $target->getBlock() && $target->getBlock()->getType() != Block::TYPE_AUTO ) {
				if ( $this->getConfig()->get( MainConfigNames::UseCodexSpecialBlock ) ) {
					$tools['block'] = $linkRenderer->makeKnownLink( # Manage block link
						SpecialPage::getTitleFor( 'Block', $username ),
						$sp->msg( 'manage-blocklink' )->text(),
						[ 'class' => 'mw-contributions-link-manage-block' ]
					);
				} else {
					$tools['block'] = $linkRenderer->makeKnownLink( # Change block link
						SpecialPage::getTitleFor( 'Block', $username ),
						$sp->msg( 'change-blocklink' )->text(),
						[ 'class' => 'mw-contributions-link-change-block' ]
					);
					$tools['unblock'] = $linkRenderer->makeKnownLink( # Unblock link
						SpecialPage::getTitleFor( 'Unblock', $username ),
						$sp->msg( 'unblocklink' )->text(),
						[ 'class' => 'mw-contributions-link-unblock' ]
					);
				}
			} else { # User is not blocked
				$tools['block'] = $linkRenderer->makeKnownLink( # Block link
					SpecialPage::getTitleFor( 'Block', $username ),
					$sp->msg( 'blocklink' )->text(),
					[ 'class' => 'mw-contributions-link-block' ]
				);
			}
		}

		# Block log link
		$tools['log-block'] = $linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Log', 'block' ),
			$sp->msg( 'sp-contributions-blocklog' )->text(),
			[ 'class' => 'mw-contributions-link-block-log' ],
			[ 'page' => $userpage->getPrefixedText() ]
		);

		# Suppression log link (T61120)
		if ( $this->permissionManager->userHasRight( $sp->getUser(), 'suppressionlog' ) ) {
			$tools['log-suppression'] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Log', 'suppress' ),
				$sp->msg( 'sp-contributions-suppresslog', $username )->text(),
				[ 'class' => 'mw-contributions-link-suppress-log' ],
				[ 'offender' => $username ]
			);
		}

		# Don't show some links for IP ranges
		if ( !$isRange ) {
			# Uploads: hide if IPs cannot upload (T220674)
			if ( !$isIP || $this->permissionManager->userHasRight( $target, 'upload' ) ) {
				$tools['uploads'] = $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'Listfiles', $username ),
					$sp->msg( 'sp-contributions-uploads' )->text(),
					[ 'class' => 'mw-contributions-link-uploads' ]
				);
			}

			# Other logs link
			# Todo: T146628
			$tools['logs'] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Log', $username ),
				$sp->msg( 'sp-contributions-logs' )->text(),
				[ 'class' => 'mw-contributions-link-logs' ]
			);

			# Add link to deleted user contributions for privileged users
			# Todo: T183457
			if ( $this->permissionManager->userHasRight( $sp->getUser(), 'deletedhistory' ) ) {
				$tools['deletedcontribs'] = $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'DeletedContributions', $username ),
					$sp->msg( 'sp-contributions-deleted', $username )->text(),
					[ 'class' => 'mw-contributions-link-deleted-contribs' ]
				);
			}
		}

		# (T373988) Don't show some links for temporary accounts
		if ( !$target->isTemp() ) {
			# Add a link to change user rights for privileged users
			if ( $this->userGroupAssignmentService->userCanChangeRights( $sp->getUser(), $target ) ) {
				$tools['userrights'] = $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'Userrights', $username ),
					$sp->msg( 'sp-contributions-userrights', $username )->text(),
					[ 'class' => 'mw-contributions-link-user-rights' ]
				);
			}

			# Add a link to rename the user
			if ( $id && $this->permissionManager->userHasRight( $sp->getUser(), 'renameuser' ) ) {
				$tools['renameuser'] = $sp->getLinkRenderer()->makeKnownLink(
					SpecialPage::getTitleFor( 'Renameuser' ),
					$sp->msg( 'renameuser-linkoncontribs', $userpage->getText() )->text(),
					[ 'title' => $sp->msg( 'renameuser-linkoncontribs-text', $userpage->getText() )->parse() ],
					[ 'oldusername' => $userpage->getText() ]
				);
			}
		}

		$this->getHookRunner()->onContributionsToolLinks( $id, $userpage, $tools, $sp );

		return $tools;
	}

	/**
	 * Get the target field for the form
	 *
	 * @param string $target
	 * @return array
	 */
	protected function getTargetField( $target ) {
		return [
			'type' => 'user',
			'default' => str_replace( '_', ' ', $target ),
			'label' => $this->msg( 'sp-contributions-username' )->text(),
			'name' => 'target',
			'id' => 'mw-target-user-or-ip',
			'size' => 40,
			'autofocus' => $target === '',
			'section' => 'contribs-top',
			'ipallowed' => true,
			'usemodwiki-ipallowed' => true,
			'iprange' => true,
			'external' => true,
			'required' => true,
		];
	}

	/**
	 * Generates the namespace selector form with hidden attributes.
	 * @param array $pagerOptions with keys contribs, user, deletedOnly, limit, target, topOnly,
	 *  newOnly, hideMinor, namespace, associated, nsInvert, tagfilter, tagInvert, year, start, end
	 * @return string HTML fragment
	 */
	protected function getForm( array $pagerOptions ) {
		if ( $this->including() ) {
			// Do not show a form when special page is included in wikitext
			return '';
		}

		// Modules required only for the form
		$this->getOutput()->addModules( [
			'mediawiki.special.contributions',
		] );
		$this->getOutput()->enableOOUI();
		$fields = [];

		# Add hidden params for tracking except for parameters in $skipParameters
		$skipParameters = [
			'namespace',
			'nsInvert',
			'deletedOnly',
			'target',
			'year',
			'month',
			'start',
			'end',
			'topOnly',
			'newOnly',
			'hideMinor',
			'associated',
			'tagfilter',
			'tagInvert',
			'title',
		];

		foreach ( $pagerOptions as $name => $value ) {
			if ( in_array( $name, $skipParameters ) ) {
				continue;
			}

			$fields[$name] = [
				'name' => $name,
				'type' => 'hidden',
				'default' => $value,
			];
		}

		$target = $pagerOptions['target'] ?? '';
		$fields['target'] = $this->getTargetField( $target );

		$ns = $pagerOptions['namespace'] ?? 'all';
		$fields['namespace'] = [
			'type' => 'namespaceselect',
			'label' => $this->msg( 'namespace' )->text(),
			'name' => 'namespace',
			'cssclass' => 'namespaceselector',
			'default' => $ns,
			'id' => 'namespace',
			'section' => 'contribs-top',
		];
		$fields['nsFilters'] = [
			'class' => HTMLMultiSelectField::class,
			'label' => '',
			'name' => 'wpfilters',
			'flatlist' => true,
			// Only shown when namespaces are selected.
			'hide-if' => [ '===', 'namespace', 'all' ],
			'options-messages' => [
				'invert' => 'nsInvert',
				'namespace_association' => 'associated',
			],
			'section' => 'contribs-top',
		];
		$fields['tagfilter'] = [
			'type' => 'tagfilter',
			'cssclass' => 'mw-tagfilter-input',
			'id' => 'tagfilter',
			'label-message' => [ 'tag-filter', 'parse' ],
			'name' => 'tagfilter',
			'size' => 20,
			'section' => 'contribs-top',
		];
		$fields['tagInvert'] = [
			'type' => 'check',
			'id' => 'tagInvert',
			'label-message' => 'invert',
			'name' => 'tagInvert',
			'hide-if' => [ '===', 'tagfilter', '' ],
			'section' => 'contribs-top',
		];

		if ( $this->permissionManager->userHasRight( $this->getUser(), 'deletedhistory' ) ) {
			$fields['deletedOnly'] = [
				'type' => 'check',
				'id' => 'mw-show-deleted-only',
				'label' => $this->msg( 'history-show-deleted' )->text(),
				'name' => 'deletedOnly',
				'section' => 'contribs-top',
			];
		}

		if ( !$this->isArchive() ) {
			$fields['topOnly'] = [
				'type' => 'check',
				'id' => 'mw-show-top-only',
				'label' => $this->msg( 'sp-contributions-toponly' )->text(),
				'name' => 'topOnly',
				'section' => 'contribs-top',
			];
		}

		$fields['newOnly'] = [
			'type' => 'check',
			'id' => 'mw-show-new-only',
			'label' => $this->msg( 'sp-contributions-newonly' )->text(),
			'name' => 'newOnly',
			'section' => 'contribs-top',
		];
		$fields['hideMinor'] = [
			'type' => 'check',
			'cssclass' => 'mw-hide-minor-edits',
			'id' => 'mw-show-new-only',
			'label' => $this->msg( 'sp-contributions-hideminor' )->text(),
			'name' => 'hideMinor',
			'section' => 'contribs-top',
		];

		// Allow additions at this point to the filters.
		$rawFilters = [];
		$this->getHookRunner()->onSpecialContributions__getForm__filters(
			$this, $rawFilters );
		foreach ( $rawFilters as $filter ) {
			// Backwards compatibility support for previous hook function signature.
			if ( is_string( $filter ) ) {
				$fields[] = [
					'type' => 'info',
					'default' => $filter,
					'raw' => true,
					'section' => 'contribs-top',
				];
				wfDeprecatedMsg(
					'A SpecialContributions::getForm::filters hook handler returned ' .
					'an array of strings, this is deprecated since MediaWiki 1.33',
					'1.33', false, false
				);
			} else {
				// Preferred append method.
				$fields[] = $filter;
			}
		}

		$fields['start'] = [
			'type' => 'date',
			'default' => '',
			'id' => 'mw-date-start',
			'label' => $this->msg( 'date-range-from' )->text(),
			'name' => 'start',
			'section' => 'contribs-date',
		];
		$fields['end'] = [
			'type' => 'date',
			'default' => '',
			'id' => 'mw-date-end',
			'label' => $this->msg( 'date-range-to' )->text(),
			'name' => 'end',
			'section' => 'contribs-date',
		];

		// Allow children classes to modify field options before generating HTML
		$this->modifyFields( $fields );

		$htmlForm = HTMLForm::factory( 'ooui', $fields, $this->getContext() );
		$htmlForm
			->setMethod( 'get' )
			->setTitle( $this->getPageTitle() )
			// When offset is defined, the user is paging through results
			// so we hide the form by default to allow users to focus on browsing
			// rather than defining search parameters
			->setCollapsibleOptions(
				( $pagerOptions['target'] ?? null ) ||
				( $pagerOptions['start'] ?? null ) ||
				( $pagerOptions['end'] ?? null )
			)
			->setAction( wfScript() )
			->setSubmitTextMsg( 'sp-contributions-submit' )
			->setWrapperLegendMsg( $this->getFormWrapperLegendMessageKey() );

		$htmlForm->prepareForm();

		// Submission is handled elsewhere, but do this to check for and display errors
		$htmlForm->setSubmitCallback( static function () {
			return true;
		} );
		$result = $htmlForm->tryAuthorizedSubmit();
		if ( !( $result === true || ( $result instanceof Status && $result->isGood() ) ) ) {
			// Uncollapse if there are errors
			$htmlForm->setCollapsibleOptions( false );
			$this->formErrors = true;
		}

		return $htmlForm->getHTML( $result );
	}

	/**
	 * Allow children classes to call this function and make modifications to the
	 * field options before they're used to create the form in getForm.
	 *
	 * @since 1.44
	 * @param array &$fields
	 */
	protected function modifyFields( &$fields ) {
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
	 * @return bool This SpecialPage provides syndication feeds.
	 */
	protected function providesFeeds() {
		return true;
	}

	/**
	 * Define whether this page shows existing revisions (from the revision table) or
	 * revisions of deleted pages (from the archive table).
	 *
	 * @return bool This page shows existing revisions
	 */
	protected function isArchive() {
		return false;
	}

	/**
	 * @param UserIdentity $targetUser The normalized target user identity
	 * @return ContributionsPager
	 */
	protected function getPager( $targetUser ) {
		// TODO: This class and the classes it extends should be abstract, and this
		// method should be abstract.
		throw new \LogicException( __METHOD__ . " must be overridden" );
	}

	/**
	 * @inheritDoc
	 */
	protected function getGroupName() {
		return 'users';
	}

	/**
	 * @return string Message key for the fieldset wrapping the form
	 */
	protected function getFormWrapperLegendMessageKey() {
		return 'sp-contributions-search';
	}

	/**
	 * @param UserIdentity $target The target of the search that produced the results page
	 * @return string Message key for the results page title
	 */
	protected function getResultsPageTitleMessageKey( UserIdentity $target ) {
		return 'contributions-title';
	}

	/**
	 * Whether the block log extract should be shown on the special page. This is public to allow extensions which
	 * add block log entries to skip adding them when this returns false.
	 *
	 * @since 1.43
	 * @param UserIdentity $target The target of the search that produced the results page
	 * @return bool Whether the block log extract should be shown if the target is blocked.
	 */
	public function shouldShowBlockLogExtract( UserIdentity $target ): bool {
		return !$this->including();
	}
}
