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
		$out->addModuleStyles( 'mediawiki.special' );

		$this->opts = array();
		$request = $this->getRequest();

		if ( $par !== null ) {
			$target = $par;
		} else {
			$target = $request->getVal( 'target' );
		}

		// check for radiobox
		if ( $request->getVal( 'contribs' ) == 'newbie' ) {
			$target = 'newbies';
			$this->opts['contribs'] = 'newbie';
		} elseif ( $par === 'newbies' ) { // b/c for WMF
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
		$id = $userObj->getID();

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

		if ( ( $ns = $request->getVal( 'namespace', null ) ) !== null && $ns !== '' ) {
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

		$feedParams = array(
			'action' => 'feedcontributions',
			'user' => $target,
		);
		if ( $this->opts['topOnly'] ) {
			$feedParams['toponly'] = true;
		}
		if ( $this->opts['newOnly'] ) {
			$feedParams['newonly'] = true;
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

		if ( Hooks::run( 'SpecialContributionsBeforeMainOutput', array( $id, $userObj, $this ) ) ) {
			if ( !$this->including() ) {
				$out->addHTML( $this->getForm() );
			}
			$pager = new ContribsPager( $this->getContext(), array(
				'target' => $target,
				'contribs' => $this->opts['contribs'],
				'namespace' => $this->opts['namespace'],
				'tagfilter' => $this->opts['tagfilter'],
				'year' => $this->opts['year'],
				'month' => $this->opts['month'],
				'deletedOnly' => $this->opts['deletedOnly'],
				'topOnly' => $this->opts['topOnly'],
				'newOnly' => $this->opts['newOnly'],
				'nsInvert' => $this->opts['nsInvert'],
				'associated' => $this->opts['associated'],
			) );

			if ( !$pager->getNumRows() ) {
				$out->addWikiMsg( 'nocontribs', $target );
			} else {
				# Show a message about slave lag, if applicable
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
							array( $message, $target ) );
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
					array(
						'contributions-userdoesnotexist',
						wfEscapeWikiText( $userObj->getName() ),
					)
				);
				if ( !$this->including() ) {
					$this->getOutput()->setStatusCode( 404 );
				}
			}
			$user = htmlspecialchars( $userObj->getName() );
		} else {
			$user = Linker::link( $userObj->getUserPage(), htmlspecialchars( $userObj->getName() ) );
		}
		$nt = $userObj->getUserPage();
		$talk = $userObj->getTalkPage();
		$links = '';
		if ( $talk ) {
			$tools = $this->getUserLinks( $nt, $talk, $userObj );
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
						array(
							'lim' => 1,
							'showIfEmpty' => false,
							'msgKey' => array(
								$userObj->isAnon() ?
									'sp-contributions-blocked-notice-anon' :
									'sp-contributions-blocked-notice',
								$userObj->getName() # Support GENDER in 'sp-contributions-blocked-notice'
							),
							'offset' => '' # don't use WebRequest parameter offset
						)
					);
				}
			}
		}

		return $this->msg( 'contribsub2' )->rawParams( $user, $links )->params( $userObj->getName() );
	}

	/**
	 * Links to different places.
	 * @param Title $userpage Target user page
	 * @param Title $talkpage Talk page
	 * @param User $target Target user object
	 * @return array
	 */
	public function getUserLinks( Title $userpage, Title $talkpage, User $target ) {

		$id = $target->getId();
		$username = $target->getName();

		$tools[] = Linker::link( $talkpage, $this->msg( 'sp-contributions-talk' )->escaped() );

		if ( ( $id !== null ) || ( $id === null && IP::isIPAddress( $username ) ) ) {
			if ( $this->getUser()->isAllowed( 'block' ) ) { # Block / Change block / Unblock links
				if ( $target->isBlocked() && $target->getBlock()->getType() != Block::TYPE_AUTO ) {
					$tools[] = Linker::linkKnown( # Change block link
						SpecialPage::getTitleFor( 'Block', $username ),
						$this->msg( 'change-blocklink' )->escaped()
					);
					$tools[] = Linker::linkKnown( # Unblock link
						SpecialPage::getTitleFor( 'Unblock', $username ),
						$this->msg( 'unblocklink' )->escaped()
					);
				} else { # User is not blocked
					$tools[] = Linker::linkKnown( # Block link
						SpecialPage::getTitleFor( 'Block', $username ),
						$this->msg( 'blocklink' )->escaped()
					);
				}
			}

			# Block log link
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Log', 'block' ),
				$this->msg( 'sp-contributions-blocklog' )->escaped(),
				array(),
				array( 'page' => $userpage->getPrefixedText() )
			);

			# Suppression log link (bug 59120)
			if ( $this->getUser()->isAllowed( 'suppressionlog' ) ) {
				$tools[] = Linker::linkKnown(
					SpecialPage::getTitleFor( 'Log', 'suppress' ),
					$this->msg( 'sp-contributions-suppresslog' )->escaped(),
					array(),
					array( 'offender' => $username )
				);
			}
		}
		# Uploads
		$tools[] = Linker::linkKnown(
			SpecialPage::getTitleFor( 'Listfiles', $username ),
			$this->msg( 'sp-contributions-uploads' )->escaped()
		);

		# Other logs link
		$tools[] = Linker::linkKnown(
			SpecialPage::getTitleFor( 'Log', $username ),
			$this->msg( 'sp-contributions-logs' )->escaped()
		);

		# Add link to deleted user contributions for priviledged users
		if ( $this->getUser()->isAllowed( 'deletedhistory' ) ) {
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'DeletedContributions', $username ),
				$this->msg( 'sp-contributions-deleted' )->escaped()
			);
		}

		# Add a link to change user rights for privileged users
		$userrightsPage = new UserrightsPage();
		$userrightsPage->setContext( $this->getContext() );
		if ( $userrightsPage->userCanChangeRights( $target ) ) {
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Userrights', $username ),
				$this->msg( 'sp-contributions-userrights' )->escaped()
			);
		}

		Hooks::run( 'ContributionsToolLinks', array( $id, $userpage, &$tools ) );

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

		$form = Html::openElement(
			'form',
			array(
				'method' => 'get',
				'action' => wfScript(),
				'class' => 'mw-contributions-form'
			)
		);

		# Add hidden params for tracking except for parameters in $skipParameters
		$skipParameters = array(
			'namespace',
			'nsInvert',
			'deletedOnly',
			'target',
			'contribs',
			'year',
			'month',
			'topOnly',
			'newOnly',
			'associated'
		);

		foreach ( $this->opts as $name => $value ) {
			if ( in_array( $name, $skipParameters ) ) {
				continue;
			}
			$form .= "\t" . Html::hidden( $name, $value ) . "\n";
		}

		$tagFilter = ChangeTags::buildTagFilterSelector( $this->opts['tagfilter'] );

		if ( $tagFilter ) {
			$filterSelection = Html::rawElement(
				'td',
				array(),
				array_shift( $tagFilter ) . implode( '&#160', $tagFilter )
			);
		} else {
			$filterSelection = Html::rawElement( 'td', array( 'colspan' => 2 ), '' );
		}

		$this->getOutput()->addModules( 'mediawiki.userSuggest' );

		$labelNewbies = Xml::radioLabel(
			$this->msg( 'sp-contributions-newbies' )->text(),
			'contribs',
			'newbie',
			'newbie',
			$this->opts['contribs'] == 'newbie',
			array( 'class' => 'mw-input' )
		);
		$labelUsername = Xml::radioLabel(
			$this->msg( 'sp-contributions-username' )->text(),
			'contribs',
			'user',
			'user',
			$this->opts['contribs'] == 'user',
			array( 'class' => 'mw-input' )
		);
		$input = Html::input(
			'target',
			$this->opts['target'],
			'text',
			array(
				'size' => '40',
				'required' => '',
				'class' => array(
					'mw-input',
					'mw-ui-input-inline',
					'mw-autocomplete-user', // used by mediawiki.userSuggest
				),
			) + ( $this->opts['target'] ? array() : array( 'autofocus' ) )
		);
		$targetSelection = Html::rawElement(
			'td',
			array( 'colspan' => 2 ),
			$labelNewbies . '<br />' . $labelUsername . ' ' . $input . ' '
		);

		$namespaceSelection = Xml::tags(
			'td',
			array(),
			Xml::label(
				$this->msg( 'namespace' )->text(),
				'namespace',
				''
			) .
			Html::namespaceSelector(
				array( 'selected' => $this->opts['namespace'], 'all' => '' ),
				array(
					'name' => 'namespace',
					'id' => 'namespace',
					'class' => 'namespaceselector',
				)
			) . '&#160;' .
				Html::rawElement(
					'span',
					array( 'style' => 'white-space: nowrap' ),
					Xml::checkLabel(
						$this->msg( 'invert' )->text(),
						'nsInvert',
						'nsInvert',
						$this->opts['nsInvert'],
						array(
							'title' => $this->msg( 'tooltip-invert' )->text(),
							'class' => 'mw-input'
						)
					) . '&#160;'
				) .
				Html::rawElement( 'span', array( 'style' => 'white-space: nowrap' ),
					Xml::checkLabel(
						$this->msg( 'namespace_association' )->text(),
						'associated',
						'associated',
						$this->opts['associated'],
						array(
							'title' => $this->msg( 'tooltip-namespace_association' )->text(),
							'class' => 'mw-input'
						)
					) . '&#160;'
				)
		);

		if ( $this->getUser()->isAllowed( 'deletedhistory' ) ) {
			$deletedOnlyCheck = Html::rawElement(
				'span',
				array( 'style' => 'white-space: nowrap' ),
				Xml::checkLabel(
					$this->msg( 'history-show-deleted' )->text(),
					'deletedOnly',
					'mw-show-deleted-only',
					$this->opts['deletedOnly'],
					array( 'class' => 'mw-input' )
				)
			);
		} else {
			$deletedOnlyCheck = '';
		}

		$checkLabelTopOnly = Html::rawElement(
			'span',
			array( 'style' => 'white-space: nowrap' ),
			Xml::checkLabel(
				$this->msg( 'sp-contributions-toponly' )->text(),
				'topOnly',
				'mw-show-top-only',
				$this->opts['topOnly'],
				array( 'class' => 'mw-input' )
			)
		);
		$checkLabelNewOnly = Html::rawElement(
			'span',
			array( 'style' => 'white-space: nowrap' ),
			Xml::checkLabel(
				$this->msg( 'sp-contributions-newonly' )->text(),
				'newOnly',
				'mw-show-new-only',
				$this->opts['newOnly'],
				array( 'class' => 'mw-input' )
			)
		);
		$extraOptions = Html::rawElement(
			'td',
			array( 'colspan' => 2 ),
			$deletedOnlyCheck . $checkLabelTopOnly . $checkLabelNewOnly
		);

		$dateSelectionAndSubmit = Xml::tags( 'td', array( 'colspan' => 2 ),
			Xml::dateMenu(
				$this->opts['year'] === '' ? MWTimestamp::getInstance()->format( 'Y' ) : $this->opts['year'],
				$this->opts['month']
			) . ' ' .
				Html::submitButton(
					$this->msg( 'sp-contributions-submit' )->text(),
					array( 'class' => 'mw-submit' ), array( 'mw-ui-progressive' )
				)
		);

		$form .= Xml::fieldset( $this->msg( 'sp-contributions-search' )->text() );
		$form .= Html::rawElement( 'table', array( 'class' => 'mw-contributions-table' ), "\n" .
			Html::rawElement( 'tr', array(), $targetSelection ) . "\n" .
			Html::rawElement( 'tr', array(), $namespaceSelection ) . "\n" .
			Html::rawElement( 'tr', array(), $filterSelection ) . "\n" .
			Html::rawElement( 'tr', array(), $extraOptions ) . "\n" .
			Html::rawElement( 'tr', array(), $dateSelectionAndSubmit ) . "\n"
		);

		$explain = $this->msg( 'sp-contributions-explain' );
		if ( !$explain->isBlank() ) {
			$form .= "<p id='mw-sp-contributions-explain'>{$explain->parse()}</p>";
		}

		$form .= Xml::closeElement( 'fieldset' ) . Xml::closeElement( 'form' );

		return $form;
	}

	protected function getGroupName() {
		return 'users';
	}
}

/**
 * Pager for Special:Contributions
 * @ingroup SpecialPage Pager
 */
class ContribsPager extends ReverseChronologicalPager {
	public $mDefaultDirection = IndexPager::DIR_DESCENDING;
	public $messages;
	public $target;
	public $namespace = '';
	public $mDb;
	public $preventClickjacking = false;

	/** @var IDatabase */
	public $mDbSecondary;

	/**
	 * @var array
	 */
	protected $mParentLens;

	function __construct( IContextSource $context, array $options ) {
		parent::__construct( $context );

		$msgs = array(
			'diff',
			'hist',
			'pipe-separator',
			'uctop'
		);

		foreach ( $msgs as $msg ) {
			$this->messages[$msg] = $this->msg( $msg )->escaped();
		}

		$this->target = isset( $options['target'] ) ? $options['target'] : '';
		$this->contribs = isset( $options['contribs'] ) ? $options['contribs'] : 'users';
		$this->namespace = isset( $options['namespace'] ) ? $options['namespace'] : '';
		$this->tagFilter = isset( $options['tagfilter'] ) ? $options['tagfilter'] : false;
		$this->nsInvert = isset( $options['nsInvert'] ) ? $options['nsInvert'] : false;
		$this->associated = isset( $options['associated'] ) ? $options['associated'] : false;

		$this->deletedOnly = !empty( $options['deletedOnly'] );
		$this->topOnly = !empty( $options['topOnly'] );
		$this->newOnly = !empty( $options['newOnly'] );

		$year = isset( $options['year'] ) ? $options['year'] : false;
		$month = isset( $options['month'] ) ? $options['month'] : false;
		$this->getDateCond( $year, $month );

		// Most of this code will use the 'contributions' group DB, which can map to slaves
		// with extra user based indexes or partioning by user. The additional metadata
		// queries should use a regular slave since the lookup pattern is not all by user.
		$this->mDbSecondary = wfGetDB( DB_SLAVE ); // any random slave
		$this->mDb = wfGetDB( DB_SLAVE, 'contributions' );
	}

	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['target'] = $this->target;

		return $query;
	}

	/**
	 * This method basically executes the exact same code as the parent class, though with
	 * a hook added, to allow extensions to add additional queries.
	 *
	 * @param string $offset Index offset, inclusive
	 * @param int $limit Exact query limit
	 * @param bool $descending Query direction, false for ascending, true for descending
	 * @return ResultWrapper
	 */
	function reallyDoQuery( $offset, $limit, $descending ) {
		list( $tables, $fields, $conds, $fname, $options, $join_conds ) = $this->buildQueryInfo(
			$offset,
			$limit,
			$descending
		);
		$pager = $this;

		/*
		 * This hook will allow extensions to add in additional queries, so they can get their data
		 * in My Contributions as well. Extensions should append their results to the $data array.
		 *
		 * Extension queries have to implement the navbar requirement as well. They should
		 * - have a column aliased as $pager->getIndexField()
		 * - have LIMIT set
		 * - have a WHERE-clause that compares the $pager->getIndexField()-equivalent column to the offset
		 * - have the ORDER BY specified based upon the details provided by the navbar
		 *
		 * See includes/Pager.php buildQueryInfo() method on how to build LIMIT, WHERE & ORDER BY
		 *
		 * &$data: an array of results of all contribs queries
		 * $pager: the ContribsPager object hooked into
		 * $offset: see phpdoc above
		 * $limit: see phpdoc above
		 * $descending: see phpdoc above
		 */
		$data = array( $this->mDb->select(
			$tables, $fields, $conds, $fname, $options, $join_conds
		) );
		Hooks::run(
			'ContribsPager::reallyDoQuery',
			array( &$data, $pager, $offset, $limit, $descending )
		);

		$result = array();

		// loop all results and collect them in an array
		foreach ( $data as $query ) {
			foreach ( $query as $i => $row ) {
				// use index column as key, allowing us to easily sort in PHP
				$result[$row->{$this->getIndexField()} . "-$i"] = $row;
			}
		}

		// sort results
		if ( $descending ) {
			ksort( $result );
		} else {
			krsort( $result );
		}

		// enforce limit
		$result = array_slice( $result, 0, $limit );

		// get rid of array keys
		$result = array_values( $result );

		return new FakeResultWrapper( $result );
	}

	function getQueryInfo() {
		list( $tables, $index, $userCond, $join_cond ) = $this->getUserCond();

		$user = $this->getUser();
		$conds = array_merge( $userCond, $this->getNamespaceCond() );

		// Paranoia: avoid brute force searches (bug 17342)
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$conds[] = $this->mDb->bitAnd( 'rev_deleted', Revision::DELETED_USER ) . ' = 0';
		} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$conds[] = $this->mDb->bitAnd( 'rev_deleted', Revision::SUPPRESSED_USER ) .
				' != ' . Revision::SUPPRESSED_USER;
		}

		# Don't include orphaned revisions
		$join_cond['page'] = Revision::pageJoinCond();
		# Get the current user name for accounts
		$join_cond['user'] = Revision::userJoinCond();

		$options = array();
		if ( $index ) {
			$options['USE INDEX'] = array( 'revision' => $index );
		}

		$queryInfo = array(
			'tables' => $tables,
			'fields' => array_merge(
				Revision::selectFields(),
				Revision::selectUserFields(),
				array( 'page_namespace', 'page_title', 'page_is_new',
					'page_latest', 'page_is_redirect', 'page_len' )
			),
			'conds' => $conds,
			'options' => $options,
			'join_conds' => $join_cond
		);

		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			$this->tagFilter
		);

		Hooks::run( 'ContribsPager::getQueryInfo', array( &$this, &$queryInfo ) );

		return $queryInfo;
	}

	function getUserCond() {
		$condition = array();
		$join_conds = array();
		$tables = array( 'revision', 'page', 'user' );
		$index = false;
		if ( $this->contribs == 'newbie' ) {
			$max = $this->mDb->selectField( 'user', 'max(user_id)', false, __METHOD__ );
			$condition[] = 'rev_user >' . (int)( $max - $max / 100 );
			# ignore local groups with the bot right
			# @todo FIXME: Global groups may have 'bot' rights
			$groupsWithBotPermission = User::getGroupsWithPermission( 'bot' );
			if ( count( $groupsWithBotPermission ) ) {
				$tables[] = 'user_groups';
				$condition[] = 'ug_group IS NULL';
				$join_conds['user_groups'] = array(
					'LEFT JOIN', array(
						'ug_user = rev_user',
						'ug_group' => $groupsWithBotPermission
					)
				);
			}
		} else {
			$uid = User::idFromName( $this->target );
			if ( $uid ) {
				$condition['rev_user'] = $uid;
				$index = 'user_timestamp';
			} else {
				$condition['rev_user_text'] = $this->target;
				$index = 'usertext_timestamp';
			}
		}

		if ( $this->deletedOnly ) {
			$condition[] = 'rev_deleted != 0';
		}

		if ( $this->topOnly ) {
			$condition[] = 'rev_id = page_latest';
		}

		if ( $this->newOnly ) {
			$condition[] = 'rev_parent_id = 0';
		}

		return array( $tables, $index, $condition, $join_conds );
	}

	function getNamespaceCond() {
		if ( $this->namespace !== '' ) {
			$selectedNS = $this->mDb->addQuotes( $this->namespace );
			$eq_op = $this->nsInvert ? '!=' : '=';
			$bool_op = $this->nsInvert ? 'AND' : 'OR';

			if ( !$this->associated ) {
				return array( "page_namespace $eq_op $selectedNS" );
			}

			$associatedNS = $this->mDb->addQuotes(
				MWNamespace::getAssociated( $this->namespace )
			);

			return array(
				"page_namespace $eq_op $selectedNS " .
					$bool_op .
					" page_namespace $eq_op $associatedNS"
			);
		}

		return array();
	}

	function getIndexField() {
		return 'rev_timestamp';
	}

	function doBatchLookups() {
		# Do a link batch query
		$this->mResult->seek( 0 );
		$revIds = array();
		$batch = new LinkBatch();
		# Give some pointers to make (last) links
		foreach ( $this->mResult as $row ) {
			if ( isset( $row->rev_parent_id ) && $row->rev_parent_id ) {
				$revIds[] = $row->rev_parent_id;
			}
			if ( isset( $row->rev_id ) ) {
				if ( $this->contribs === 'newbie' ) { // multiple users
					$batch->add( NS_USER, $row->user_name );
					$batch->add( NS_USER_TALK, $row->user_name );
				}
				$batch->add( $row->page_namespace, $row->page_title );
			}
		}
		$this->mParentLens = Revision::getParentLengths( $this->mDbSecondary, $revIds );
		$batch->execute();
		$this->mResult->seek( 0 );
	}

	/**
	 * @return string
	 */
	function getStartBody() {
		return "<ul class=\"mw-contributions-list\">\n";
	}

	/**
	 * @return string
	 */
	function getEndBody() {
		return "</ul>\n";
	}

	/**
	 * Generates each row in the contributions list.
	 *
	 * Contributions which are marked "top" are currently on top of the history.
	 * For these contributions, a [rollback] link is shown for users with roll-
	 * back privileges. The rollback link restores the most recent version that
	 * was not written by the target user.
	 *
	 * @todo This would probably look a lot nicer in a table.
	 * @param object $row
	 * @return string
	 */
	function formatRow( $row ) {

		$ret = '';
		$classes = array();

		/*
		 * There may be more than just revision rows. To make sure that we'll only be processing
		 * revisions here, let's _try_ to build a revision out of our row (without displaying
		 * notices though) and then trying to grab data from the built object. If we succeed,
		 * we're definitely dealing with revision data and we may proceed, if not, we'll leave it
		 * to extensions to subscribe to the hook to parse the row.
		 */
		wfSuppressWarnings();
		try {
			$rev = new Revision( $row );
			$validRevision = (bool)$rev->getId();
		} catch ( Exception $e ) {
			$validRevision = false;
		}
		wfRestoreWarnings();

		if ( $validRevision ) {
			$classes = array();

			$page = Title::newFromRow( $row );
			$link = Linker::link(
				$page,
				htmlspecialchars( $page->getPrefixedText() ),
				array( 'class' => 'mw-contributions-title' ),
				$page->isRedirect() ? array( 'redirect' => 'no' ) : array()
			);
			# Mark current revisions
			$topmarktext = '';
			$user = $this->getUser();
			if ( $row->rev_id == $row->page_latest ) {
				$topmarktext .= '<span class="mw-uctop">' . $this->messages['uctop'] . '</span>';
				# Add rollback link
				if ( !$row->page_is_new && $page->quickUserCan( 'rollback', $user )
					&& $page->quickUserCan( 'edit', $user )
				) {
					$this->preventClickjacking();
					$topmarktext .= ' ' . Linker::generateRollback( $rev, $this->getContext() );
				}
			}
			# Is there a visible previous revision?
			if ( $rev->userCan( Revision::DELETED_TEXT, $user ) && $rev->getParentId() !== 0 ) {
				$difftext = Linker::linkKnown(
					$page,
					$this->messages['diff'],
					array(),
					array(
						'diff' => 'prev',
						'oldid' => $row->rev_id
					)
				);
			} else {
				$difftext = $this->messages['diff'];
			}
			$histlink = Linker::linkKnown(
				$page,
				$this->messages['hist'],
				array(),
				array( 'action' => 'history' )
			);

			if ( $row->rev_parent_id === null ) {
				// For some reason rev_parent_id isn't populated for this row.
				// Its rumoured this is true on wikipedia for some revisions (bug 34922).
				// Next best thing is to have the total number of bytes.
				$chardiff = ' <span class="mw-changeslist-separator">. .</span> ';
				$chardiff .= Linker::formatRevisionSize( $row->rev_len );
				$chardiff .= ' <span class="mw-changeslist-separator">. .</span> ';
			} else {
				$parentLen = 0;
				if ( isset( $this->mParentLens[$row->rev_parent_id] ) ) {
					$parentLen = $this->mParentLens[$row->rev_parent_id];
				}

				$chardiff = ' <span class="mw-changeslist-separator">. .</span> ';
				$chardiff .= ChangesList::showCharacterDifference(
					$parentLen,
					$row->rev_len,
					$this->getContext()
				);
				$chardiff .= ' <span class="mw-changeslist-separator">. .</span> ';
			}

			$lang = $this->getLanguage();
			$comment = $lang->getDirMark() . Linker::revComment( $rev, false, true );
			$date = $lang->userTimeAndDate( $row->rev_timestamp, $user );
			if ( $rev->userCan( Revision::DELETED_TEXT, $user ) ) {
				$d = Linker::linkKnown(
					$page,
					htmlspecialchars( $date ),
					array( 'class' => 'mw-changeslist-date' ),
					array( 'oldid' => intval( $row->rev_id ) )
				);
			} else {
				$d = htmlspecialchars( $date );
			}
			if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
				$d = '<span class="history-deleted">' . $d . '</span>';
			}

			# Show user names for /newbies as there may be different users.
			# Note that we already excluded rows with hidden user names.
			if ( $this->contribs == 'newbie' ) {
				$userlink = ' . . ' . $lang->getDirMark()
					. Linker::userLink( $rev->getUser(), $rev->getUserText() );
				$userlink .= ' ' . $this->msg( 'parentheses' )->rawParams(
					Linker::userTalkLink( $rev->getUser(), $rev->getUserText() ) )->escaped() . ' ';
			} else {
				$userlink = '';
			}

			if ( $rev->getParentId() === 0 ) {
				$nflag = ChangesList::flag( 'newpage' );
			} else {
				$nflag = '';
			}

			if ( $rev->isMinor() ) {
				$mflag = ChangesList::flag( 'minor' );
			} else {
				$mflag = '';
			}

			$del = Linker::getRevDeleteLink( $user, $rev, $page );
			if ( $del !== '' ) {
				$del .= ' ';
			}

			$diffHistLinks = $this->msg( 'parentheses' )
				->rawParams( $difftext . $this->messages['pipe-separator'] . $histlink )
				->escaped();
			$ret = "{$del}{$d} {$diffHistLinks}{$chardiff}{$nflag}{$mflag} ";
			$ret .= "{$link}{$userlink} {$comment} {$topmarktext}";

			# Denote if username is redacted for this edit
			if ( $rev->isDeleted( Revision::DELETED_USER ) ) {
				$ret .= " <strong>" .
					$this->msg( 'rev-deleted-user-contribs' )->escaped() .
					"</strong>";
			}

			# Tags, if any.
			list( $tagSummary, $newClasses ) = ChangeTags::formatSummaryRow(
				$row->ts_tags,
				'contributions'
			);
			$classes = array_merge( $classes, $newClasses );
			$ret .= " $tagSummary";
		}

		// Let extensions add data
		Hooks::run( 'ContributionsLineEnding', array( $this, &$ret, $row, &$classes ) );

		if ( $classes === array() && $ret === '' ) {
			wfDebug( "Dropping Special:Contribution row that could not be formatted\n" );
			$ret = "<!-- Could not format Special:Contribution row. -->\n";
		} else {
			$ret = Html::rawElement( 'li', array( 'class' => $classes ), $ret ) . "\n";
		}

		return $ret;
	}

	/**
	 * Overwrite Pager function and return a helpful comment
	 * @return string
	 */
	function getSqlComment() {
		if ( $this->namespace || $this->deletedOnly ) {
			// potentially slow, see CR r58153
			return 'contributions page filtered for namespace or RevisionDeleted edits';
		} else {
			return 'contributions page unfiltered';
		}
	}

	protected function preventClickjacking() {
		$this->preventClickjacking = true;
	}

	/**
	 * @return bool
	 */
	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}
}
