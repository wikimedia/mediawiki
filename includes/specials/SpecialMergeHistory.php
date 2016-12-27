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
	/** @var string */
	protected $mAction;

	/** @var string */
	protected $mTarget;

	/** @var string */
	protected $mDest;

	/** @var string */
	protected $mTimestamp;

	/** @var int */
	protected $mTargetID;

	/** @var int */
	protected $mDestID;

	/** @var string */
	protected $mComment;

	/** @var bool Was posted? */
	protected $mMerge;

	/** @var bool Was submitted? */
	protected $mSubmitted;

	/** @var Title */
	protected $mTargetObj;

	/** @var Title */
	protected $mDestObj;

	/** @var int[] */
	public $prevId;

	public function __construct() {
		parent::__construct( 'MergeHistory', 'mergehistory' );
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * @return void
	 */
	private function loadRequestParams() {
		$request = $this->getRequest();
		$this->mAction = $request->getVal( 'action' );
		$this->mTarget = $request->getVal( 'target' );
		$this->mDest = $request->getVal( 'dest' );
		$this->mSubmitted = $request->getBool( 'submitted' );

		$this->mTargetID = intval( $request->getVal( 'targetID' ) );
		$this->mDestID = intval( $request->getVal( 'destID' ) );
		$this->mTimestamp = $request->getVal( 'mergepoint' );
		if ( !preg_match( '/[0-9]{14}/', $this->mTimestamp ) ) {
			$this->mTimestamp = '';
		}
		$this->mComment = $request->getText( 'wpComment' );

		$this->mMerge = $request->wasPosted()
			&& $this->getUser()->matchEditToken( $request->getVal( 'wpEditToken' ) );

		// target page
		if ( $this->mSubmitted ) {
			$this->mTargetObj = Title::newFromText( $this->mTarget );
			$this->mDestObj = Title::newFromText( $this->mDest );
		} else {
			$this->mTargetObj = null;
			$this->mDestObj = null;
		}
	}

	public function execute( $par ) {
		$this->useTransactionalTimeLimit();

		$this->checkPermissions();
		$this->checkReadOnly();

		$this->loadRequestParams();

		$this->setHeaders();
		$this->outputHeader();

		if ( $this->mTargetID && $this->mDestID && $this->mAction == 'submit' && $this->mMerge ) {
			$this->merge();

			return;
		}

		if ( !$this->mSubmitted ) {
			$this->showMergeForm();

			return;
		}

		$errors = [];
		if ( !$this->mTargetObj instanceof Title ) {
			$errors[] = $this->msg( 'mergehistory-invalid-source' )->parseAsBlock();
		} elseif ( !$this->mTargetObj->exists() ) {
			$errors[] = $this->msg( 'mergehistory-no-source',
				wfEscapeWikiText( $this->mTargetObj->getPrefixedText() )
			)->parseAsBlock();
		}

		if ( !$this->mDestObj instanceof Title ) {
			$errors[] = $this->msg( 'mergehistory-invalid-destination' )->parseAsBlock();
		} elseif ( !$this->mDestObj->exists() ) {
			$errors[] = $this->msg( 'mergehistory-no-destination',
				wfEscapeWikiText( $this->mDestObj->getPrefixedText() )
			)->parseAsBlock();
		}

		if ( $this->mTargetObj && $this->mDestObj && $this->mTargetObj->equals( $this->mDestObj ) ) {
			$errors[] = $this->msg( 'mergehistory-same-destination' )->parseAsBlock();
		}

		if ( count( $errors ) ) {
			$this->showMergeForm();
			$this->getOutput()->addHTML( implode( "\n", $errors ) );
		} else {
			$this->showHistory();
		}
	}

	function showMergeForm() {
		$out = $this->getOutput();
		$out->addWikiMsg( 'mergehistory-header' );

		$out->addHTML(
			Xml::openElement( 'form', [
				'method' => 'get',
				'action' => wfScript() ] ) .
				'<fieldset>' .
				Xml::element( 'legend', [],
					$this->msg( 'mergehistory-box' )->text() ) .
				Html::hidden( 'title', $this->getPageTitle()->getPrefixedDBkey() ) .
				Html::hidden( 'submitted', '1' ) .
				Html::hidden( 'mergepoint', $this->mTimestamp ) .
				Xml::openElement( 'table' ) .
				'<tr>
				<td>' . Xml::label( $this->msg( 'mergehistory-from' )->text(), 'target' ) . '</td>
				<td>' . Xml::input( 'target', 30, $this->mTarget, [ 'id' => 'target' ] ) . '</td>
			</tr><tr>
				<td>' . Xml::label( $this->msg( 'mergehistory-into' )->text(), 'dest' ) . '</td>
				<td>' . Xml::input( 'dest', 30, $this->mDest, [ 'id' => 'dest' ] ) . '</td>
			</tr><tr><td>' .
				Xml::submitButton( $this->msg( 'mergehistory-go' )->text() ) .
				'</td></tr>' .
				Xml::closeElement( 'table' ) .
				'</fieldset>' .
				'</form>'
		);

		$this->addHelpLink( 'Help:Merge history' );
	}

	private function showHistory() {
		$this->showMergeForm();

		# List all stored revisions
		$revisions = new MergeHistoryPager(
			$this, [], $this->mTargetObj, $this->mDestObj
		);
		$haveRevisions = $revisions && $revisions->getNumRows() > 0;

		$out = $this->getOutput();
		$titleObj = $this->getPageTitle();
		$action = $titleObj->getLocalURL( [ 'action' => 'submit' ] );
		# Start the form here
		$top = Xml::openElement(
			'form',
			[
				'method' => 'post',
				'action' => $action,
				'id' => 'merge'
			]
		);
		$out->addHTML( $top );

		if ( $haveRevisions ) {
			# Format the user-visible controls (comment field, submission button)
			# in a nice little table
			$table =
				Xml::openElement( 'fieldset' ) .
					$this->msg( 'mergehistory-merge', $this->mTargetObj->getPrefixedText(),
						$this->mDestObj->getPrefixedText() )->parse() .
					Xml::openElement( 'table', [ 'id' => 'mw-mergehistory-table' ] ) .
					'<tr>
						<td class="mw-label">' .
					Xml::label( $this->msg( 'mergehistory-reason' )->text(), 'wpComment' ) .
					'</td>
					<td class="mw-input">' .
					Xml::input( 'wpComment', 50, $this->mComment, [ 'id' => 'wpComment' ] ) .
					"</td>
					</tr>
					<tr>
						<td>\u{00A0}</td>
						<td class=\"mw-submit\">" .
					Xml::submitButton(
						$this->msg( 'mergehistory-submit' )->text(),
						[ 'name' => 'merge', 'id' => 'mw-merge-submit' ]
					) .
					'</td>
					</tr>' .
					Xml::closeElement( 'table' ) .
					Xml::closeElement( 'fieldset' );

			$out->addHTML( $table );
		}

		$out->addHTML(
			'<h2 id="mw-mergehistory">' .
				$this->msg( 'mergehistory-list' )->escaped() . "</h2>\n"
		);

		if ( $haveRevisions ) {
			$out->addHTML( $revisions->getNavigationBar() );
			$out->addHTML( '<ul>' );
			$out->addHTML( $revisions->getBody() );
			$out->addHTML( '</ul>' );
			$out->addHTML( $revisions->getNavigationBar() );
		} else {
			$out->addWikiMsg( 'mergehistory-empty' );
		}

		# Show relevant lines from the merge log:
		$mergeLogPage = new LogPage( 'merge' );
		$out->addHTML( '<h2>' . $mergeLogPage->getName()->escaped() . "</h2>\n" );
		LogEventsList::showLogExtract( $out, 'merge', $this->mTargetObj );

		# When we submit, go by page ID to avoid some nasty but unlikely collisions.
		# Such would happen if a page was renamed after the form loaded, but before submit
		$misc = Html::hidden( 'targetID', $this->mTargetObj->getArticleID() );
		$misc .= Html::hidden( 'destID', $this->mDestObj->getArticleID() );
		$misc .= Html::hidden( 'target', $this->mTarget );
		$misc .= Html::hidden( 'dest', $this->mDest );
		$misc .= Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() );
		$misc .= Xml::closeElement( 'form' );
		$out->addHTML( $misc );

		return true;
	}

	function formatRevisionRow( $row ) {
		$rev = new Revision( $row );

		$linkRenderer = $this->getLinkRenderer();

		$stxt = '';
		$last = $this->msg( 'last' )->escaped();

		$ts = wfTimestamp( TS_MW, $row->rev_timestamp );
		$checkBox = Xml::radio( 'mergepoint', $ts, ( $this->mTimestamp === $ts ) );

		$user = $this->getUser();

		$pageLink = $linkRenderer->makeKnownLink(
			$rev->getTitle(),
			$this->getLanguage()->userTimeAndDate( $ts, $user ),
			[],
			[ 'oldid' => $rev->getId() ]
		);
		if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$pageLink = '<span class="history-deleted">' . $pageLink . '</span>';
		}

		# Last link
		if ( !$rev->userCan( Revision::DELETED_TEXT, $user ) ) {
			$last = $this->msg( 'last' )->escaped();
		} elseif ( isset( $this->prevId[$row->rev_id] ) ) {
			$last = $linkRenderer->makeKnownLink(
				$rev->getTitle(),
				$this->msg( 'last' )->text(),
				[],
				[
					'diff' => $row->rev_id,
					'oldid' => $this->prevId[$row->rev_id]
				]
			);
		}

		$userLink = Linker::revUserTools( $rev );

		$size = $row->rev_len;
		if ( !is_null( $size ) ) {
			$stxt = Linker::formatRevisionSize( $size );
		}
		$comment = Linker::revComment( $rev );

		return Html::rawElement( 'li', [],
			$this->msg( 'mergehistory-revisionrow' )
				->rawParams( $checkBox, $last, $pageLink, $userLink, $stxt, $comment )->escaped() );
	}

	/**
	 * Actually attempt the history move
	 *
	 * @todo if all versions of page A are moved to B and then a user
	 * tries to do a reverse-merge via the "unmerge" log link, then page
	 * A will still be a redirect (as it was after the original merge),
	 * though it will have the old revisions back from before (as expected).
	 * The user may have to "undo" the redirect manually to finish the "unmerge".
	 * Maybe this should delete redirects at the target page of merges?
	 *
	 * @return bool Success
	 */
	function merge() {
		# Get the titles directly from the IDs, in case the target page params
		# were spoofed. The queries are done based on the IDs, so it's best to
		# keep it consistent...
		$targetTitle = Title::newFromID( $this->mTargetID );
		$destTitle = Title::newFromID( $this->mDestID );
		if ( is_null( $targetTitle ) || is_null( $destTitle ) ) {
			return false; // validate these
		}
		if ( $targetTitle->getArticleID() == $destTitle->getArticleID() ) {
			return false;
		}

		// MergeHistory object
		$mh = new MergeHistory( $targetTitle, $destTitle, $this->mTimestamp );

		// Merge!
		$mergeStatus = $mh->merge( $this->getUser(), $this->mComment );
		if ( !$mergeStatus->isOK() ) {
			// Failed merge
			$this->getOutput()->addWikiMsg( $mergeStatus->getMessage() );
			return false;
		}

		$linkRenderer = $this->getLinkRenderer();

		$targetLink = $linkRenderer->makeLink(
			$targetTitle,
			null,
			[],
			[ 'redirect' => 'no' ]
		);

		$this->getOutput()->addWikiMsg( $this->msg( 'mergehistory-done' )
			->rawParams( $targetLink )
			->params( $destTitle->getPrefixedText() )
			->numParams( $mh->getMergedRevisionCount() )
		);

		return true;
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
