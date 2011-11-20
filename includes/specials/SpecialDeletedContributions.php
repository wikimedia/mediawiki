<?php
/**
 * Implements Special:DeletedContributions
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
 * Implements Special:DeletedContributions to display archived revisions
 * @ingroup SpecialPage
 */

class DeletedContribsPager extends IndexPager {
	public $mDefaultDirection = true;
	var $messages, $target;
	var $namespace = '', $mDb;

	function __construct( $target, $namespace = false ) {
		parent::__construct();
		$msgs = array( 'deletionlog', 'undeleteviewlink', 'diff' );
		foreach( $msgs as $msg ) {
			$this->messages[$msg] = wfMsgExt( $msg, array( 'escapenoentities') );
		}
		$this->target = $target;
		$this->namespace = $namespace;
		$this->mDb = wfGetDB( DB_SLAVE, 'contributions' );
	}

	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['target'] = $this->target;
		return $query;
	}

	function getQueryInfo() {
		list( $index, $userCond ) = $this->getUserCond();
		$conds = array_merge( $userCond, $this->getNamespaceCond() );
		$user = $this->getUser();
		// Paranoia: avoid brute force searches (bug 17792)
		if( !$user->isAllowed( 'deletedhistory' ) ) {
			$conds[] = $this->mDb->bitAnd('ar_deleted',Revision::DELETED_USER) . ' = 0';
		} elseif( !$user->isAllowed( 'suppressrevision' ) ) {
			$conds[] = $this->mDb->bitAnd('ar_deleted',Revision::SUPPRESSED_USER) .
				' != ' . Revision::SUPPRESSED_USER;
		}
		return array(
			'tables' => array( 'archive' ),
			'fields' => array(
				'ar_rev_id', 'ar_namespace', 'ar_title', 'ar_timestamp', 'ar_comment', 'ar_minor_edit',
				'ar_user', 'ar_user_text', 'ar_deleted'
			),
			'conds' => $conds,
			'options' => array( 'USE INDEX' => $index )
		);
	}

	function getUserCond() {
		$condition = array();

		$condition['ar_user_text'] = $this->target;
		$index = 'usertext_timestamp';

		return array( $index, $condition );
	}

	function getIndexField() {
		return 'ar_timestamp';
	}

	function getStartBody() {
		return "<ul>\n";
	}

	function getEndBody() {
		return "</ul>\n";
	}

	function getNavigationBar() {
		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}
		$lang = $this->getLang();
		$fmtLimit = $lang->formatNum( $this->mLimit );
		$linkTexts = array(
			'prev' => wfMsgExt( 'pager-newer-n', array( 'escape', 'parsemag' ), $fmtLimit ),
			'next' => wfMsgExt( 'pager-older-n', array( 'escape', 'parsemag' ), $fmtLimit ),
			'first' => wfMsgHtml( 'histlast' ),
			'last' => wfMsgHtml( 'histfirst' )
		);

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = $lang->pipeList( $limitLinks );

		$this->mNavigationBar = "(" . $lang->pipeList( array( $pagingLinks['first'], $pagingLinks['last'] ) ) . ") " .
			wfMsgExt( 'viewprevnext', array( 'parsemag', 'escape', 'replaceafter' ), $pagingLinks['prev'], $pagingLinks['next'], $limits );
		return $this->mNavigationBar;
	}

	function getNamespaceCond() {
		if ( $this->namespace !== '' ) {
			return array( 'ar_namespace' => (int)$this->namespace );
		} else {
			return array();
		}
	}

	/**
	 * Generates each row in the contributions list.
	 *
	 * Contributions which are marked "top" are currently on top of the history.
	 * For these contributions, a [rollback] link is shown for users with sysop
	 * privileges. The rollback link restores the most recent version that was not
	 * written by the target user.
	 *
	 * @todo This would probably look a lot nicer in a table.
	 */
	function formatRow( $row ) {
		wfProfileIn( __METHOD__ );

		$rev = new Revision( array(
				'id'         => $row->ar_rev_id,
				'comment'    => $row->ar_comment,
				'user'       => $row->ar_user,
				'user_text'  => $row->ar_user_text,
				'timestamp'  => $row->ar_timestamp,
				'minor_edit' => $row->ar_minor_edit,
				'deleted'    => $row->ar_deleted,
				) );

		$page = Title::makeTitle( $row->ar_namespace, $row->ar_title );

		$undelete = SpecialPage::getTitleFor( 'Undelete' );

		$logs = SpecialPage::getTitleFor( 'Log' );
		$dellog = Linker::linkKnown(
			$logs,
			$this->messages['deletionlog'],
			array(),
			array(
				'type' => 'delete',
				'page' => $page->getPrefixedText()
			)
		);

		$reviewlink = Linker::linkKnown(
			SpecialPage::getTitleFor( 'Undelete', $page->getPrefixedDBkey() ),
			$this->messages['undeleteviewlink']
		);

		$user = $this->getUser();

		if( $user->isAllowed('deletedtext') ) {
			$last = Linker::linkKnown(
				$undelete,
				$this->messages['diff'],
				array(),
				array(
					'target' => $page->getPrefixedText(),
					'timestamp' => $rev->getTimestamp(),
					'diff' => 'prev'
				)
			);
		} else {
			$last = $this->messages['diff'];
		}

		$comment = Linker::revComment( $rev );
		$date = htmlspecialchars( $this->getLang()->timeanddate( $rev->getTimestamp(), true ) );

		if( !$user->isAllowed( 'undelete' ) || !$rev->userCan( Revision::DELETED_TEXT, $user ) ) {
			$link = $date; // unusable link
		} else {
			$link = Linker::linkKnown(
				$undelete,
				$date,
				array(),
				array(
					'target' => $page->getPrefixedText(),
					'timestamp' => $rev->getTimestamp()
				)
			);
		}
		// Style deleted items
		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}

		$pagelink = Linker::link( $page );

		if( $rev->isMinor() ) {
			$mflag = ChangesList::flag( 'minor' );
		} else {
			$mflag = '';
		}

		// Revision delete link
		$del = Linker::getRevDeleteLink( $user, $rev, $page );
		if ( $del ) $del .= ' ';

		$tools = Html::rawElement(
			'span',
			array( 'class' => 'mw-deletedcontribs-tools' ),
			wfMsg( 'parentheses', $this->getLang()->pipeList( array( $last, $dellog, $reviewlink ) ) )
		);

		$ret = "{$del}{$link} {$tools} . . {$mflag} {$pagelink} {$comment}";

		# Denote if username is redacted for this edit
		if( $rev->isDeleted( Revision::DELETED_USER ) ) {
			$ret .= " <strong>" . wfMsgHtml('rev-deleted-user-contribs') . "</strong>";
		}

		$ret = Html::rawElement( 'li', array(), $ret ) . "\n";

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Get the Database object in use
	 *
	 * @return Database
	 */
	public function getDatabase() {
		return $this->mDb;
	}
}

class DeletedContributionsPage extends SpecialPage {
	function __construct() {
		parent::__construct( 'DeletedContributions', 'deletedhistory',
		/*listed*/ true, /*function*/ false, /*file*/ false );
	}

	/**
	 * Special page "deleted user contributions".
	 * Shows a list of the deleted contributions of a user.
	 *
	 * @return	none
	 * @param	$par	String: (optional) user name of the user for which to show the contributions
	 */
	function execute( $par ) {
		global $wgQueryPageDefaultLimit;
		$this->setHeaders();

		$user = $this->getUser();

		if ( !$this->userCanExecute( $user ) ) {
			$this->displayRestrictionError();
			return;
		}

		$request = $this->getRequest();
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'deletedcontributions-title' ) );

		$options = array();

		if ( $par !== null ) {
			$target = $par;
		} else {
			$target = $request->getVal( 'target' );
		}

		if ( !strlen( $target ) ) {
			$out->addHTML( $this->getForm( '' ) );
			return;
		}

		$options['limit'] = $request->getInt( 'limit', $wgQueryPageDefaultLimit );
		$options['target'] = $target;

		$userObj = User::newFromName( $target );
		if ( !$userObj ) {
			$out->addHTML( $this->getForm( '' ) );
			return;
		}
		$nt = $userObj->getUserPage();
		$id = $userObj->getID();

		$target = $userObj->getName();
		$out->addSubtitle( $this->getSubTitle( $userObj ) );

		if ( ( $ns = $request->getVal( 'namespace', null ) ) !== null && $ns !== '' ) {
			$options['namespace'] = intval( $ns );
		} else {
			$options['namespace'] = '';
		}

		$out->addHTML( $this->getForm( $options ) );

		$pager = new DeletedContribsPager( $target, $options['namespace'] );
		if ( !$pager->getNumRows() ) {
			$out->addWikiMsg( 'nocontribs' );
			return;
		}

		# Show a message about slave lag, if applicable
		$lag = wfGetLB()->safeGetLag( $pager->getDatabase() );
		if( $lag > 0 )
			$out->showLagWarning( $lag );

		$out->addHTML(
			'<p>' . $pager->getNavigationBar() . '</p>' .
			$pager->getBody() .
			'<p>' . $pager->getNavigationBar() . '</p>' );

		# If there were contributions, and it was a valid user or IP, show
		# the appropriate "footer" message - WHOIS tools, etc.
		if( $target != 'newbies' ) {
			$message = IP::isIPAddress( $target )
				? 'sp-contributions-footer-anon'
				: 'sp-contributions-footer';

			if( !wfMessage( $message )->isDisabled() ) {
				$out->wrapWikiMsg( "<div class='mw-contributions-footer'>\n$1\n</div>", array( $message, $target ) );
			}
		}
	}

	/**
	 * Generates the subheading with links
	 * @param $userObj User object for the target
	 * @return String: appropriately-escaped HTML to be output literally
	 * @todo FIXME: Almost the same as contributionsSub in SpecialContributions.php. Could be combined.
	 */
	function getSubTitle( $userObj ) {
		if ( $userObj->isAnon() ) {
			$user = htmlspecialchars( $userObj->getName() );
		} else {
			$user = Linker::link( $userObj->getPage(), htmlspecialchars( $userObj->getText() ) );
		}
		$nt = $userObj->getUserPage();
		$id = $userObj->getID();
		$talk = $nt->getTalkPage();
		if( $talk ) {
			# Talk page link
			$tools[] = Linker::link( $talk, wfMsgHtml( 'sp-contributions-talk' ) );
			if( ( $id !== null ) || ( $id === null && IP::isIPAddress( $nt->getText() ) ) ) {
				if( $this->getUser()->isAllowed( 'block' ) ) { # Block / Change block / Unblock links
					if ( $userObj->isBlocked() ) {
						$tools[] = Linker::linkKnown( # Change block link
							SpecialPage::getTitleFor( 'Block', $nt->getDBkey() ),
							wfMsgHtml( 'change-blocklink' )
						);
						$tools[] = Linker::linkKnown( # Unblock link
							SpecialPage::getTitleFor( 'BlockList' ),
							wfMsgHtml( 'unblocklink' ),
							array(),
							array(
								'action' => 'unblock',
								'ip' => $nt->getDBkey()
							)
						);
					}
					else { # User is not blocked
						$tools[] = Linker::linkKnown( # Block link
							SpecialPage::getTitleFor( 'Block', $nt->getDBkey() ),
							wfMsgHtml( 'blocklink' )
						);
					}
				}
				# Block log link
				$tools[] = Linker::linkKnown(
					SpecialPage::getTitleFor( 'Log' ),
					wfMsgHtml( 'sp-contributions-blocklog' ),
					array(),
					array(
						'type' => 'block',
						'page' => $nt->getPrefixedText()
					)
				);
			}
			# Other logs link
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Log' ),
				wfMsgHtml( 'sp-contributions-logs' ),
				array(),
				array( 'user' => $nt->getText() )
			);
			# Link to contributions
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Contributions', $nt->getDBkey() ),
				wfMsgHtml( 'sp-deletedcontributions-contribs' )
			);

			# Add a link to change user rights for privileged users
			$userrightsPage = new UserrightsPage();
			if( $id !== null && $userrightsPage->userCanChangeRights( User::newFromId( $id ) ) ) {
				$tools[] = Linker::linkKnown(
					SpecialPage::getTitleFor( 'Userrights', $nt->getDBkey() ),
					wfMsgHtml( 'sp-contributions-userrights' )
				);
			}

			wfRunHooks( 'ContributionsToolLinks', array( $id, $nt, &$tools ) );

			$links = $this->getLang()->pipeList( $tools );

			// Show a note if the user is blocked and display the last block log entry.
			if ( $userObj->isBlocked() ) {
				$out = $this->getOutput(); // LogEventsList::showLogExtract() wants the first parameter by ref
				LogEventsList::showLogExtract(
					$out,
					'block',
					$nt,
					'',
					array(
						'lim' => 1,
						'showIfEmpty' => false,
						'msgKey' => array(
							'sp-contributions-blocked-notice',
							$nt->getText() # Support GENDER in 'sp-contributions-blocked-notice'
						),
						'offset' => '' # don't use $this->getRequest() parameter offset
					)
				);
			}
		}

		// Old message 'contribsub' had one parameter, but that doesn't work for
		// languages that want to put the "for" bit right after $user but before
		// $links.  If 'contribsub' is around, use it for reverse compatibility,
		// otherwise use 'contribsub2'.
		$oldMsg = $this->msg( 'contribsub' );
		if ( $oldMsg->exists() ) {
			return $oldMsg->rawParams( "$user ($links)" );
		} else {
			return $this->msg( 'contribsub2' )->rawParams( $user, $links );
		}
	}

	/**
	 * Generates the namespace selector form with hidden attributes.
	 * @param $options Array: the options to be included.
	 */
	function getForm( $options ) {
		global $wgScript;

		$options['title'] = $this->getTitle()->getPrefixedText();
		if ( !isset( $options['target'] ) ) {
			$options['target'] = '';
		} else {
			$options['target'] = str_replace( '_' , ' ' , $options['target'] );
		}

		if ( !isset( $options['namespace'] ) ) {
			$options['namespace'] = '';
		}

		if ( !isset( $options['contribs'] ) ) {
			$options['contribs'] = 'user';
		}

		if ( $options['contribs'] == 'newbie' ) {
			$options['target'] = '';
		}

		$f = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) );

		foreach ( $options as $name => $value ) {
			if ( in_array( $name, array( 'namespace', 'target', 'contribs' ) ) ) {
				continue;
			}
			$f .= "\t" . Html::hidden( $name, $value ) . "\n";
		}

		$f .=  Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', array(), wfMsg( 'sp-contributions-search' ) ) .
			Xml::tags( 'label', array( 'for' => 'target' ), wfMsgExt( 'sp-contributions-username', 'parseinline' ) ) . ' ' .
			Html::input( 'target', $options['target'], 'text', array(
				'size' => '20',
				'required' => ''
			) + ( $options['target'] ? array() : array( 'autofocus' ) ) ) . ' '.
			Xml::label( wfMsg( 'namespace' ), 'namespace' ) . ' ' .
			Xml::namespaceSelector( $options['namespace'], '' ) . ' ' .
			Xml::submitButton( wfMsg( 'sp-contributions-submit' ) ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );
		return $f;
	}
}
