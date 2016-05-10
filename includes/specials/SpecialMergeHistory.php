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
	/** @var FormOptions */
	protected $mOpts;

	/** @var Status */
	protected $mStatus;

	/** @var Title|null */
	protected $mTargetObj, $mDestObj;

	/** @var int[] */
	public $prevId;

	public function __construct() {
		parent::__construct( 'MergeHistory', 'mergehistory' );
	}

	public function doesWrites() {
		return true;
	}

	public function execute( $par ) {
		$this->useTransactionalTimeLimit();

		$this->checkPermissions();
		$this->checkReadOnly();

		$this->setHeaders();
		$this->outputHeader();

		$this->addHelpLink( 'Help:Merge history' );

		$opts = new FormOptions();

		$opts->add( 'target', '' );
		$opts->add( 'dest', '' );
		$opts->add( 'target', '' );
		$opts->add( 'mergepoint', '' );
		$opts->add( 'reason', '' );
		$opts->add( 'merge', false );

		$opts->fetchValuesFromRequest( $this->getRequest() );

		$target = $opts->getValue( 'target' );
		$dest = $opts->getValue( 'dest' );
		$targetObj = Title::newFromText( $target );
		$destObj = Title::newFromText( $dest );
		$status = Status::newGood();

		$this->mOpts = $opts;
		$this->mTargetObj = $targetObj;
		$this->mDestObj = $destObj;

		if ( $opts->getValue( 'merge' ) && $targetObj &&
			$destObj && $opts->getValue( 'mergepoint' ) !== '' ) {
			$this->merge();

			return;
		}

		if ( $target === '' && $dest === '' ) {
			$this->showMergeForm();

			return;
		}

		if ( !$targetObj instanceof Title ) {
			$status->merge( Status::newFatal( 'mergehistory-invalid-source' ) );
		} elseif ( !$targetObj->exists() ) {
			$status->merge( Status::newFatal( 'mergehistory-no-source',
				wfEscapeWikiText( $targetObj->getPrefixedText() )
			) );
		}

		if ( !$destObj instanceof Title ) {
			$status->merge( Status::newFatal( 'mergehistory-invalid-destination' ) );
		} elseif ( !$destObj->exists() ) {
			$status->merge( Status::newFatal( 'mergehistory-no-destination',
				wfEscapeWikiText( $destObj->getPrefixedText() )
			) );
		}

		if ( $targetObj && $destObj && $targetObj->equals( $destObj ) ) {
			$status->merge( Status::newFatal( 'mergehistory-same-destination' ) );
		}

		$this->mStatus = $status;

		$this->showMergeForm();

		if ( $status->isOK() ) {
			$this->showHistory();
		}
	}

	function showMergeForm() {
		$formDescriptor = [
			'target' => [
				'type' => 'title',
				'name' => 'target',
				'label-message' => 'mergehistory-from',
				'required' => true,
			],

			'dest' => [
				'type' => 'title',
				'name' => 'dest',
				'label-message' => 'mergehistory-into',
				'required' => true,
			],
		];

		$form = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setIntro( $this->msg( 'mergehistory-header' ) )
			->setWrapperLegendMsg( 'mergehistory-box' )
			->setSubmitTextMsg( 'mergehistory-go' )
			->setMethod( 'post' )
			->prepareForm()
			->displayForm( $this->mStatus );
	}

	private function showHistory() {
		# List all stored revisions
		$revisions = new MergeHistoryPager(
			$this, [], $this->mTargetObj, $this->mDestObj
		);
		$haveRevisions = $revisions && $revisions->getNumRows() > 0;

		$out = $this->getOutput();
		$header = '<h2 id="mw-mergehistory">' .
			$this->msg( 'mergehistory-list' )->escaped() . "</h2>\n";

		if ( $haveRevisions ) {
			$hiddenFields = [
				'merge' => true,
				'target' => $this->mOpts->getValue( 'target' ),
				'dest' => $this->mOpts->getValue( 'dest' ),
			];

			$formDescriptor = [
				'reason' => [
					'type' => 'text',
					'name' => 'reason',
					'label-message' => 'mergehistory-reason',
				],
			];

			$mergeText = $this->msg( 'mergehistory-merge',
				$this->mTargetObj->getPrefixedText(),
				$this->mDestObj->getPrefixedText()
			)->parse();

			$history = $header .
				$revisions->getNavigationBar() .
				'<ul>' .
				$revisions->getBody() .
				'</ul>' .
				$revisions->getNavigationBar();

			$form = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
				->addHiddenFields( $hiddenFields )
				->setPreText( $mergeText )
				->setFooterText( $history )
				->setSubmitTextMsg( 'mergehistory-submit' )
				->setMethod( 'post' )
				->prepareForm()
				->displayForm( false );
		} else {
			$out->addHTML( $header );
			$out->addWikiMsg( 'mergehistory-empty' );
		}

		# Show relevant lines from the merge log:
		$mergeLogPage = new LogPage( 'merge' );
		$out->addHTML( '<h2>' . $mergeLogPage->getName()->escaped() . "</h2>\n" );
		LogEventsList::showLogExtract( $out, 'merge', $this->mTargetObj );
	}

	function formatRevisionRow( $row ) {
		$rev = new Revision( $row );

		$stxt = '';
		$last = $this->msg( 'last' )->escaped();

		$ts = wfTimestamp( TS_MW, $row->rev_timestamp );
		$checkBox = Xml::radio( 'mergepoint', $ts, ( $this->mOpts->getValue( 'mergepoint' ) === $ts ) );

		$user = $this->getUser();

		$pageLink = Linker::linkKnown(
			$rev->getTitle(),
			htmlspecialchars( $this->getLanguage()->userTimeAndDate( $ts, $user ) ),
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
			$last = Linker::linkKnown(
				$rev->getTitle(),
				$this->msg( 'last' )->escaped(),
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
		$opts = $this->mOpts;

		# Get the titles directly from the IDs, in case the target page params
		# were spoofed. The queries are done based on the IDs, so it's best to
		# keep it consistent...
		$targetObj = $this->mTargetObj;
		$destObj = $this->mDestObj;

		if ( is_null( $targetObj ) || is_null( $destObj ) ||
			$targetObj->getArticleID() == $destObj->getArticleID() ) {
			return false;
		}

		// MergeHistory object
		$mh = new MergeHistory( $targetObj, $destObj, $opts->getValue( 'mergepoint' ) );

		// Merge!
		$mergeStatus = $mh->merge( $this->getUser(), $opts->getValue( 'reason' ) );
		if ( !$mergeStatus->isOK() ) {
			// Failed merge
			$this->getOutput()->addWikiMsg( $mergeStatus->getMessage() );
			return false;
		}

		$targetLink = Linker::link(
			$targetObj,
			null,
			[],
			[ 'redirect' => 'no' ]
		);

		$this->getOutput()->addWikiMsg( $this->msg( 'mergehistory-done' )
			->rawParams( $targetLink )
			->params( $destObj->getPrefixedText() )
			->numParams( $mh->getMergedRevisionCount() )
		);

		return true;
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
