<?php
/**
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
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\Page\MergeHistoryFactory;
use MediaWiki\Pager\MergeHistoryPager;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Combine the revision history of two articles into one.
 *
 * Limited to users with the appropriate permissions,
 * and with some restrictions on whether a page's history can be
 * merged.
 *
 * @ingroup SpecialPage
 */
class SpecialMergeHistory extends SpecialPage {
	/** @var string|null */
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

	/** @var Title|null */
	protected $mTargetObj;

	/** @var Title|null */
	protected $mDestObj;

	private MergeHistoryFactory $mergeHistoryFactory;
	private LinkBatchFactory $linkBatchFactory;
	private IConnectionProvider $dbProvider;
	private RevisionStore $revisionStore;
	private CommentFormatter $commentFormatter;
	private ChangeTagsStore $changeTagsStore;

	/** @var Status */
	private $mStatus;

	public function __construct(
		MergeHistoryFactory $mergeHistoryFactory,
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		RevisionStore $revisionStore,
		CommentFormatter $commentFormatter,
		ChangeTagsStore $changeTagsStore
	) {
		parent::__construct( 'MergeHistory', 'mergehistory' );
		$this->mergeHistoryFactory = $mergeHistoryFactory;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->dbProvider = $dbProvider;
		$this->revisionStore = $revisionStore;
		$this->commentFormatter = $commentFormatter;
		$this->changeTagsStore = $changeTagsStore;
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/**
	 * @return void
	 */
	private function loadRequestParams() {
		$request = $this->getRequest();
		$this->mAction = $request->getRawVal( 'action' );
		$this->mTarget = $request->getVal( 'target', '' );
		$this->mDest = $request->getVal( 'dest', '' );
		$this->mSubmitted = $request->getBool( 'submitted' );

		$this->mTargetID = intval( $request->getVal( 'targetID' ) );
		$this->mDestID = intval( $request->getVal( 'destID' ) );
		$this->mTimestamp = $request->getVal( 'mergepoint' );
		if ( $this->mTimestamp === null || !preg_match( '/[0-9]{14}(\|[0-9]+)?/', $this->mTimestamp ) ) {
			$this->mTimestamp = '';
		}
		$this->mComment = $request->getText( 'wpComment' );

		$this->mMerge = $request->wasPosted()
			&& $this->getContext()->getCsrfTokenSet()->matchToken( $request->getVal( 'wpEditToken' ) );

		// target page
		if ( $this->mSubmitted ) {
			$this->mTargetObj = Title::newFromText( $this->mTarget );
			$this->mDestObj = Title::newFromText( $this->mDest );
		} else {
			$this->mTargetObj = null;
			$this->mDestObj = null;
		}
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->useTransactionalTimeLimit();

		$this->checkPermissions();
		$this->checkReadOnly();

		$this->loadRequestParams();

		$this->setHeaders();
		$this->outputHeader();
		$status = Status::newGood();

		if ( $this->mTargetID && $this->mDestID && $this->mAction == 'submit' && $this->mMerge ) {
			$this->merge();

			return;
		}

		if ( !$this->mSubmitted ) {
			$this->showMergeForm();

			return;
		}

		if ( !$this->mTargetObj instanceof Title ) {
			$status->merge( Status::newFatal( 'mergehistory-invalid-source' ) );
		} elseif ( !$this->mTargetObj->exists() ) {
			$status->merge( Status::newFatal(
				'mergehistory-no-source',
				wfEscapeWikiText( $this->mTargetObj->getPrefixedText() )
			) );
		}

		if ( !$this->mDestObj instanceof Title ) {
			$status->merge( Status::newFatal( 'mergehistory-invalid-destination' ) );
		} elseif ( !$this->mDestObj->exists() ) {
			$status->merge( Status::newFatal(
				'mergehistory-no-destination',
				wfEscapeWikiText( $this->mDestObj->getPrefixedText() )
			) );
		}

		if ( $this->mTargetObj && $this->mDestObj && $this->mTargetObj->equals( $this->mDestObj ) ) {
			$status->merge( Status::newFatal( 'mergehistory-same-destination' ) );
		}

		$this->mStatus = $status;

		$this->showMergeForm();

		if ( $this->mStatus->isGood() ) {
			$this->showHistory();
		}
	}

	private function showMergeForm() {
		$out = $this->getOutput();
		$out->addWikiMsg( 'mergehistory-header' );

		$fields = [
			'submitted' => [
				'type' => 'hidden',
				'default' => '1',
				'name' => 'submitted'
			],
			'title' => [
				'type' => 'hidden',
				'default' => $this->getPageTitle()->getPrefixedDBkey(),
				'name' => 'title'
			],
			'mergepoint' => [
				'type' => 'hidden',
				'default' => $this->mTimestamp,
				'name' => 'mergepoint'
			],
			'target' => [
				'type' => 'title',
				'label-message' => 'mergehistory-from',
				'default' => $this->mTarget,
				'id' => 'target',
				'name' => 'target'
			],
			'dest' => [
				'type' => 'title',
				'label-message' => 'mergehistory-into',
				'default' => $this->mDest,
				'id' => 'dest',
				'name' => 'dest'
			]
		];

		$form = HTMLForm::factory( 'ooui', $fields, $this->getContext() );
		$form->setWrapperLegendMsg( 'mergehistory-box' )
			->setSubmitTextMsg( 'mergehistory-go' )
			->setMethod( 'get' )
			->prepareForm()
			->displayForm( $this->mStatus );

		$this->addHelpLink( 'Help:Merge history' );
	}

	private function showHistory() {
		# List all stored revisions
		$revisions = new MergeHistoryPager(
			$this->getContext(),
			$this->getLinkRenderer(),
			$this->linkBatchFactory,
			$this->dbProvider,
			$this->revisionStore,
			$this->commentFormatter,
			$this->changeTagsStore,
			[],
			$this->mTargetObj,
			$this->mDestObj,
			$this->mTimestamp
		);
		$haveRevisions = $revisions->getNumRows() > 0;

		$out = $this->getOutput();
		$out->addModuleStyles( [
			'mediawiki.interface.helpers.styles',
			'mediawiki.special'
		] );
		$titleObj = $this->getPageTitle();
		$action = $titleObj->getLocalURL( [ 'action' => 'submit' ] );
		# Start the form here
		$fields = [
			'targetID' => [
				'type' => 'hidden',
				'name' => 'targetID',
				'default' => $this->mTargetObj->getArticleID()
			],
			'destID' => [
				'type' => 'hidden',
				'name' => 'destID',
				'default' => $this->mDestObj->getArticleID()
			],
			'target' => [
				'type' => 'hidden',
				'name' => 'target',
				'default' => $this->mTarget
			],
			'dest' => [
				'type' => 'hidden',
				'name' => 'dest',
				'default' => $this->mDest
			],
		];
		if ( $haveRevisions ) {
			$fields += [
				'explanation' => [
					'type' => 'info',
					'default' => $this->msg( 'mergehistory-merge', $this->mTargetObj->getPrefixedText(),
						$this->mDestObj->getPrefixedText() )->parse(),
					'raw' => true,
					'cssclass' => 'mw-mergehistory-explanation',
					'section' => 'mergehistory-submit'
				],
				'reason' => [
					'type' => 'text',
					'name' => 'wpComment',
					'label-message' => 'mergehistory-reason',
					'size' => 50,
					'default' => $this->mComment,
					'section' => 'mergehistory-submit'
				],
				'submit' => [
					'type' => 'submit',
					'default' => $this->msg( 'mergehistory-submit' ),
					'section' => 'mergehistory-submit',
					'id' => 'mw-merge-submit',
					'name' => 'merge'
				]
			];
		}
		$form = HTMLForm::factory( 'ooui', $fields, $this->getContext() );
		$form->addHiddenField( 'wpEditToken', $form->getCsrfTokenSet()->getToken() )
			->setId( 'merge' )
			->setAction( $action )
			->suppressDefaultSubmit();

		if ( $haveRevisions ) {
			$form->setFooterHtml(
				'<h2 id="mw-mergehistory">' . $this->msg( 'mergehistory-list' )->escaped() . '</h2>' .
				$revisions->getNavigationBar() .
				$revisions->getBody() .
				$revisions->getNavigationBar()
			);
		} else {
			$form->setFooterHtml( $this->msg( 'mergehistory-empty' ) );
		}

		$form->prepareForm()->displayForm( false );

		# Show relevant lines from the merge log:
		$mergeLogPage = new LogPage( 'merge' );
		$out->addHTML( '<h2>' . $mergeLogPage->getName()->escaped() . "</h2>\n" );
		LogEventsList::showLogExtract( $out, 'merge', $this->mTargetObj );
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
	private function merge() {
		# Get the titles directly from the IDs, in case the target page params
		# were spoofed. The queries are done based on the IDs, so it's best to
		# keep it consistent...
		$targetTitle = Title::newFromID( $this->mTargetID );
		$destTitle = Title::newFromID( $this->mDestID );
		if ( $targetTitle === null || $destTitle === null ) {
			return false; // validate these
		}
		if ( $targetTitle->getArticleID() == $destTitle->getArticleID() ) {
			return false;
		}

		// MergeHistory object
		$mh = $this->mergeHistoryFactory->newMergeHistory( $targetTitle, $destTitle, $this->mTimestamp );

		// Merge!
		$mergeStatus = $mh->merge( $this->getAuthority(), $this->mComment );
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

		// In some cases the target page will be deleted
		$append = ( $mergeStatus->getValue() === 'source-deleted' )
			? $this->msg( 'mergehistory-source-deleted', $targetTitle->getPrefixedText() ) : '';

		$this->getOutput()->addWikiMsg( $this->msg( 'mergehistory-done' )
			->rawParams( $targetLink )
			->params( $destTitle->getPrefixedText(), $append )
			->numParams( $mh->getMergedRevisionCount() )
		);

		return true;
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pagetools';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialMergeHistory::class, 'SpecialMergeHistory' );
