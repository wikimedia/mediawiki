<?php
/**
 * Implements Special:MergeHistory
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
 * Special page allowing users with the appropriate permissions to
 * merge article histories, with some restrictions
 *
 * @ingroup SpecialPage
 */
class SpecialMergeHistory extends SpecialPage {
	var $mAction, $mTarget, $mDest, $mTimestamp, $mTargetID, $mDestID, $mComment;

	/**
	 * @var Title
	 */
	var $mTargetObj, $mDestObj;

	public function __construct() {
		parent::__construct( 'MergeHistory', 'mergehistory' );
	}

	/**
	 * @param $request WebRequest
	 * @return void
	 */
	private function loadRequestParams( $request ) {
		global $wgUser;

		$this->mAction = $request->getVal( 'action' );
		$this->mTarget = $request->getVal( 'target' );
		$this->mDest = $request->getVal( 'dest' );
		$this->mSubmitted = $request->getBool( 'submitted' );

		$this->mTargetID = intval( $request->getVal( 'targetID' ) );
		$this->mDestID = intval( $request->getVal( 'destID' ) );
		$this->mTimestamp = $request->getVal( 'mergepoint' );
		if( !preg_match( '/[0-9]{14}/', $this->mTimestamp ) ) {
			$this->mTimestamp = '';
		}
		$this->mComment = $request->getText( 'wpComment' );

		$this->mMerge = $request->wasPosted() && $wgUser->matchEditToken( $request->getVal( 'wpEditToken' ) );
		// target page
		if( $this->mSubmitted ) {
			$this->mTargetObj = Title::newFromURL( $this->mTarget );
			$this->mDestObj = Title::newFromURL( $this->mDest );
		} else {
			$this->mTargetObj = null;
			$this->mDestObj = null;
		}
		$this->preCacheMessages();
	}

	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	function preCacheMessages() {
		// Precache various messages
		if( !isset( $this->message ) ) {
			$this->message['last'] = wfMsgExt( 'last', array( 'escape' ) );
		}
	}

	public function execute( $par ) {
		global $wgOut, $wgRequest, $wgUser;

		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		if( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		}

		$this->loadRequestParams( $wgRequest );

		$this->setHeaders();
		$this->outputHeader();

		if( $this->mTargetID && $this->mDestID && $this->mAction == 'submit' && $this->mMerge ) {
			return $this->merge();
		}

		if ( !$this->mSubmitted ) {
			$this->showMergeForm();
			return;
		}

		$errors = array();
		if ( !$this->mTargetObj instanceof Title ) {
			$errors[] = wfMsgExt( 'mergehistory-invalid-source', array( 'parse' ) );
		} elseif( !$this->mTargetObj->exists() ) {
			$errors[] = wfMsgExt( 'mergehistory-no-source', array( 'parse' ),
				wfEscapeWikiText( $this->mTargetObj->getPrefixedText() )
			);
		}

		if ( !$this->mDestObj instanceof Title ) {
			$errors[] = wfMsgExt( 'mergehistory-invalid-destination', array( 'parse' ) );
		} elseif( !$this->mDestObj->exists() ) {
			$errors[] = wfMsgExt( 'mergehistory-no-destination', array( 'parse' ),
				wfEscapeWikiText( $this->mDestObj->getPrefixedText() )
			);
		}

		if ( $this->mTargetObj && $this->mDestObj && $this->mTargetObj->equals( $this->mDestObj ) ) {
			$errors[] = wfMsgExt( 'mergehistory-same-destination', array( 'parse' ) );
		}

		if ( count( $errors ) ) {
			$this->showMergeForm();
			$wgOut->addHTML( implode( "\n", $errors ) );
		} else {
			$this->showHistory();
		}

	}

	function showMergeForm() {
		global $wgOut, $wgScript;

		$wgOut->addWikiMsg( 'mergehistory-header' );

		$wgOut->addHTML(
			Xml::openElement( 'form', array(
				'method' => 'get',
				'action' => $wgScript ) ) .
			'<fieldset>' .
			Xml::element( 'legend', array(),
				wfMsg( 'mergehistory-box' ) ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedDbKey() ) .
			Html::hidden( 'submitted', '1' ) .
			Html::hidden( 'mergepoint', $this->mTimestamp ) .
			Xml::openElement( 'table' ) .
			'<tr>
				<td>' . Xml::label( wfMsg( 'mergehistory-from' ), 'target' ) . '</td>
				<td>' . Xml::input( 'target', 30, $this->mTarget, array( 'id' => 'target' ) ) . '</td>
			</tr><tr>
				<td>' . Xml::label( wfMsg( 'mergehistory-into' ), 'dest' ) . '</td>
				<td>' . Xml::input( 'dest', 30, $this->mDest, array( 'id' => 'dest' ) ) . '</td>
			</tr><tr><td>' .
			Xml::submitButton( wfMsg( 'mergehistory-go' ) ) .
			'</td></tr>' .
			Xml::closeElement( 'table' ) .
			'</fieldset>' .
			'</form>'
		);
	}

	private function showHistory() {
		global $wgUser, $wgOut;

		$this->sk = $this->getSkin();

		$wgOut->setPageTitle( wfMsg( 'mergehistory' ) );

		$this->showMergeForm();

		# List all stored revisions
		$revisions = new MergeHistoryPager(
			$this, array(), $this->mTargetObj, $this->mDestObj
		);
		$haveRevisions = $revisions && $revisions->getNumRows() > 0;

		$titleObj = $this->getTitle();
		$action = $titleObj->getLocalURL( array( 'action' => 'submit' ) );
		# Start the form here
		$top = Xml::openElement(
			'form',
			array(
				'method' => 'post',
				'action' => $action,
				'id' => 'merge'
			)
		);
		$wgOut->addHTML( $top );

		if( $haveRevisions ) {
			# Format the user-visible controls (comment field, submission button)
			# in a nice little table
			$table =
				Xml::openElement( 'fieldset' ) .
				wfMsgExt( 'mergehistory-merge', array( 'parseinline' ),
					$this->mTargetObj->getPrefixedText(), $this->mDestObj->getPrefixedText() ) .
				Xml::openElement( 'table', array( 'id' => 'mw-mergehistory-table' ) ) .
					'<tr>
						<td class="mw-label">' .
							Xml::label( wfMsg( 'mergehistory-reason' ), 'wpComment' ) .
						'</td>
						<td class="mw-input">' .
							Xml::input( 'wpComment', 50, $this->mComment, array( 'id' => 'wpComment' ) ) .
						'</td>
					</tr>
					<tr>
						<td>&#160;</td>
						<td class="mw-submit">' .
							Xml::submitButton( wfMsg( 'mergehistory-submit' ), array( 'name' => 'merge', 'id' => 'mw-merge-submit' ) ) .
						'</td>
					</tr>' .
				Xml::closeElement( 'table' ) .
				Xml::closeElement( 'fieldset' );

			$wgOut->addHTML( $table );
		}

		$wgOut->addHTML(
			'<h2 id="mw-mergehistory">' .
			wfMsgHtml( 'mergehistory-list' ) . "</h2>\n"
		);

		if( $haveRevisions ) {
			$wgOut->addHTML( $revisions->getNavigationBar() );
			$wgOut->addHTML( '<ul>' );
			$wgOut->addHTML( $revisions->getBody() );
			$wgOut->addHTML( '</ul>' );
			$wgOut->addHTML( $revisions->getNavigationBar() );
		} else {
			$wgOut->addWikiMsg( 'mergehistory-empty' );
		}

		# Show relevant lines from the deletion log:
		$wgOut->addHTML( '<h2>' . htmlspecialchars( LogPage::logName( 'merge' ) ) . "</h2>\n" );
		LogEventsList::showLogExtract( $wgOut, 'merge', $this->mTargetObj->getPrefixedText() );

		# When we submit, go by page ID to avoid some nasty but unlikely collisions.
		# Such would happen if a page was renamed after the form loaded, but before submit
		$misc = Html::hidden( 'targetID', $this->mTargetObj->getArticleID() );
		$misc .= Html::hidden( 'destID', $this->mDestObj->getArticleID() );
		$misc .= Html::hidden( 'target', $this->mTarget );
		$misc .= Html::hidden( 'dest', $this->mDest );
		$misc .= Html::hidden( 'wpEditToken', $wgUser->editToken() );
		$misc .= Xml::closeElement( 'form' );
		$wgOut->addHTML( $misc );

		return true;
	}

	function formatRevisionRow( $row ) {
		global $wgLang;

		$rev = new Revision( $row );

		$stxt = '';
		$last = $this->message['last'];

		$ts = wfTimestamp( TS_MW, $row->rev_timestamp );
		$checkBox = Xml::radio( 'mergepoint', $ts, false );

		$pageLink = $this->sk->linkKnown(
			$rev->getTitle(),
			htmlspecialchars( $wgLang->timeanddate( $ts ) ),
			array(),
			array( 'oldid' => $rev->getId() )
		);
		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$pageLink = '<span class="history-deleted">' . $pageLink . '</span>';
		}

		# Last link
		if( !$rev->userCan( Revision::DELETED_TEXT ) ) {
			$last = $this->message['last'];
		} elseif( isset( $this->prevId[$row->rev_id] ) ) {
			$last = $this->sk->linkKnown(
				$rev->getTitle(),
				$this->message['last'],
				array(),
				array(
					'diff' => $row->rev_id,
					'oldid' => $this->prevId[$row->rev_id]
				)
			);
		}

		$userLink = $this->sk->revUserTools( $rev );

		$size = $row->rev_len;
		if( !is_null( $size ) ) {
			$stxt = $this->sk->formatRevisionSize( $size );
		}
		$comment = $this->sk->revComment( $rev );

		return "<li>$checkBox ($last) $pageLink . . $userLink $stxt $comment</li>";
	}

	/**
	 * Fetch revision text link if it's available to all users
	 * @return string
	 */
	function getPageLink( $row, $titleObj, $ts, $target ) {
		global $wgLang;

		if( !$this->userCan( $row, Revision::DELETED_TEXT ) ) {
			return '<span class="history-deleted">' .
				$wgLang->timeanddate( $ts, true ) . '</span>';
		} else {
			$link = $this->sk->linkKnown(
				$titleObj,
				$wgLang->timeanddate( $ts, true ),
				array(),
				array(
					'target' => $target,
					'timestamp' => $ts
				)
			);
			if( $this->isDeleted( $row, Revision::DELETED_TEXT ) ) {
				$link = '<span class="history-deleted">' . $link . '</span>';
			}
			return $link;
		}
	}

	function merge() {
		global $wgOut;
		# Get the titles directly from the IDs, in case the target page params
		# were spoofed. The queries are done based on the IDs, so it's best to
		# keep it consistent...
		$targetTitle = Title::newFromID( $this->mTargetID );
		$destTitle = Title::newFromID( $this->mDestID );
		if( is_null( $targetTitle ) || is_null( $destTitle ) ) {
			return false; // validate these
		}
		if( $targetTitle->getArticleId() == $destTitle->getArticleId() ) {
			return false;
		}
		# Verify that this timestamp is valid
		# Must be older than the destination page
		$dbw = wfGetDB( DB_MASTER );
		# Get timestamp into DB format
		$this->mTimestamp = $this->mTimestamp ? $dbw->timestamp( $this->mTimestamp ) : '';
		# Max timestamp should be min of destination page
		$maxtimestamp = $dbw->selectField(
			'revision',
			'MIN(rev_timestamp)',
			array( 'rev_page' => $this->mDestID ),
			__METHOD__
		);
		# Destination page must exist with revisions
		if( !$maxtimestamp ) {
			$wgOut->addWikiMsg( 'mergehistory-fail' );
			return false;
		}
		# Get the latest timestamp of the source
		$lasttimestamp = $dbw->selectField(
			array( 'page', 'revision' ),
			'rev_timestamp',
			array( 'page_id' => $this->mTargetID, 'page_latest = rev_id' ),
			__METHOD__
		);
		# $this->mTimestamp must be older than $maxtimestamp
		if( $this->mTimestamp >= $maxtimestamp ) {
			$wgOut->addWikiMsg( 'mergehistory-fail' );
			return false;
		}
		# Update the revisions
		if( $this->mTimestamp ) {
			$timewhere = "rev_timestamp <= {$this->mTimestamp}";
			$timestampLimit = wfTimestamp( TS_MW, $this->mTimestamp );
		} else {
			$timewhere = "rev_timestamp <= {$maxtimestamp}";
			$timestampLimit = wfTimestamp( TS_MW, $lasttimestamp );
		}
		# Do the moving...
		$dbw->update(
			'revision',
			array( 'rev_page' => $this->mDestID ),
			array( 'rev_page' => $this->mTargetID, $timewhere ),
			__METHOD__
		);

		$count = $dbw->affectedRows();
		# Make the source page a redirect if no revisions are left
		$haveRevisions = $dbw->selectField(
			'revision',
			'rev_timestamp',
			array( 'rev_page' => $this->mTargetID  ),
			__METHOD__,
			array( 'FOR UPDATE' )
		);
		if( !$haveRevisions ) {
			if( $this->mComment ) {
				$comment = wfMsgForContent(
					'mergehistory-comment',
					$targetTitle->getPrefixedText(),
					$destTitle->getPrefixedText(),
					$this->mComment
				);
			} else {
				$comment = wfMsgForContent(
					'mergehistory-autocomment',
					$targetTitle->getPrefixedText(),
					$destTitle->getPrefixedText()
				);
			}
			$mwRedir = MagicWord::get( 'redirect' );
			$redirectText = $mwRedir->getSynonym( 0 ) . ' [[' . $destTitle->getPrefixedText() . "]]\n";
			$redirectArticle = new Article( $targetTitle );
			$redirectRevision = new Revision( array(
				'page'    => $this->mTargetID,
				'comment' => $comment,
				'text'    => $redirectText ) );
			$redirectRevision->insertOn( $dbw );
			$redirectArticle->updateRevisionOn( $dbw, $redirectRevision );

			# Now, we record the link from the redirect to the new title.
			# It should have no other outgoing links...
			$dbw->delete( 'pagelinks', array( 'pl_from' => $this->mDestID ), __METHOD__ );
			$dbw->insert( 'pagelinks',
				array(
					'pl_from'      => $this->mDestID,
					'pl_namespace' => $destTitle->getNamespace(),
					'pl_title'     => $destTitle->getDBkey() ),
				__METHOD__
			);
		} else {
			$targetTitle->invalidateCache(); // update histories
		}
		$destTitle->invalidateCache(); // update histories
		# Check if this did anything
		if( !$count ) {
			$wgOut->addWikiMsg( 'mergehistory-fail' );
			return false;
		}
		# Update our logs
		$log = new LogPage( 'merge' );
		$log->addEntry(
			'merge', $targetTitle, $this->mComment,
			array( $destTitle->getPrefixedText(), $timestampLimit )
		);

		$wgOut->addHTML(
			wfMsgExt( 'mergehistory-success', array('parseinline'),
			$targetTitle->getPrefixedText(), $destTitle->getPrefixedText(), $count ) );

		wfRunHooks( 'ArticleMergeComplete', array( $targetTitle, $destTitle ) );

		return true;
	}
}

class MergeHistoryPager extends ReverseChronologicalPager {
	public $mForm, $mConds;

	function __construct( $form, $conds = array(), $source, $dest ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->title = $source;
		$this->articleID = $source->getArticleID();

		$dbr = wfGetDB( DB_SLAVE );
		$maxtimestamp = $dbr->selectField(
			'revision',
			'MIN(rev_timestamp)',
			array( 'rev_page' => $dest->getArticleID() ),
			__METHOD__
		);
		$this->maxTimestamp = $maxtimestamp;

		parent::__construct();
	}

	function getTitle() {
		return SpecialPage::getTitleFor( 'Contributions' );
	}

	function getStartBody() {
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		$this->mResult->seek( 0 );
		$batch = new LinkBatch();
		# Give some pointers to make (last) links
		$this->mForm->prevId = array();
		foreach ( $this->mResult as $row ) {
			$batch->addObj( Title::makeTitleSafe( NS_USER, $row->rev_user_text ) );
			$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->rev_user_text ) );

			$rev_id = isset( $rev_id ) ? $rev_id : $row->rev_id;
			if( $rev_id > $row->rev_id ) {
				$this->mForm->prevId[$rev_id] = $row->rev_id;
			} elseif( $rev_id < $row->rev_id ) {
				$this->mForm->prevId[$row->rev_id] = $rev_id;
			}

			$rev_id = $row->rev_id;
		}

		$batch->execute();
		$this->mResult->seek( 0 );

		wfProfileOut( __METHOD__ );
		return '';
	}

	function formatRow( $row ) {
		return $this->mForm->formatRevisionRow( $row );
	}

	function getQueryInfo() {
		$conds = $this->mConds;
		$conds['rev_page'] = $this->articleID;
		$conds[] = 'page_id = rev_page';
		$conds[] = "rev_timestamp < {$this->maxTimestamp}";
		return array(
			'tables' => array( 'revision', 'page' ),
			'fields' => array(
				'rev_minor_edit', 'rev_timestamp', 'rev_user', 'rev_user_text',
				'rev_comment', 'rev_id', 'rev_page', 'rev_parent_id',
				'rev_text_id', 'rev_len', 'rev_deleted'
			),
			'conds' => $conds
		);
	}

	function getIndexField() {
		return 'rev_timestamp';
	}
}
