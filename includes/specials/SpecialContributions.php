<?php
/**
 * Implements Special:Contributions
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
use MediaWiki\MediaWikiServices;
use Wikimedia\IPUtils;

/**
 * Special:Contributions, show user contributions in a paged list
 *
 * @ingroup SpecialPage
 */
class SpecialContributions extends IncludableSpecialPage {
	protected $opts;

	public function __construct() {
		parent::__construct( 'Contributions' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		// Modules required for viewing the list of contributions (also when included on other pages)
		$out->addModuleStyles( [
			'jquery.makeCollapsible.styles',
			'mediawiki.interface.helpers.styles',
			'mediawiki.special',
			'mediawiki.special.changeslist',
		] );
		$out->addModules( [
			'mediawiki.special.recentchanges',
			// Certain skins e.g. Minerva might have disabled this module.
			'mediawiki.page.ready'
		] );
		$this->addHelpLink( 'Help:User contributions' );

		$this->opts = [];
		$request = $this->getRequest();

		$target = $par ?? $request->getVal( 'target' );

		$this->opts['deletedOnly'] = $request->getBool( 'deletedOnly' );

		if ( !strlen( $target ) ) {
			if ( !$this->including() ) {
				$out->addHTML( $this->getForm( $this->opts ) );
			}

			return;
		}

		$user = $this->getUser();

		$this->opts['limit'] = $request->getInt( 'limit', $user->getOption( 'rclimit' ) );
		$this->opts['target'] = $target;
		$this->opts['topOnly'] = $request->getBool( 'topOnly' );
		$this->opts['newOnly'] = $request->getBool( 'newOnly' );
		$this->opts['hideMinor'] = $request->getBool( 'hideMinor' );

		$id = 0;
		if ( ExternalUserNames::isExternal( $target ) ) {
			$userObj = User::newFromName( $target, false );
			if ( !$userObj ) {
				$out->addHTML( $this->getForm( $this->opts ) );
				return;
			}

			$out->addSubtitle( $this->contributionsSub( $userObj ) );
			$out->setHTMLTitle( $this->msg(
				'pagetitle',
				$this->msg( 'contributions-title', $target )->plain()
			)->inContentLanguage() );
		} else {
			$nt = Title::makeTitleSafe( NS_USER, $target );
			if ( !$nt ) {
				$out->addHTML( $this->getForm( $this->opts ) );
				return;
			}
			$userObj = User::newFromName( $nt->getText(), false );
			if ( !$userObj ) {
				$out->addHTML( $this->getForm( $this->opts ) );
				return;
			}
			$id = $userObj->getId();

			$target = $nt->getText();
			$out->addSubtitle( $this->contributionsSub( $userObj ) );
			$out->setHTMLTitle( $this->msg(
				'pagetitle',
				$this->msg( 'contributions-title', $target )->plain()
			)->inContentLanguage() );

			# For IP ranges, we want the contributionsSub, but not the skin-dependent
			# links under 'Tools', which may include irrelevant links like 'Logs'.
			if ( !IPUtils::isValidRange( $target ) &&
				( User::isIP( $target ) || $userObj->isRegistered() )
			) {
				// Don't add non-existent users, because hidden users
				// that we add here will be removed later to pretend
				// that they don't exist, and if users that actually don't
				// exist are added here and then not removed, it exposes
				// which users exist and are hidden vs. which actually don't
				// exist. But, do set the relevant user for single IPs.
				$this->getSkin()->setRelevantUser( $userObj );
			}
		}

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

		$this->opts['tagfilter'] = (string)$request->getVal( 'tagfilter' );

		// Allows reverts to have the bot flag in recent changes. It is just here to
		// be passed in the form at the top of the page
		if ( MediaWikiServices::getInstance()
				 ->getPermissionManager()
				 ->userHasRight( $user, 'markbotedits' ) && $request->getBool( 'bot' )
		) {
			$this->opts['bot'] = '1';
		}

		$skip = $request->getText( 'offset' ) || $request->getText( 'dir' ) == 'prev';
		# Offset overrides year/month selection
		if ( !$skip ) {
			$this->opts['year'] = $request->getVal( 'year' );
			$this->opts['month'] = $request->getVal( 'month' );

			$this->opts['start'] = $request->getVal( 'start' );
			$this->opts['end'] = $request->getVal( 'end' );
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
		if ( $this->opts['tagfilter'] !== '' ) {
			$feedParams['tagfilter'] = $this->opts['tagfilter'];
		}
		if ( $this->opts['namespace'] !== '' ) {
			$feedParams['namespace'] = $this->opts['namespace'];
		}
		// Don't use year and month for the feed URL, but pass them on if
		// we redirect to API (if $feedType is specified)
		if ( $feedType && $this->opts['year'] !== null ) {
			$feedParams['year'] = $this->opts['year'];
		}
		if ( $feedType && $this->opts['month'] !== null ) {
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

		if ( $this->getHookRunner()->onSpecialContributionsBeforeMainOutput(
			$id, $userObj, $this )
		) {
			$pager = new ContribsPager( $this->getContext(), [
				'target' => $target,
				'namespace' => $this->opts['namespace'],
				'tagfilter' => $this->opts['tagfilter'],
				'start' => $this->opts['start'],
				'end' => $this->opts['end'],
				'deletedOnly' => $this->opts['deletedOnly'],
				'topOnly' => $this->opts['topOnly'],
				'newOnly' => $this->opts['newOnly'],
				'hideMinor' => $this->opts['hideMinor'],
				'nsInvert' => $this->opts['nsInvert'],
				'associated' => $this->opts['associated'],
			], $this->getLinkRenderer() );
			if ( !$this->including() ) {
				$out->addHTML( $this->getForm( $this->opts ) );
			}

			if ( IPUtils::isValidRange( $target ) && !$pager->isQueryableRange( $target ) ) {
				// Valid range, but outside CIDR limit.
				$limits = $this->getConfig()->get( 'RangeContributionsCIDRLimit' );
				$limit = $limits[ IPUtils::isIPv4( $target ) ? 'IPv4' : 'IPv6' ];
				$out->addWikiMsg( 'sp-contributions-outofrange', $limit );
			} elseif ( !$pager->getNumRows() ) {
				$out->addWikiMsg( 'nocontribs', $target );
			} else {
				// @todo We just want a wiki ID here, not a "DB domain", but
				// current status of MediaWiki conflates the two. See T235955.
				$poolKey = WikiMap::getCurrentWikiDbDomain() . ':SpecialContributions:';
				if ( $this->getUser()->isAnon() ) {
					$poolKey .= 'a:' . $this->getUser()->getName();
				} else {
					$poolKey .= 'u:' . $this->getUser()->getId();
				}
				$work = new PoolCounterWorkViaCallback( 'SpecialContributions', $poolKey, [
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
						$out->wrapWikiMsg( "<div class='errorbox'>\n$1\n</div>", $msg );
					}
				] );
				$work->execute();
			}

			$out->preventClickjacking( $pager->getPreventClickjacking() );

			# Show the appropriate "footer" message - WHOIS tools, etc.
			if ( IPUtils::isValidRange( $target ) ) {
				$message = 'sp-contributions-footer-anon-range';
			} elseif ( IPUtils::isIPAddress( $target ) ) {
				$message = 'sp-contributions-footer-anon';
			} elseif ( $userObj->isAnon() ) {
				// No message for non-existing users
				$message = '';
			} elseif ( $userObj->isHidden() &&
				!MediaWikiServices::getInstance()->getPermissionManager()
					->userHasRight( $this->getUser(), 'hideuser' )
			) {
				// User is registered, but make sure that the viewer can see them, to avoid
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
	 * @return string Appropriately-escaped HTML to be output literally
	 * @todo FIXME: Almost the same as getSubTitle in SpecialDeletedContributions.php.
	 * Could be combined.
	 */
	protected function contributionsSub( $userObj ) {
		$isAnon = $userObj->isAnon();
		if ( !$isAnon && $userObj->isHidden() &&
			!MediaWikiServices::getInstance()->getPermissionManager()
				->userHasRight( $this->getUser(), 'hideuser' )
		) {
			// T120883 if the user is hidden and the viewer cannot see hidden
			// users, pretend like it does not exist at all.
			$isAnon = true;
		}

		if ( $isAnon ) {
			// Show a warning message that the user being searched for doesn't exist.
			// User::isIP returns true for IP address and usemod IPs like '123.123.123.xxx',
			// but returns false for IP ranges. We don't want to suggest either of these are
			// valid usernames which we would with the 'contributions-userdoesnotexist' message.
			if ( !User::isIP( $userObj->getName() ) && !$userObj->isIPRange() ) {
				$this->getOutput()->wrapWikiMsg(
					"<div class=\"mw-userpage-userdoesnotexist error\">\n\$1\n</div>",
					[
						'contributions-userdoesnotexist',
						wfEscapeWikiText( $userObj->getName() ),
					]
				);
				if ( !$this->including() ) {
					$this->getOutput()->setStatusCode( 404 );
				}
			}
			$user = htmlspecialchars( $userObj->getName() );
		} else {
			$user = $this->getLinkRenderer()->makeLink( $userObj->getUserPage(), $userObj->getName() );
		}
		$nt = $userObj->getUserPage();
		$talk = $userObj->getTalkPage();
		$links = '';
		if ( $talk ) {
			$tools = self::getUserLinks( $this, $userObj );
			$links = Html::openElement( 'span', [ 'class' => 'mw-changeslist-links' ] );
			foreach ( $tools as $tool ) {
				$links .= Html::rawElement( 'span', [], $tool ) . ' ';
			}
			$links = trim( $links ) . Html::closeElement( 'span' );

			// Show a note if the user is blocked and display the last block log entry.
			// Do not expose the autoblocks, since that may lead to a leak of accounts' IPs,
			// and also this will display a totally irrelevant log entry as a current block.
			if ( !$this->including() ) {
				// For IP ranges you must give DatabaseBlock::newFromTarget the CIDR string
				// and not a user object.
				if ( $userObj->isIPRange() ) {
					$block = DatabaseBlock::newFromTarget( $userObj->getName(), $userObj->getName() );
				} else {
					$block = DatabaseBlock::newFromTarget( $userObj, $userObj );
				}

				if ( $block !== null && $block->getType() != DatabaseBlock::TYPE_AUTO ) {
					if ( $block->getType() == DatabaseBlock::TYPE_RANGE ) {
						$nt = MediaWikiServices::getInstance()->getNamespaceInfo()->
							getCanonicalName( NS_USER ) . ':' . $block->getTarget();
					}

					$out = $this->getOutput(); // showLogExtract() wants first parameter by reference
					if ( $userObj->isAnon() ) {
						$msgKey = $block->isSitewide() ?
							'sp-contributions-blocked-notice-anon' :
							'sp-contributions-blocked-notice-anon-partial';
					} else {
						$msgKey = $block->isSitewide() ?
							'sp-contributions-blocked-notice' :
							'sp-contributions-blocked-notice-partial';
					}
					// Allow local styling overrides for different types of block
					$class = $block->isSitewide() ?
						'mw-contributions-blocked-notice' :
						'mw-contributions-blocked-notice-partial';
					LogEventsList::showLogExtract(
						$out,
						'block',
						$nt,
						'',
						[
							'lim' => 1,
							'showIfEmpty' => false,
							'msgKey' => [
								$msgKey,
								$userObj->getName() # Support GENDER in 'sp-contributions-blocked-notice'
							],
							'offset' => '', # don't use WebRequest parameter offset
							'wrap' => Html::rawElement(
								'div',
								[ 'class' => $class ],
								'$1'
							),
						]
					);
				}
			}
		}

		return Html::rawElement( 'div', [ 'class' => 'mw-contributions-user-tools' ],
			$this->msg( 'contributions-subtitle' )->rawParams( $user )->params( $userObj->getName() )
			. ' ' . $links
		);
	}

	/**
	 * Links to different places.
	 *
	 * @note This function is also called in DeletedContributionsPage
	 * @param SpecialPage $sp SpecialPage instance, for context
	 * @param User $target Target user object
	 * @return array
	 */
	public static function getUserLinks( SpecialPage $sp, User $target ) {
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
				$sp->msg( 'sp-contributions-talk' )->text()
			);
		}

		# Block / Change block / Unblock links
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		if ( $permissionManager->userHasRight( $sp->getUser(), 'block' ) ) {
			if ( $target->getBlock() && $target->getBlock()->getType() != DatabaseBlock::TYPE_AUTO ) {
				$tools['block'] = $linkRenderer->makeKnownLink( # Change block link
					SpecialPage::getTitleFor( 'Block', $username ),
					$sp->msg( 'change-blocklink' )->text()
				);
				$tools['unblock'] = $linkRenderer->makeKnownLink( # Unblock link
					SpecialPage::getTitleFor( 'Unblock', $username ),
					$sp->msg( 'unblocklink' )->text()
				);
			} else { # User is not blocked
				$tools['block'] = $linkRenderer->makeKnownLink( # Block link
					SpecialPage::getTitleFor( 'Block', $username ),
					$sp->msg( 'blocklink' )->text()
				);
			}
		}

		# Block log link
		$tools['log-block'] = $linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Log', 'block' ),
			$sp->msg( 'sp-contributions-blocklog' )->text(),
			[],
			[ 'page' => $userpage->getPrefixedText() ]
		);

		# Suppression log link (T61120)
		if ( $permissionManager->userHasRight( $sp->getUser(), 'suppressionlog' ) ) {
			$tools['log-suppression'] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Log', 'suppress' ),
				$sp->msg( 'sp-contributions-suppresslog', $username )->text(),
				[],
				[ 'offender' => $username ]
			);
		}

		# Don't show some links for IP ranges
		if ( !$isRange ) {
			# Uploads: hide if IPs cannot upload (T220674)
			if ( !$isIP || $permissionManager->userHasRight( $target, 'upload' ) ) {
				$tools['uploads'] = $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'Listfiles', $username ),
					$sp->msg( 'sp-contributions-uploads' )->text()
				);
			}

			# Other logs link
			# Todo: T146628
			$tools['logs'] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Log', $username ),
				$sp->msg( 'sp-contributions-logs' )->text()
			);

			# Add link to deleted user contributions for priviledged users
			# Todo: T183457
			if ( $permissionManager->userHasRight( $sp->getUser(), 'deletedhistory' ) ) {
				$tools['deletedcontribs'] = $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'DeletedContributions', $username ),
					$sp->msg( 'sp-contributions-deleted', $username )->text()
				);
			}
		}

		# Add a link to change user rights for privileged users
		$userrightsPage = new UserrightsPage();
		$userrightsPage->setContext( $sp->getContext() );
		if ( $userrightsPage->userCanChangeRights( $target ) ) {
			$tools['userrights'] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Userrights', $username ),
				$sp->msg( 'sp-contributions-userrights', $username )->text()
			);
		}

		Hooks::runner()->onContributionsToolLinks( $id, $userpage, $tools, $sp );

		return $tools;
	}

	/**
	 * Generates the namespace selector form with hidden attributes.
	 * @param array $pagerOptions with keys contribs, user, deletedOnly, limit, target, topOnly,
	 *  newOnly, hideMinor, namespace, associated, nsInvert, tagfilter, year, start, end
	 * @return string HTML fragment
	 */
	protected function getForm( array $pagerOptions ) {
		$this->opts['title'] = $this->getPageTitle()->getPrefixedText();
		// Modules required only for the form
		$this->getOutput()->addModules( [
			'mediawiki.special.contributions',
		] );
		$this->getOutput()->addModuleStyles( 'mediawiki.widgets.DateInputWidget.styles' );
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
			'tagfilter'
		];

		foreach ( $this->opts as $name => $value ) {
			if ( in_array( $name, $skipParameters ) ) {
				continue;
			}

			$fields[$name] = [
				'name' => $name,
				'type' => 'hidden',
				'default' => $value,
			];
		}

		$target = $this->opts['target'] ?? null;
		$fields['target'] = [
			'type' => 'user',
			'default' => $target ?
				str_replace( '_', ' ', $target ) : '' ,
			'label' => $this->msg( 'sp-contributions-username' )->text(),
			'name' => 'target',
			'id' => 'mw-target-user-or-ip',
			'size' => 40,
			'autofocus' => !$target,
			'section' => 'contribs-top',
		];

		$ns = $this->opts['namespace'] ?? null;
		$fields['namespace'] = [
			'type' => 'namespaceselect',
			'label' => $this->msg( 'namespace' )->text(),
			'name' => 'namespace',
			'cssclass' => 'namespaceselector',
			'default' => $ns,
			'id' => 'namespace',
			'section' => 'contribs-top',
		];
		$request = $this->getRequest();
		$nsFilters = $request->getArray( 'wpfilters' );
		$fields['nsFilters'] = [
			'class' => 'HTMLMultiSelectField',
			'label' => '',
			'name' => 'wpfilters',
			'flatlist' => true,
			// Only shown when namespaces are selected.
			'cssclass' => $ns === '' ?
				'contribs-ns-filters mw-input-with-label mw-input-hidden' :
				'contribs-ns-filters mw-input-with-label',
			// `contribs-ns-filters` class allows these fields to be toggled on/off by JavaScript.
			// See resources/src/mediawiki.special.recentchanges.js
			'infusable' => true,
			'options-messages' => [
				'invert' => 'nsInvert',
				'namespace_association' => 'associated',
			],
			'default' => $nsFilters,
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

		if ( MediaWikiServices::getInstance()
			->getPermissionManager()
			->userHasRight( $this->getUser(), 'deletedhistory' )
		) {
			$fields['deletedOnly'] = [
				'type' => 'check',
				'id' => 'mw-show-deleted-only',
				'label' => $this->msg( 'history-show-deleted' )->text(),
				'name' => 'deletedOnly',
				'section' => 'contribs-top',
			];
		}

		$fields['topOnly'] = [
			'type' => 'check',
			'id' => 'mw-show-top-only',
			'label' => $this->msg( 'sp-contributions-toponly' )->text(),
			'name' => 'topOnly',
			'section' => 'contribs-top',
		];
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
				wfDeprecated(
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

		$htmlForm = HTMLForm::factory( 'ooui', $fields, $this->getContext() );
		$htmlForm
			->setMethod( 'get' )
			// When offset is defined, the user is paging through results
			// so we hide the form by default to allow users to focus on browsing
			// rather than defining search parameters
			->setCollapsibleOptions(
				( $pagerOptions['target'] ?? null ) ||
				( $pagerOptions['start'] ?? null ) ||
				( $pagerOptions['end'] ?? null )
			)
			->setAction( wfScript() )
			->setSubmitText( $this->msg( 'sp-contributions-submit' )->text() )
			->setWrapperLegend( $this->msg( 'sp-contributions-search' )->text() );

		$explain = $this->msg( 'sp-contributions-explain' );
		if ( !$explain->isBlank() ) {
			$htmlForm->addFooterText( "<p id='mw-sp-contributions-explain'>{$explain->parse()}</p>" );
		}

		$htmlForm->loadData();

		return $htmlForm->getHTML( false );
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
