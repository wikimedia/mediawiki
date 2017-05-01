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
		$out->addModuleStyles( [
			'mediawiki.special',
			'mediawiki.special.changeslist',
		] );
		$this->addHelpLink( 'Help:User contributions' );

		$this->opts = [];
		$request = $this->getRequest();

		if ( $par !== null ) {
			$target = $par;
		} else {
			$target = $request->getVal( 'target' );
		}

		if ( $request->getVal( 'contribs' ) == 'newbie' || $par === 'newbies' ) {
			$target = 'newbies';
			$this->opts['contribs'] = 'newbie';
		} else {
			$this->opts['contribs'] = 'user';
		}

		$this->opts['deletedOnly'] = $request->getBool( 'deletedOnly' );

		if ( !strlen( $target ) ) {
			if ( !$this->including() ) {
				$out->addHTML( $this->getForm() );
			}

			return;
		}

		$user = $this->getUser();

		$this->opts['limit'] = $request->getInt( 'limit', $user->getOption( 'rclimit' ) );
		$this->opts['target'] = $target;
		$this->opts['topOnly'] = $request->getBool( 'topOnly' );
		$this->opts['newOnly'] = $request->getBool( 'newOnly' );
		$this->opts['hideMinor'] = $request->getBool( 'hideMinor' );

		$nt = Title::makeTitleSafe( NS_USER, $target );
		if ( !$nt ) {
			$out->addHTML( $this->getForm() );

			return;
		}
		$userObj = User::newFromName( $nt->getText(), false );
		if ( !$userObj ) {
			$out->addHTML( $this->getForm() );

			return;
		}
		$id = $userObj->getId();

		if ( $this->opts['contribs'] != 'newbie' ) {
			$target = $nt->getText();
			$out->addSubtitle( $this->contributionsSub( $userObj ) );
			$out->setHTMLTitle( $this->msg(
				'pagetitle',
				$this->msg( 'contributions-title', $target )->plain()
			)->inContentLanguage() );
			$this->getSkin()->setRelevantUser( $userObj );
		} else {
			$out->addSubtitle( $this->msg( 'sp-contributions-newbies-sub' ) );
			$out->setHTMLTitle( $this->msg(
				'pagetitle',
				$this->msg( 'sp-contributions-newbies-title' )->plain()
			)->inContentLanguage() );
		}

		$ns = $request->getVal( 'namespace', null );
		if ( $ns !== null && $ns !== '' ) {
			$this->opts['namespace'] = intval( $ns );
		} else {
			$this->opts['namespace'] = '';
		}

		$this->opts['associated'] = $request->getBool( 'associated' );
		$this->opts['nsInvert'] = (bool)$request->getVal( 'nsInvert' );
		$this->opts['tagfilter'] = (string)$request->getVal( 'tagfilter' );

		// Allows reverts to have the bot flag in recent changes. It is just here to
		// be passed in the form at the top of the page
		if ( $user->isAllowed( 'markbotedits' ) && $request->getBool( 'bot' ) ) {
			$this->opts['bot'] = '1';
		}

		$skip = $request->getText( 'offset' ) || $request->getText( 'dir' ) == 'prev';
		# Offset overrides year/month selection
		if ( $skip ) {
			$this->opts['year'] = '';
			$this->opts['month'] = '';
		} else {
			$this->opts['year'] = $request->getIntOrNull( 'year' );
			$this->opts['month'] = $request->getIntOrNull( 'month' );
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

		if ( Hooks::run( 'SpecialContributionsBeforeMainOutput', [ $id, $userObj, $this ] ) ) {
			if ( !$this->including() ) {
				$out->addHTML( $this->getForm() );
			}
			$pager = new ContribsPager( $this->getContext(), [
				'target' => $target,
				'contribs' => $this->opts['contribs'],
				'namespace' => $this->opts['namespace'],
				'tagfilter' => $this->opts['tagfilter'],
				'year' => $this->opts['year'],
				'month' => $this->opts['month'],
				'deletedOnly' => $this->opts['deletedOnly'],
				'topOnly' => $this->opts['topOnly'],
				'newOnly' => $this->opts['newOnly'],
				'hideMinor' => $this->opts['hideMinor'],
				'nsInvert' => $this->opts['nsInvert'],
				'associated' => $this->opts['associated'],
			] );

			if ( !$pager->getNumRows() ) {
				$out->addWikiMsg( 'nocontribs', $target );
			} else {
				# Show a message about replica DB lag, if applicable
				$lag = wfGetLB()->safeGetLag( $pager->getDatabase() );
				if ( $lag > 0 ) {
					$out->showLagWarning( $lag );
				}

				$output = $pager->getBody();
				if ( !$this->including() ) {
					$output = '<p>' . $pager->getNavigationBar() . '</p>' .
						$output .
						'<p>' . $pager->getNavigationBar() . '</p>';
				}
				$out->addHTML( $output );
			}
			$out->preventClickjacking( $pager->getPreventClickjacking() );

			# Show the appropriate "footer" message - WHOIS tools, etc.
			if ( $this->opts['contribs'] == 'newbie' ) {
				$message = 'sp-contributions-footer-newbies';
			} elseif ( IP::isIPAddress( $target ) ) {
				$message = 'sp-contributions-footer-anon';
			} elseif ( $userObj->isAnon() ) {
				// No message for non-existing users
				$message = '';
			} else {
				$message = 'sp-contributions-footer';
			}

			if ( $message ) {
				if ( !$this->including() ) {
					if ( !$this->msg( $message, $target )->isDisabled() ) {
						$out->wrapWikiMsg(
							"<div class='mw-contributions-footer'>\n$1\n</div>",
							[ $message, $target ] );
					}
				}
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
		if ( $userObj->isAnon() ) {
			// Show a warning message that the user being searched for doesn't exists
			if ( !User::isIP( $userObj->getName() ) ) {
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
			$links = $this->getLanguage()->pipeList( $tools );

			// Show a note if the user is blocked and display the last block log entry.
			// Do not expose the autoblocks, since that may lead to a leak of accounts' IPs,
			// and also this will display a totally irrelevant log entry as a current block.
			if ( !$this->including() ) {
				$block = Block::newFromTarget( $userObj, $userObj );
				if ( !is_null( $block ) && $block->getType() != Block::TYPE_AUTO ) {
					if ( $block->getType() == Block::TYPE_RANGE ) {
						$nt = MWNamespace::getCanonicalName( NS_USER ) . ':' . $block->getTarget();
					}

					$out = $this->getOutput(); // showLogExtract() wants first parameter by reference
					LogEventsList::showLogExtract(
						$out,
						'block',
						$nt,
						'',
						[
							'lim' => 1,
							'showIfEmpty' => false,
							'msgKey' => [
								$userObj->isAnon() ?
									'sp-contributions-blocked-notice-anon' :
									'sp-contributions-blocked-notice',
								$userObj->getName() # Support GENDER in 'sp-contributions-blocked-notice'
							],
							'offset' => '' # don't use WebRequest parameter offset
						]
					);
				}
			}
		}

		return $this->msg( 'contribsub2' )->rawParams( $user, $links )->params( $userObj->getName() );
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

		$linkRenderer = $sp->getLinkRenderer();
		$tools['user-talk'] = $linkRenderer->makeLink(
			$talkpage,
			$sp->msg( 'sp-contributions-talk' )->text()
		);

		if ( ( $id !== null ) || ( $id === null && IP::isIPAddress( $username ) ) ) {
			if ( $sp->getUser()->isAllowed( 'block' ) ) { # Block / Change block / Unblock links
				if ( $target->isBlocked() && $target->getBlock()->getType() != Block::TYPE_AUTO ) {
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
			if ( $sp->getUser()->isAllowed( 'suppressionlog' ) ) {
				$tools['log-suppression'] = $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'Log', 'suppress' ),
					$sp->msg( 'sp-contributions-suppresslog', $username )->text(),
					[],
					[ 'offender' => $username ]
				);
			}
		}
		# Uploads
		$tools['uploads'] = $linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Listfiles', $username ),
			$sp->msg( 'sp-contributions-uploads' )->text()
		);

		# Other logs link
		$tools['logs'] = $linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Log', $username ),
			$sp->msg( 'sp-contributions-logs' )->text()
		);

		# Add link to deleted user contributions for priviledged users
		if ( $sp->getUser()->isAllowed( 'deletedhistory' ) ) {
			$tools['deletedcontribs'] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'DeletedContributions', $username ),
				$sp->msg( 'sp-contributions-deleted', $username )->text()
			);
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

		Hooks::run( 'ContributionsToolLinks', [ $id, $userpage, &$tools, $sp ] );

		return $tools;
	}

	/**
	 * Generates the namespace selector form with hidden attributes.
	 * @return string HTML fragment
	 */
	protected function getForm() {
		$this->opts['title'] = $this->getPageTitle()->getPrefixedText();
		if ( !isset( $this->opts['target'] ) ) {
			$this->opts['target'] = '';
		} else {
			$this->opts['target'] = str_replace( '_', ' ', $this->opts['target'] );
		}

		if ( !isset( $this->opts['namespace'] ) ) {
			$this->opts['namespace'] = '';
		}

		if ( !isset( $this->opts['nsInvert'] ) ) {
			$this->opts['nsInvert'] = '';
		}

		if ( !isset( $this->opts['associated'] ) ) {
			$this->opts['associated'] = false;
		}

		if ( !isset( $this->opts['contribs'] ) ) {
			$this->opts['contribs'] = 'user';
		}

		if ( !isset( $this->opts['year'] ) ) {
			$this->opts['year'] = '';
		}

		if ( !isset( $this->opts['month'] ) ) {
			$this->opts['month'] = '';
		}

		if ( $this->opts['contribs'] == 'newbie' ) {
			$this->opts['target'] = '';
		}

		if ( !isset( $this->opts['tagfilter'] ) ) {
			$this->opts['tagfilter'] = '';
		}

		if ( !isset( $this->opts['topOnly'] ) ) {
			$this->opts['topOnly'] = false;
		}

		if ( !isset( $this->opts['newOnly'] ) ) {
			$this->opts['newOnly'] = false;
		}

		if ( !isset( $this->opts['hideMinor'] ) ) {
			$this->opts['hideMinor'] = false;
		}

		$form = Html::openElement(
			'form',
			[
				'method' => 'get',
				'action' => wfScript(),
				'class' => 'mw-contributions-form'
			]
		);

		# Add hidden params for tracking except for parameters in $skipParameters
		$skipParameters = [
			'namespace',
			'nsInvert',
			'deletedOnly',
			'target',
			'contribs',
			'year',
			'month',
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
			$form .= "\t" . Html::hidden( $name, $value ) . "\n";
		}

		$tagFilter = ChangeTags::buildTagFilterSelector(
			$this->opts['tagfilter'], false, $this->getContext() );

		if ( $tagFilter ) {
			$filterSelection = Html::rawElement(
				'div',
				[],
				implode( '&#160;', $tagFilter )
			);
		} else {
			$filterSelection = Html::rawElement( 'div', [], '' );
		}

		$this->getOutput()->addModules( 'mediawiki.userSuggest' );

		$labelNewbies = Xml::radioLabel(
			$this->msg( 'sp-contributions-newbies' )->text(),
			'contribs',
			'newbie',
			'newbie',
			$this->opts['contribs'] == 'newbie',
			[ 'class' => 'mw-input' ]
		);
		$labelUsername = Xml::radioLabel(
			$this->msg( 'sp-contributions-username' )->text(),
			'contribs',
			'user',
			'user',
			$this->opts['contribs'] == 'user',
			[ 'class' => 'mw-input' ]
		);
		$input = Html::input(
			'target',
			$this->opts['target'],
			'text',
			[
				'size' => '40',
				'class' => [
					'mw-input',
					'mw-ui-input-inline',
					'mw-autocomplete-user', // used by mediawiki.userSuggest
				],
			] + (
				// Only autofocus if target hasn't been specified or in non-newbies mode
				( $this->opts['contribs'] === 'newbie' || $this->opts['target'] )
					? [] : [ 'autofocus' => true ]
				)
		);

		$targetSelection = Html::rawElement(
			'div',
			[],
			$labelNewbies . '<br>' . $labelUsername . ' ' . $input . ' '
		);

		$namespaceSelection = Xml::tags(
			'div',
			[],
			Xml::label(
				$this->msg( 'namespace' )->text(),
				'namespace',
				''
			) . '&#160;' .
			Html::namespaceSelector(
				[ 'selected' => $this->opts['namespace'], 'all' => '' ],
				[
					'name' => 'namespace',
					'id' => 'namespace',
					'class' => 'namespaceselector',
				]
			) . '&#160;' .
				Html::rawElement(
					'span',
					[ 'class' => 'mw-input-with-label' ],
					Xml::checkLabel(
						$this->msg( 'invert' )->text(),
						'nsInvert',
						'nsInvert',
						$this->opts['nsInvert'],
						[
							'title' => $this->msg( 'tooltip-invert' )->text(),
							'class' => 'mw-input'
						]
					) . '&#160;'
				) .
				Html::rawElement( 'span', [ 'class' => 'mw-input-with-label' ],
					Xml::checkLabel(
						$this->msg( 'namespace_association' )->text(),
						'associated',
						'associated',
						$this->opts['associated'],
						[
							'title' => $this->msg( 'tooltip-namespace_association' )->text(),
							'class' => 'mw-input'
						]
					) . '&#160;'
				)
		);

		$filters = [];

		if ( $this->getUser()->isAllowed( 'deletedhistory' ) ) {
			$filters[] = Html::rawElement(
				'span',
				[ 'class' => 'mw-input-with-label' ],
				Xml::checkLabel(
					$this->msg( 'history-show-deleted' )->text(),
					'deletedOnly',
					'mw-show-deleted-only',
					$this->opts['deletedOnly'],
					[ 'class' => 'mw-input' ]
				)
			);
		}

		$filters[] = Html::rawElement(
			'span',
			[ 'class' => 'mw-input-with-label' ],
			Xml::checkLabel(
				$this->msg( 'sp-contributions-toponly' )->text(),
				'topOnly',
				'mw-show-top-only',
				$this->opts['topOnly'],
				[ 'class' => 'mw-input' ]
			)
		);
		$filters[] = Html::rawElement(
			'span',
			[ 'class' => 'mw-input-with-label' ],
			Xml::checkLabel(
				$this->msg( 'sp-contributions-newonly' )->text(),
				'newOnly',
				'mw-show-new-only',
				$this->opts['newOnly'],
				[ 'class' => 'mw-input' ]
			)
		);
		$filters[] = Html::rawElement(
			'span',
			[ 'class' => 'mw-input-with-label' ],
			Xml::checkLabel(
				$this->msg( 'sp-contributions-hideminor' )->text(),
				'hideMinor',
				'mw-hide-minor-edits',
				$this->opts['hideMinor'],
				[ 'class' => 'mw-input' ]
			)
		);

		Hooks::run(
			'SpecialContributions::getForm::filters',
			[ $this, &$filters ]
		);

		$extraOptions = Html::rawElement(
			'div',
			[],
			implode( '', $filters )
		);

		$dateSelectionAndSubmit = Xml::tags( 'div', [],
			Xml::dateMenu(
				$this->opts['year'] === '' ? MWTimestamp::getInstance()->format( 'Y' ) : $this->opts['year'],
				$this->opts['month']
			) . ' ' .
				Html::submitButton(
					$this->msg( 'sp-contributions-submit' )->text(),
					[ 'class' => 'mw-submit' ], [ 'mw-ui-progressive' ]
				)
		);

		$form .= Xml::fieldset(
			$this->msg( 'sp-contributions-search' )->text(),
			$targetSelection .
			$namespaceSelection .
			$filterSelection .
			$extraOptions .
			$dateSelectionAndSubmit,
			[ 'class' => 'mw-contributions-table' ]
		);

		$explain = $this->msg( 'sp-contributions-explain' );
		if ( !$explain->isBlank() ) {
			$form .= "<p id='mw-sp-contributions-explain'>{$explain->parse()}</p>";
		}

		$form .= Xml::closeElement( 'form' );

		return $form;
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
