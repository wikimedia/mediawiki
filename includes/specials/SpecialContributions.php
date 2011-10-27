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

class SpecialContributions extends SpecialPage {

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

		if( $par == 'newbies' ) {
			$target = 'newbies';
			$this->opts['contribs'] = 'newbie';
		} elseif( $par !== null ) {
			$target = $par;
		} else {
			$target = $request->getVal( 'target' );
		}

		// check for radiobox
		if( $request->getVal( 'contribs' ) == 'newbie' ) {
			$target = 'newbies';
			$this->opts['contribs'] = 'newbie';
		} else {
			$this->opts['contribs'] = 'user';
		}

		$this->opts['deletedOnly'] = $request->getBool( 'deletedOnly' );

		if( !strlen( $target ) ) {
			$out->addHTML( $this->getForm() );
			return;
		}

		$user = $this->getUser();

		$this->opts['limit'] = $request->getInt( 'limit', $user->getOption('rclimit') );
		$this->opts['target'] = $target;
		$this->opts['topOnly'] = $request->getBool( 'topOnly' );

		$nt = Title::makeTitleSafe( NS_USER, $target );
		if( !$nt ) {
			$out->addHTML( $this->getForm() );
			return;
		}
		$id = User::idFromName( $nt->getText() );

		if( $this->opts['contribs'] != 'newbie' ) {
			$target = $nt->getText();
			$out->setSubtitle( $this->contributionsSub( $nt, $id ) );
			$out->setHTMLTitleMsg( 'pagetitle', wfMsgExt( 'contributions-title', array( 'parsemag' ),$target ) );
			$userObj = User::newFromName( $target, false );
			if ( is_object( $userObj ) ) {
				$this->getSkin()->setRelevantUser( $userObj );
			}
		} else {
			$out->setSubtitle( wfMsgHtml( 'sp-contributions-newbies-sub') );
			$out->setHTMLTitleMsg( 'pagetitle', wfMsg( 'sp-contributions-newbies-title' ) );
		}

		if( ( $ns = $request->getVal( 'namespace', null ) ) !== null && $ns !== '' ) {
			$this->opts['namespace'] = intval( $ns );
		} else {
			$this->opts['namespace'] = '';
		}

		$this->opts['tagfilter'] = (string) $request->getVal( 'tagfilter' );

		// Allows reverts to have the bot flag in recent changes. It is just here to
		// be passed in the form at the top of the page
		if( $user->isAllowed( 'markbotedits' ) && $request->getBool( 'bot' ) ) {
			$this->opts['bot'] = '1';
		}

		$skip = $request->getText( 'offset' ) || $request->getText( 'dir' ) == 'prev';
		# Offset overrides year/month selection
		if( $skip ) {
			$this->opts['year'] = '';
			$this->opts['month'] = '';
		} else {
			$this->opts['year'] = $request->getIntOrNull( 'year' );
			$this->opts['month'] = $request->getIntOrNull( 'month' );
		}

		$feedType = $request->getVal( 'feed' );
		if( $feedType ) {
			// Maintain some level of backwards compatability
			// If people request feeds using the old parameters, redirect to API
			$apiParams = array(
				'action' => 'feedcontributions',
				'feedformat' => $feedType,
				'user' => $target,
			);
			if ( $this->opts['topOnly'] ) {
				$apiParams['toponly'] = true;
			}
			if ( $this->opts['deletedOnly'] ) {
				$apiParams['deletedonly'] = true;
			}
			if ( $this->opts['tagfilter'] !== '' ) {
				$apiParams['tagfilter'] = $this->opts['tagfilter'];
			}
			if ( $this->opts['namespace'] !== '' ) {
				$apiParams['namespace'] = $this->opts['namespace'];
			}
			if ( $this->opts['year'] !== null ) {
				$apiParams['year'] = $this->opts['year'];
			}
			if ( $this->opts['month'] !== null ) {
				$apiParams['month'] = $this->opts['month'];
			}

			$url = wfScript( 'api' ) . '?' . wfArrayToCGI( $apiParams );

			$out->redirect( $url, '301' );
			return;
		}

		// Add RSS/atom links
		$this->addFeedLinks( array( 'action' => 'feedcontributions', 'user' => $target ) );

		if ( wfRunHooks( 'SpecialContributionsBeforeMainOutput', array( $id ) ) ) {

			$out->addHTML( $this->getForm() );

			$pager = new ContribsPager( array(
				'target' => $target,
				'contribs' => $this->opts['contribs'],
				'namespace' => $this->opts['namespace'],
				'year' => $this->opts['year'],
				'month' => $this->opts['month'],
				'deletedOnly' => $this->opts['deletedOnly'],
				'topOnly' => $this->opts['topOnly'],
			) );
			if( !$pager->getNumRows() ) {
				$out->addWikiMsg( 'nocontribs', $target );
			} else {
				# Show a message about slave lag, if applicable
				$lag = wfGetLB()->safeGetLag( $pager->getDatabase() );
				if( $lag > 0 )
					$out->showLagWarning( $lag );

				$out->addHTML(
					'<p>' . $pager->getNavigationBar() . '</p>' .
					$pager->getBody() .
					'<p>' . $pager->getNavigationBar() . '</p>'
				);
			}
			$out->preventClickjacking( $pager->getPreventClickjacking() );

			# Show the appropriate "footer" message - WHOIS tools, etc.
			if( $this->opts['contribs'] != 'newbie' ) {
				$message = 'sp-contributions-footer';
				if ( IP::isIPAddress( $target ) ) {
					$message = 'sp-contributions-footer-anon';
				} else {
					$userObj = User::newFromName( $target );
					if ( !$userObj || $userObj->isAnon() ) {
						// No message for non-existing users
						return;
					}
				}

				if( !wfMessage( $message, $target )->isDisabled() ) {
					$out->wrapWikiMsg(
						"<div class='mw-contributions-footer'>\n$1\n</div>",
						array( $message, $target ) );
				}
			}
		}
	}

	/**
	 * Generates the subheading with links
	 * @param $nt Title object for the target
	 * @param $id Integer: User ID for the target
	 * @return String: appropriately-escaped HTML to be output literally
	 * @todo FIXME: Almost the same as getSubTitle in SpecialDeletedContributions.php. Could be combined.
	 */
	protected function contributionsSub( $nt, $id ) {
		if ( $id === null ) {
			$user = htmlspecialchars( $nt->getText() );
		} else {
			$user = Linker::link( $nt, htmlspecialchars( $nt->getText() ) );
		}
		$userObj = User::newFromName( $nt->getText(), /* check for username validity not needed */ false );
		$talk = $nt->getTalkPage();
		if( $talk ) {
			$tools = self::getUserLinks( $nt, $talk, $userObj, $this->getUser() );
			$links = $this->getLang()->pipeList( $tools );

			// Show a note if the user is blocked and display the last block log entry.
			if ( $userObj->isBlocked() ) {
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
							$nt->getText() # Support GENDER in 'sp-contributions-blocked-notice'
						),
						'offset' => '' # don't use WebRequest parameter offset
					)
				);
			}
		}

		// Old message 'contribsub' had one parameter, but that doesn't work for
		// languages that want to put the "for" bit right after $user but before
		// $links.  If 'contribsub' is around, use it for reverse compatibility,
		// otherwise use 'contribsub2'.
		if( wfEmptyMsg( 'contribsub' ) ) {
			return wfMsgHtml( 'contribsub2', $user, $links );
		} else {
			return wfMsgHtml( 'contribsub', "$user ($links)" );
		}
	}

	/**
	 * Links to different places.
	 * @param $userpage Title: Target user page
	 * @param $talkpage Title: Talk page
	 * @param $target User: Target user object
	 * @param $subject User: The viewing user ($wgUser might be still checked in some cases)
	 */
	public static function getUserLinks( Title $userpage, Title $talkpage, User $target, User $subject ) {

		$id = $target->getId();
		$username = $target->getName();

		$tools[] = Linker::link( $talkpage, wfMsgHtml( 'sp-contributions-talk' ) );

		if( ( $id !== null ) || ( $id === null && IP::isIPAddress( $username ) ) ) {
			if( $subject->isAllowed( 'block' ) ) { # Block / Change block / Unblock links
				if ( $target->isBlocked() ) {
					$tools[] = Linker::linkKnown( # Change block link
						SpecialPage::getTitleFor( 'Block', $username ),
						wfMsgHtml( 'change-blocklink' )
					);
					$tools[] = Linker::linkKnown( # Unblock link
						SpecialPage::getTitleFor( 'Unblock', $username ),
						wfMsgHtml( 'unblocklink' )
					);
				} else { # User is not blocked
					$tools[] = Linker::linkKnown( # Block link
						SpecialPage::getTitleFor( 'Block', $username ),
						wfMsgHtml( 'blocklink' )
					);
				}
			}
			# Block log link
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Log', 'block' ),
				wfMsgHtml( 'sp-contributions-blocklog' ),
				array(),
				array(
					'page' => $userpage->getPrefixedText()
				)
			);
		}
		# Uploads
		$tools[] = Linker::linkKnown(
			SpecialPage::getTitleFor( 'Listfiles', $username ),
			wfMsgHtml( 'sp-contributions-uploads' )
		);

		# Other logs link
		$tools[] = Linker::linkKnown(
			SpecialPage::getTitleFor( 'Log', $username ),
			wfMsgHtml( 'sp-contributions-logs' )
		);

		# Add link to deleted user contributions for priviledged users
		if( $subject->isAllowed( 'deletedhistory' ) ) {
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'DeletedContributions', $username ),
				wfMsgHtml( 'sp-contributions-deleted' )
			);
		}

		# Add a link to change user rights for privileged users
		$userrightsPage = new UserrightsPage();
		$userrightsPage->getContext()->setUser( $subject );
		if( $id !== null && $userrightsPage->userCanChangeRights( $target ) ) {
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Userrights', $username ),
				wfMsgHtml( 'sp-contributions-userrights' )
			);
		}

		wfRunHooks( 'ContributionsToolLinks', array( $id, $userpage, &$tools ) );
		return $tools;
	}

	/**
	 * Generates the namespace selector form with hidden attributes.
	 * @return String: HTML fragment
	 */
	protected function getForm() {
		global $wgScript;

		$this->opts['title'] = $this->getTitle()->getPrefixedText();
		if( !isset( $this->opts['target'] ) ) {
			$this->opts['target'] = '';
		} else {
			$this->opts['target'] = str_replace( '_' , ' ' , $this->opts['target'] );
		}

		if( !isset( $this->opts['namespace'] ) ) {
			$this->opts['namespace'] = '';
		}

		if( !isset( $this->opts['contribs'] ) ) {
			$this->opts['contribs'] = 'user';
		}

		if( !isset( $this->opts['year'] ) ) {
			$this->opts['year'] = '';
		}

		if( !isset( $this->opts['month'] ) ) {
			$this->opts['month'] = '';
		}

		if( $this->opts['contribs'] == 'newbie' ) {
			$this->opts['target'] = '';
		}

		if( !isset( $this->opts['tagfilter'] ) ) {
			$this->opts['tagfilter'] = '';
		}

		if( !isset( $this->opts['topOnly'] ) ) {
			$this->opts['topOnly'] = false;
		}

		$f = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'class' => 'mw-contributions-form' ) );

		# Add hidden params for tracking except for parameters in $skipParameters
		$skipParameters = array( 'namespace', 'deletedOnly', 'target', 'contribs', 'year', 'month', 'topOnly' );
		foreach ( $this->opts as $name => $value ) {
			if( in_array( $name, $skipParameters ) ) {
				continue;
			}
			$f .= "\t" . Html::hidden( $name, $value ) . "\n";
		}

		$tagFilter = ChangeTags::buildTagFilterSelector( $this->opts['tagfilter'] );

		$f .= 	Xml::fieldset( wfMsg( 'sp-contributions-search' ) ) .
			Xml::radioLabel( wfMsgExt( 'sp-contributions-newbies', array( 'parsemag' ) ),
				'contribs', 'newbie' , 'newbie', $this->opts['contribs'] == 'newbie' ) . '<br />' .
			Xml::radioLabel( wfMsgExt( 'sp-contributions-username', array( 'parsemag' ) ),
				'contribs' , 'user', 'user', $this->opts['contribs'] == 'user' ) . ' ' .
			Html::input( 'target', $this->opts['target'], 'text', array(
				'size' => '20',
				'required' => ''
			) + ( $this->opts['target'] ? array() : array( 'autofocus' ) ) ) . ' '.
			Html::rawElement( 'span', array( 'style' => 'white-space: nowrap' ),
				Xml::label( wfMsg( 'namespace' ), 'namespace' ) . ' ' .
				Xml::namespaceSelector( $this->opts['namespace'], '' )
			) .
			Xml::checkLabel( wfMsg( 'history-show-deleted' ),
				'deletedOnly', 'mw-show-deleted-only', $this->opts['deletedOnly'] ) . '<br />' .
			Xml::tags( 'p', null, Xml::checkLabel( wfMsg( 'sp-contributions-toponly' ),
				'topOnly', 'mw-show-top-only', $this->opts['topOnly'] ) ) .
			( $tagFilter ? Xml::tags( 'p', null, implode( '&#160;', $tagFilter ) ) : '' ) .
			Html::rawElement( 'p', array( 'style' => 'white-space: nowrap' ),
				Xml::dateMenu( $this->opts['year'], $this->opts['month'] ) . ' ' .
				Xml::submitButton( wfMsg( 'sp-contributions-submit' ) )
			) . ' ';
		$explain = wfMessage( 'sp-contributions-explain' );
		if ( $explain->exists() ) {
			$f .= "<p id='mw-sp-contributions-explain'>{$explain}</p>";
		}
		$f .= Xml::closeElement('fieldset' ) .
			Xml::closeElement( 'form' );
		return $f;
	}
}

/**
 * Pager for Special:Contributions
 * @ingroup SpecialPage Pager
 */
class ContribsPager extends ReverseChronologicalPager {
	public $mDefaultDirection = true;
	var $messages, $target;
	var $namespace = '', $mDb;
	var $preventClickjacking = false;

	function __construct( $options ) {
		parent::__construct();

		$msgs = array( 'uctop', 'diff', 'newarticle', 'rollbacklink', 'diff', 'hist', 'rev-delundel', 'pipe-separator' );

		foreach( $msgs as $msg ) {
			$this->messages[$msg] = wfMsgExt( $msg, array( 'escapenoentities' ) );
		}

		$this->target = isset( $options['target'] ) ? $options['target'] : '';
		$this->contribs = isset( $options['contribs'] ) ? $options['contribs'] : 'users';
		$this->namespace = isset( $options['namespace'] ) ? $options['namespace'] : '';
		$this->tagFilter = isset( $options['tagfilter'] ) ? $options['tagfilter'] : false;

		$this->deletedOnly = !empty( $options['deletedOnly'] );
		$this->topOnly = !empty( $options['topOnly'] );

		$year = isset( $options['year'] ) ? $options['year'] : false;
		$month = isset( $options['month'] ) ? $options['month'] : false;
		$this->getDateCond( $year, $month );

		$this->mDb = wfGetDB( DB_SLAVE, 'contributions' );
	}

	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['target'] = $this->target;
		return $query;
	}

	function getQueryInfo() {
		list( $tables, $index, $userCond, $join_cond ) = $this->getUserCond();

		$user = $this->getUser();
		$conds = array_merge( $userCond, $this->getNamespaceCond() );
		// Paranoia: avoid brute force searches (bug 17342)
		if( !$user->isAllowed( 'deletedhistory' ) ) {
			$conds[] = $this->mDb->bitAnd('rev_deleted',Revision::DELETED_USER) . ' = 0';
		} elseif( !$user->isAllowed( 'suppressrevision' ) ) {
			$conds[] = $this->mDb->bitAnd('rev_deleted',Revision::SUPPRESSED_USER) .
				' != ' . Revision::SUPPRESSED_USER;
		}

		# Don't include orphaned revisions
		$join_cond['page'] = Revision::pageJoinCond();
		# Get the current user name for accounts
		$join_cond['user'] = Revision::userJoinCond();

		$queryInfo = array(
			'tables'     => $tables,
			'fields'     => array_merge(
				Revision::selectFields(),
				Revision::selectUserFields(),
				array( 'page_namespace', 'page_title', 'page_is_new',
					'page_latest', 'page_is_redirect', 'page_len' )
			),
			'conds'      => $conds,
			'options'    => array( 'USE INDEX' => array( 'revision' => $index ) ),
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

		wfRunHooks( 'ContribsPager::getQueryInfo', array( &$this, &$queryInfo ) );
		return $queryInfo;
	}

	function getUserCond() {
		$condition = array();
		$join_conds = array();
		$tables = array( 'revision', 'page', 'user' );
		if( $this->contribs == 'newbie' ) {
			$tables[] = 'user_groups';
			$max = $this->mDb->selectField( 'user', 'max(user_id)', false, __METHOD__ );
			$condition[] = 'rev_user >' . (int)($max - $max / 100);
			$condition[] = 'ug_group IS NULL';
			$index = 'user_timestamp';
			# @todo FIXME: Other groups may have 'bot' rights
			$join_conds['user_groups'] = array( 'LEFT JOIN', "ug_user = rev_user AND ug_group = 'bot'" );
		} else {
			if ( IP::isIPAddress( $this->target ) ) {
				$condition['rev_user_text'] = $this->target;
				$index = 'usertext_timestamp';
			} else {
				$condition['rev_user'] = User::idFromName( $this->target );
				$index = 'user_timestamp';
			}
		}
		if( $this->deletedOnly ) {
			$condition[] = "rev_deleted != '0'";
		}
		if( $this->topOnly ) {
			$condition[] = "rev_id = page_latest";
		}
		return array( $tables, $index, $condition, $join_conds );
	}

	function getNamespaceCond() {
		if( $this->namespace !== '' ) {
			return array( 'page_namespace' => (int)$this->namespace );
		} else {
			return array();
		}
	}

	function getIndexField() {
		return 'rev_timestamp';
	}

	function doBatchLookups() {
		$this->mResult->rewind();
		$revIds = array();
		foreach ( $this->mResult as $row ) {
			$revIds[] = $row->rev_parent_id;
		}
		$this->mParentLens = $this->getParentLengths( $revIds );
		$this->mResult->rewind(); // reset

		if ( $this->contribs === 'newbie' ) { // multiple users
			# Do a link batch query
			$this->mResult->seek( 0 );
			$batch = new LinkBatch();
			# Give some pointers to make (last) links
			foreach ( $this->mResult as $row ) {
				$batch->addObj( Title::makeTitleSafe( NS_USER, $row->user_name ) );
				$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->user_name ) );
			}
			$batch->execute();
			$this->mResult->seek( 0 );
		}
	}

	/**
	 * Do a batched query to get the parent revision lengths
	 */
	private function getParentLengths( array $revIds ) {
		$revLens = array();
		if ( !$revIds ) {
			return $revLens; // empty
		}
		wfProfileIn( __METHOD__ );
		$res = $this->getDatabase()->select( 'revision',
			array( 'rev_id', 'rev_len' ),
			array( 'rev_id' => $revIds ),
			__METHOD__ );
		foreach( $res as $row ) {
			$revLens[$row->rev_id] = $row->rev_len;
		}
		wfProfileOut( __METHOD__ );
		return $revLens;
	}

	function getStartBody() {
		return "<ul>\n";
	}

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
	 */
	function formatRow( $row ) {
		wfProfileIn( __METHOD__ );

		$rev = new Revision( $row );
		$classes = array();

		$page = Title::newFromRow( $row );
		$link = Linker::link(
			$page,
			htmlspecialchars( $page->getPrefixedText() ),
			array(),
			$page->isRedirect() ? array( 'redirect' => 'no' ) : array()
		);
		# Mark current revisions
		$topmarktext = '';
		if( $row->rev_id == $row->page_latest ) {
			$topmarktext .= '<span class="mw-uctop">' . $this->messages['uctop'] . '</span>';
			# Add rollback link
			if( !$row->page_is_new && $page->quickUserCan( 'rollback' )
				&& $page->quickUserCan( 'edit' ) )
			{
				$this->preventClickjacking();
				$topmarktext .= ' '.Linker::generateRollback( $rev );
			}
		}
		$user = $this->getUser();
		# Is there a visible previous revision?
		if( $rev->userCan( Revision::DELETED_TEXT, $user ) && $rev->getParentId() !== 0 ) {
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

		if ( isset( $this->mParentLens[$row->rev_parent_id] ) ) {
			$chardiff = ' . . ' . ChangesList::showCharacterDifference(
				$this->mParentLens[$row->rev_parent_id], $row->rev_len ) . ' . . ';
		} else {
			$chardiff = ' ';
		}

		$comment = $this->getLang()->getDirMark() . Linker::revComment( $rev, false, true );
		$date = $this->getLang()->timeanddate( wfTimestamp( TS_MW, $row->rev_timestamp ), true );
		if( $rev->userCan( Revision::DELETED_TEXT, $user ) ) {
			$d = Linker::linkKnown(
				$page,
				htmlspecialchars($date),
				array(),
				array( 'oldid' => intval( $row->rev_id ) )
			);
		} else {
			$d = htmlspecialchars( $date );
		}
		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$d = '<span class="history-deleted">' . $d . '</span>';
		}

		# Show user names for /newbies as there may be different users.
		# Note that we already excluded rows with hidden user names.
		if( $this->contribs == 'newbie' ) {
			$userlink = ' . . ' . Linker::userLink( $rev->getUser(), $rev->getUserText() );
			$userlink .= ' ' . wfMsg( 'parentheses',
				Linker::userTalkLink( $rev->getUser(), $rev->getUserText() ) ) . ' ';
		} else {
			$userlink = '';
		}

		if( $rev->getParentId() === 0 ) {
			$nflag = ChangesList::flag( 'newpage' );
		} else {
			$nflag = '';
		}

		if( $rev->isMinor() ) {
			$mflag = ChangesList::flag( 'minor' );
		} else {
			$mflag = '';
		}

		// Don't show useless link to people who cannot hide revisions
		$canHide = $user->isAllowed( 'deleterevision' );
		if( $canHide || ($rev->getVisibility() && $user->isAllowed('deletedhistory')) ) {
			if( !$rev->userCan( Revision::DELETED_RESTRICTED, $user ) ) {
				$del = Linker::revDeleteLinkDisabled( $canHide ); // revision was hidden from sysops
			} else {
				$query = array(
					'type'   => 'revision',
					'target' => $page->getPrefixedDbkey(),
					'ids'    => $rev->getId()
				);
				$del = Linker::revDeleteLink( $query,
					$rev->isDeleted( Revision::DELETED_RESTRICTED ), $canHide );
			}
			$del .= ' ';
		} else {
			$del = '';
		}

		$diffHistLinks = '(' . $difftext . $this->messages['pipe-separator'] . $histlink . ')';
		$ret = "{$del}{$d} {$diffHistLinks}{$chardiff}{$nflag}{$mflag} {$link}{$userlink} {$comment} {$topmarktext}";

		# Denote if username is redacted for this edit
		if( $rev->isDeleted( Revision::DELETED_USER ) ) {
			$ret .= " <strong>" . wfMsgHtml('rev-deleted-user-contribs') . "</strong>";
		}

		# Tags, if any.
		list($tagSummary, $newClasses) = ChangeTags::formatSummaryRow( $row->ts_tags, 'contributions' );
		$classes = array_merge( $classes, $newClasses );
		$ret .= " $tagSummary";

		// Let extensions add data
		wfRunHooks( 'ContributionsLineEnding', array( &$this, &$ret, $row ) );

		$classes = implode( ' ', $classes );
		$ret = "<li class=\"$classes\">$ret</li>\n";
		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Get the Database object in use
	 *
	 * @return DatabaseBase
	 */
	public function getDatabase() {
		return $this->mDb;
	}

	/**
	 * Overwrite Pager function and return a helpful comment
	 */
	function getSqlComment() {
		if ( $this->namespace || $this->deletedOnly ) {
			return 'contributions page filtered for namespace or RevisionDeleted edits'; // potentially slow, see CR r58153
		} else {
			return 'contributions page unfiltered';
		}
	}

	protected function preventClickjacking() {
		$this->preventClickjacking = true;
	}

	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}
}
