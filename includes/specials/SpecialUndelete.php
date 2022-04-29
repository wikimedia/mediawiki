<?php
/**
 * Implements Special:Undelete
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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Page\UndeletePage;
use MediaWiki\Page\UndeletePageFactory;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\ArchivedRevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\User\UserOptionsLookup;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Special page allowing users with the appropriate permissions to view
 * and restore deleted content.
 *
 * @ingroup SpecialPage
 */
class SpecialUndelete extends SpecialPage {
	private $mAction;
	private $mTarget;
	private $mTimestamp;
	private $mRestore;
	private $mRevdel;
	private $mInvert;
	private $mFilename;
	/** @var string[] */
	private $mTargetTimestamp = [];
	private $mAllowed;
	private $mCanView;
	/** @var string */
	private $mComment = '';
	private $mToken;
	/** @var bool|null */
	private $mPreview;
	/** @var bool|null */
	private $mDiff;
	/** @var bool|null */
	private $mDiffOnly;
	/** @var bool|null */
	private $mUnsuppress;
	/** @var int[] */
	private $mFileVersions = [];

	/** @var Title|null */
	private $mTargetObj;
	/**
	 * @var string Search prefix
	 */
	private $mSearchPrefix;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var RevisionRenderer */
	private $revisionRenderer;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var NameTableStore */
	private $changeTagDefStore;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var LocalRepo */
	private $localRepo;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/** @var SearchEngineFactory */
	private $searchEngineFactory;

	/** @var UndeletePageFactory */
	private $undeletePageFactory;

	/** @var ArchivedRevisionLookup */
	private $archivedRevisionLookup;

	/**
	 * @param PermissionManager $permissionManager
	 * @param RevisionStore $revisionStore
	 * @param RevisionRenderer $revisionRenderer
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param NameTableStore $changeTagDefStore
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param RepoGroup $repoGroup
	 * @param ILoadBalancer $loadBalancer
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param WikiPageFactory $wikiPageFactory
	 * @param SearchEngineFactory $searchEngineFactory
	 * @param UndeletePageFactory $undeletePageFactory
	 * @param ArchivedRevisionLookup $archivedRevisionLookup
	 */
	public function __construct(
		PermissionManager $permissionManager,
		RevisionStore $revisionStore,
		RevisionRenderer $revisionRenderer,
		IContentHandlerFactory $contentHandlerFactory,
		NameTableStore $changeTagDefStore,
		LinkBatchFactory $linkBatchFactory,
		RepoGroup $repoGroup,
		ILoadBalancer $loadBalancer,
		UserOptionsLookup $userOptionsLookup,
		WikiPageFactory $wikiPageFactory,
		SearchEngineFactory $searchEngineFactory,
		UndeletePageFactory $undeletePageFactory,
		ArchivedRevisionLookup $archivedRevisionLookup
	) {
		parent::__construct( 'Undelete', 'deletedhistory' );
		$this->permissionManager = $permissionManager;
		$this->revisionStore = $revisionStore;
		$this->revisionRenderer = $revisionRenderer;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->changeTagDefStore = $changeTagDefStore;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->localRepo = $repoGroup->getLocalRepo();
		$this->loadBalancer = $loadBalancer;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->searchEngineFactory = $searchEngineFactory;
		$this->undeletePageFactory = $undeletePageFactory;
		$this->archivedRevisionLookup = $archivedRevisionLookup;
	}

	public function doesWrites() {
		return true;
	}

	private function loadRequest( $par ) {
		$request = $this->getRequest();
		$user = $this->getUser();

		$this->mAction = $request->getRawVal( 'action' );
		if ( $par !== null && $par !== '' ) {
			$this->mTarget = $par;
		} else {
			$this->mTarget = $request->getVal( 'target' );
		}

		$this->mTargetObj = null;

		if ( $this->mTarget !== null && $this->mTarget !== '' ) {
			$this->mTargetObj = Title::newFromText( $this->mTarget );
		}

		$this->mSearchPrefix = $request->getText( 'prefix' );
		$time = $request->getVal( 'timestamp' );
		$this->mTimestamp = $time ? wfTimestamp( TS_MW, $time ) : '';
		$this->mFilename = $request->getVal( 'file' );

		$posted = $request->wasPosted() &&
			$user->matchEditToken( $request->getVal( 'wpEditToken' ) );
		$this->mRestore = $request->getCheck( 'restore' ) && $posted;
		$this->mRevdel = $request->getCheck( 'revdel' ) && $posted;
		$this->mInvert = $request->getCheck( 'invert' ) && $posted;
		$this->mPreview = $request->getCheck( 'preview' ) && $posted;
		$this->mDiff = $request->getCheck( 'diff' );
		$this->mDiffOnly = $request->getBool( 'diffonly',
			$this->userOptionsLookup->getOption( $this->getUser(), 'diffonly' ) );
		$this->mComment = $request->getText( 'wpComment' );
		$this->mUnsuppress = $request->getVal( 'wpUnsuppress' ) &&
			$this->permissionManager->userHasRight( $user, 'suppressrevision' );
		$this->mToken = $request->getVal( 'token' );

		if ( $this->isAllowed( 'undelete' ) ) {
			$this->mAllowed = true; // user can restore
			$this->mCanView = true; // user can view content
		} elseif ( $this->isAllowed( 'deletedtext' ) ) {
			$this->mAllowed = false; // user cannot restore
			$this->mCanView = true; // user can view content
			$this->mRestore = false;
		} else { // user can only view the list of revisions
			$this->mAllowed = false;
			$this->mCanView = false;
			$this->mTimestamp = '';
			$this->mRestore = false;
		}

		if ( $this->mRestore || $this->mInvert ) {
			$timestamps = [];
			$this->mFileVersions = [];
			foreach ( $request->getValues() as $key => $val ) {
				$matches = [];
				if ( preg_match( '/^ts(\d{14})$/', $key, $matches ) ) {
					array_push( $timestamps, $matches[1] );
				}

				if ( preg_match( '/^fileid(\d+)$/', $key, $matches ) ) {
					$this->mFileVersions[] = intval( $matches[1] );
				}
			}
			rsort( $timestamps );
			$this->mTargetTimestamp = $timestamps;
		}
	}

	/**
	 * Checks whether a user is allowed the permission for the
	 * specific title if one is set.
	 *
	 * @param string $permission
	 * @param User|null $user
	 * @return bool
	 */
	protected function isAllowed( $permission, User $user = null ) {
		$user = $user ?: $this->getUser();
		$block = $user->getBlock();

		if ( $this->mTargetObj !== null ) {
			return $this->permissionManager->userCan( $permission, $user, $this->mTargetObj );
		} else {
			$hasRight = $this->permissionManager->userHasRight( $user, $permission );
			$sitewideBlock = $block && $block->isSitewide();
			return $permission === 'undelete' ? ( $hasRight && !$sitewideBlock ) : $hasRight;
		}
	}

	public function userCanExecute( User $user ) {
		return $this->isAllowed( $this->mRestriction, $user );
	}

	/**
	 * @inheritDoc
	 */
	public function checkPermissions() {
		$user = $this->getUser();

		// First check if user has the right to use this page. If not,
		// show a permissions error whether they are blocked or not.
		if ( !parent::userCanExecute( $user ) ) {
			$this->displayRestrictionError();
		}

		// If a user has the right to use this page, but is blocked from
		// the target, show a block error.
		if (
			$this->mTargetObj && $this->permissionManager->isBlockedFrom( $user, $this->mTargetObj ) ) {
			throw new UserBlockedError( $user->getBlock() );
		}

		// Finally, do the comprehensive permission check via isAllowed.
		if ( !$this->userCanExecute( $user ) ) {
			$this->displayRestrictionError();
		}
	}

	public function execute( $par ) {
		$this->useTransactionalTimeLimit();

		$user = $this->getUser();

		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Deletion_and_undeletion' );

		$this->loadRequest( $par );
		$this->checkPermissions(); // Needs to be after mTargetObj is set

		$out = $this->getOutput();

		if ( $this->mTargetObj === null ) {
			$out->addWikiMsg( 'undelete-header' );

			# Not all users can just browse every deleted page from the list
			if ( $this->permissionManager->userHasRight( $user, 'browsearchive' ) ) {
				$this->showSearchForm();
			}

			return;
		}

		$this->addHelpLink( 'Help:Undelete' );
		if ( $this->mAllowed ) {
			$out->setPageTitle( $this->msg( 'undeletepage' ) );
		} else {
			$out->setPageTitle( $this->msg( 'viewdeletedpage' ) );
		}

		$this->getSkin()->setRelevantTitle( $this->mTargetObj );

		if ( $this->mTimestamp !== '' ) {
			$this->showRevision( $this->mTimestamp );
		} elseif ( $this->mFilename !== null && $this->mTargetObj->inNamespace( NS_FILE ) ) {
			$file = new ArchivedFile( $this->mTargetObj, 0, $this->mFilename );
			// Check if user is allowed to see this file
			if ( !$file->exists() ) {
				$out->addWikiMsg( 'filedelete-nofile', $this->mFilename );
			} elseif ( !$file->userCan( File::DELETED_FILE, $user ) ) {
				if ( $file->isDeleted( File::DELETED_RESTRICTED ) ) {
					throw new PermissionsError( 'suppressrevision' );
				} else {
					throw new PermissionsError( 'deletedtext' );
				}
			} elseif ( !$user->matchEditToken( $this->mToken, $this->mFilename ) ) {
				$this->showFileConfirmationForm( $this->mFilename );
			} else {
				$this->showFile( $this->mFilename );
			}
		} elseif ( $this->mAction === 'submit' ) {
			if ( $this->mRestore ) {
				$this->undelete();
			} elseif ( $this->mRevdel ) {
				$this->redirectToRevDel();
			}

		} else {
			$this->showHistory();
		}
	}

	/**
	 * Convert submitted form data to format expected by RevisionDelete and
	 * redirect the request
	 */
	private function redirectToRevDel() {
		$revisions = [];

		foreach ( $this->getRequest()->getValues() as $key => $val ) {
			$matches = [];
			if ( preg_match( "/^ts(\d{14})$/", $key, $matches ) ) {
				$revisionRecord = $this->archivedRevisionLookup
					->getRevisionRecordByTimestamp( $this->mTargetObj, $matches[1] );
				if ( $revisionRecord ) {
					// Can return null
					$revisions[ $revisionRecord->getId() ] = 1;
				}
			}
		}

		$query = [
			'type' => 'revision',
			'ids' => $revisions,
			'target' => $this->mTargetObj->getPrefixedText()
		];
		$url = SpecialPage::getTitleFor( 'Revisiondelete' )->getFullURL( $query );
		$this->getOutput()->redirect( $url );
	}

	private function showSearchForm() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'undelete-search-title' ) );
		$fuzzySearch = $this->getRequest()->getVal( 'fuzzy', '1' );

		$out->enableOOUI();

		$fields = [];
		$fields[] = new OOUI\ActionFieldLayout(
			new OOUI\TextInputWidget( [
				'name' => 'prefix',
				'inputId' => 'prefix',
				'infusable' => true,
				'value' => $this->mSearchPrefix,
				'autofocus' => true,
			] ),
			new OOUI\ButtonInputWidget( [
				'label' => $this->msg( 'undelete-search-submit' )->text(),
				'flags' => [ 'primary', 'progressive' ],
				'inputId' => 'searchUndelete',
				'type' => 'submit',
			] ),
			[
				'label' => new OOUI\HtmlSnippet(
					$this->msg(
						$fuzzySearch ? 'undelete-search-full' : 'undelete-search-prefix'
					)->parse()
				),
				'align' => 'left',
			]
		);

		$fieldset = new OOUI\FieldsetLayout( [
			'label' => $this->msg( 'undelete-search-box' )->text(),
			'items' => $fields,
		] );

		$form = new OOUI\FormLayout( [
			'method' => 'get',
			'action' => wfScript(),
		] );

		$form->appendContent(
			$fieldset,
			new OOUI\HtmlSnippet(
				Html::hidden( 'title', $this->getPageTitle()->getPrefixedDBkey() ) .
				Html::hidden( 'fuzzy', $fuzzySearch )
			)
		);

		$out->addHTML(
			new OOUI\PanelLayout( [
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $form,
			] )
		);

		# List undeletable articles
		if ( $this->mSearchPrefix ) {
			// For now, we enable search engine match only when specifically asked to
			// by using fuzzy=1 parameter.
			if ( $fuzzySearch ) {
				$result = PageArchive::listPagesBySearch( $this->mSearchPrefix );
			} else {
				$result = PageArchive::listPagesByPrefix( $this->mSearchPrefix );
			}
			$this->showList( $result );
		}
	}

	/**
	 * Generic list of deleted pages
	 *
	 * @param IResultWrapper $result
	 * @return bool
	 */
	private function showList( $result ) {
		$out = $this->getOutput();

		if ( $result->numRows() == 0 ) {
			$out->addWikiMsg( 'undelete-no-results' );

			return false;
		}

		$out->addWikiMsg( 'undeletepagetext', $this->getLanguage()->formatNum( $result->numRows() ) );

		$linkRenderer = $this->getLinkRenderer();
		$undelete = $this->getPageTitle();
		$out->addHTML( "<ul id='undeleteResultsList'>\n" );
		foreach ( $result as $row ) {
			$title = Title::makeTitleSafe( $row->ar_namespace, $row->ar_title );
			if ( $title !== null ) {
				$item = $linkRenderer->makeKnownLink(
					$undelete,
					$title->getPrefixedText(),
					[],
					[ 'target' => $title->getPrefixedText() ]
				);
			} else {
				// The title is no longer valid, show as text
				$item = Html::element(
					'span',
					[ 'class' => 'mw-invalidtitle' ],
					Linker::getInvalidTitleDescription(
						$this->getContext(),
						$row->ar_namespace,
						$row->ar_title
					)
				);
			}
			$revs = $this->msg( 'undeleterevisions' )->numParams( $row->count )->parse();
			$out->addHTML(
				Html::rawElement(
					'li',
					[ 'class' => 'undeleteResult' ],
					"{$item} ({$revs})"
				)
			);
		}
		$result->free();
		$out->addHTML( "</ul>\n" );

		return true;
	}

	private function showRevision( $timestamp ) {
		if ( !preg_match( '/[0-9]{14}/', $timestamp ) ) {
			return;
		}
		$out = $this->getOutput();

		// When viewing a specific revision, add a subtitle link back to the overall
		// history, see T284114
		$listLink = $this->getLinkRenderer()->makeKnownLink(
			$this->getPageTitle(),
			$this->msg( 'undelete-back-to-list' )->text(),
			[],
			[ 'target' => $this->mTargetObj->getPrefixedText() ]
		);
		// same < arrow as with subpages
		$subtitle = "&lt; $listLink";
		$out->setSubtitle( $subtitle );

		$archive = new PageArchive( $this->mTargetObj );
		// FIXME: This hook must be deprecated, passing PageArchive by ref is awful.
		if ( !$this->getHookRunner()->onUndeleteForm__showRevision(
			$archive, $this->mTargetObj )
		) {
			return;
		}
		$revRecord = $this->archivedRevisionLookup->getRevisionRecordByTimestamp( $this->mTargetObj, $timestamp );

		$user = $this->getUser();

		if ( !$revRecord ) {
			$out->addWikiMsg( 'undeleterevision-missing' );
			return;
		}

		if ( $revRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			// Used in wikilinks, should not contain whitespaces
			$titleText = $this->mTargetObj->getPrefixedDBkey();
			if ( !$revRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
				$msg = $revRecord->isDeleted( RevisionRecord::DELETED_RESTRICTED )
					? [ 'rev-suppressed-text-permission', $titleText ]
					: [ 'rev-deleted-text-permission', $titleText ];
				$out->addHtml(
					Html::warningBox(
						$this->msg( $msg[0], $msg[1] )->parse(),
						'plainlinks'
					)
				);
				return;
			}

			$msg = $revRecord->isDeleted( RevisionRecord::DELETED_RESTRICTED )
				? [ 'rev-suppressed-text-view', $titleText ]
				: [ 'rev-deleted-text-view', $titleText ];
			$out->addHtml(
				Html::warningBox(
					$this->msg( $msg[0], $msg[1] )->parse(),
					'plainlinks'
				)
			);
			// and we are allowed to see...
		}

		if ( $this->mDiff ) {
			$previousRevRecord = $this->archivedRevisionLookup
				->getPreviousRevisionRecord( $this->mTargetObj, $timestamp );
			if ( $previousRevRecord ) {
				$this->showDiff( $previousRevRecord, $revRecord );
				if ( $this->mDiffOnly ) {
					return;
				}

				$out->addHTML( '<hr />' );
			} else {
				$out->addWikiMsg( 'undelete-nodiff' );
			}
		}

		$link = $this->getLinkRenderer()->makeKnownLink(
			$this->getPageTitle( $this->mTargetObj->getPrefixedDBkey() ),
			$this->mTargetObj->getPrefixedText()
		);

		$lang = $this->getLanguage();

		// date and time are separate parameters to facilitate localisation.
		// $time is kept for backward compat reasons.
		$time = $lang->userTimeAndDate( $timestamp, $user );
		$d = $lang->userDate( $timestamp, $user );
		$t = $lang->userTime( $timestamp, $user );
		$userLink = Linker::revUserTools( $revRecord );

		$content = $revRecord->getContent(
			SlotRecord::MAIN,
			RevisionRecord::FOR_THIS_USER,
			$user
		);

		// TODO: MCR: this will have to become something like $hasTextSlots and $hasNonTextSlots
		$isText = ( $content instanceof TextContent );

		$out->addHTML(
			Html::openElement(
				'div',
				[
					'id' => 'mw-undelete-revision',
					'class' => $this->mPreview || $isText ? 'warningbox' : '',
				]
			)
		);

		// Revision delete links
		if ( !$this->mDiff ) {
			$revdel = Linker::getRevDeleteLink(
				$user,
				$revRecord,
				$this->mTargetObj
			);
			if ( $revdel ) {
				$out->addHTML( "$revdel " );
			}
		}

		$out->addWikiMsg(
			'undelete-revision',
			Message::rawParam( $link ), $time,
			Message::rawParam( $userLink ), $d, $t
		);
		$out->addHTML( Html::closeElement( 'div' ) );

		if ( $this->mPreview || !$isText ) {
			// NOTE: non-text content has no source view, so always use rendered preview

			$popts = $out->parserOptions();

			$rendered = $this->revisionRenderer->getRenderedRevision(
				$revRecord,
				$popts,
				$user,
				[ 'audience' => RevisionRecord::FOR_THIS_USER ]
			);

			// Fail hard if the audience check fails, since we already checked
			// at the beginning of this method.
			$pout = $rendered->getRevisionParserOutput();

			$out->addParserOutput( $pout, [
				'enableSectionEditLinks' => false,
			] );
		}

		$out->enableOOUI();
		$buttonFields = [];

		if ( $isText ) {
			'@phan-var TextContent $content';
			// TODO: MCR: make this work for multiple slots
			// source view for textual content
			$sourceView = Xml::element( 'textarea', [
				'readonly' => 'readonly',
				'cols' => 80,
				'rows' => 25
			], $content->getText() . "\n" );

			$buttonFields[] = new OOUI\ButtonInputWidget( [
				'type' => 'submit',
				'name' => 'preview',
				'label' => $this->msg( 'showpreview' )->text()
			] );
		} else {
			$sourceView = '';
		}

		$buttonFields[] = new OOUI\ButtonInputWidget( [
			'name' => 'diff',
			'type' => 'submit',
			'label' => $this->msg( 'showdiff' )->text()
		] );

		$out->addHTML(
			$sourceView .
				Xml::openElement( 'div', [
					'style' => 'clear: both' ] ) .
				Xml::openElement( 'form', [
					'method' => 'post',
					'action' => $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] ) ] ) .
				Xml::element( 'input', [
					'type' => 'hidden',
					'name' => 'target',
					'value' => $this->mTargetObj->getPrefixedDBkey() ] ) .
				Xml::element( 'input', [
					'type' => 'hidden',
					'name' => 'timestamp',
					'value' => $timestamp ] ) .
				Xml::element( 'input', [
					'type' => 'hidden',
					'name' => 'wpEditToken',
					'value' => $user->getEditToken() ] ) .
				new OOUI\FieldLayout(
					new OOUI\Widget( [
						'content' => new OOUI\HorizontalLayout( [
							'items' => $buttonFields
						] )
					] )
				) .
				Xml::closeElement( 'form' ) .
				Xml::closeElement( 'div' )
		);
	}

	/**
	 * Build a diff display between this and the previous either deleted
	 * or non-deleted edit.
	 *
	 * @param RevisionRecord $previousRevRecord
	 * @param RevisionRecord $currentRevRecord
	 */
	private function showDiff(
		RevisionRecord $previousRevRecord,
		RevisionRecord $currentRevRecord
	) {
		$currentTitle = Title::newFromLinkTarget( $currentRevRecord->getPageAsLinkTarget() );

		$diffContext = clone $this->getContext();
		$diffContext->setTitle( $currentTitle );
		$diffContext->setWikiPage( $this->wikiPageFactory->newFromTitle( $currentTitle ) );

		$contentModel = $currentRevRecord->getSlot(
			SlotRecord::MAIN,
			RevisionRecord::RAW
		)->getModel();

		$diffEngine = $this->contentHandlerFactory->getContentHandler( $contentModel )
			->createDifferenceEngine( $diffContext );

		$diffEngine->setRevisions( $previousRevRecord, $currentRevRecord );
		$diffEngine->showDiffStyle();
		$formattedDiff = $diffEngine->getDiff(
			$this->diffHeader( $previousRevRecord, 'o' ),
			$this->diffHeader( $currentRevRecord, 'n' )
		);

		$this->getOutput()->addHTML( "<div>$formattedDiff</div>\n" );
	}

	/**
	 * @param RevisionRecord $revRecord
	 * @param string $prefix
	 * @return string
	 */
	private function diffHeader( RevisionRecord $revRecord, $prefix ) {
		$isDeleted = !( $revRecord->getId() && $revRecord->getPageAsLinkTarget() );
		if ( $isDeleted ) {
			// @todo FIXME: $rev->getTitle() is null for deleted revs...?
			$targetPage = $this->getPageTitle();
			$targetQuery = [
				'target' => $this->mTargetObj->getPrefixedText(),
				'timestamp' => wfTimestamp( TS_MW, $revRecord->getTimestamp() )
			];
		} else {
			// @todo FIXME: getId() may return non-zero for deleted revs...
			$targetPage = $revRecord->getPageAsLinkTarget();
			$targetQuery = [ 'oldid' => $revRecord->getId() ];
		}

		// Add show/hide deletion links if available
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$rdel = Linker::getRevDeleteLink( $user, $revRecord, $this->mTargetObj );

		if ( $rdel ) {
			$rdel = " $rdel";
		}

		$minor = $revRecord->isMinor() ? ChangesList::flag( 'minor' ) : '';

		$dbr = $this->loadBalancer->getConnectionRef( ILoadBalancer::DB_REPLICA );
		$tagIds = $dbr->selectFieldValues(
			'change_tag',
			'ct_tag_id',
			[ 'ct_rev_id' => $revRecord->getId() ],
			__METHOD__
		);
		$tags = [];
		foreach ( $tagIds as $tagId ) {
			try {
				$tags[] = $this->changeTagDefStore->getName( (int)$tagId );
			} catch ( NameTableAccessException $exception ) {
				continue;
			}
		}
		$tags = implode( ',', $tags );
		$tagSummary = ChangeTags::formatSummaryRow( $tags, 'deleteddiff', $this->getContext() );

		// FIXME This is reimplementing DifferenceEngine#getRevisionHeader
		// and partially #showDiffPage, but worse
		return '<div id="mw-diff-' . $prefix . 'title1"><strong>' .
			$this->getLinkRenderer()->makeLink(
				$targetPage,
				$this->msg(
					'revisionasof',
					$lang->userTimeAndDate( $revRecord->getTimestamp(), $user ),
					$lang->userDate( $revRecord->getTimestamp(), $user ),
					$lang->userTime( $revRecord->getTimestamp(), $user )
				)->text(),
				[],
				$targetQuery
			) .
			'</strong></div>' .
			'<div id="mw-diff-' . $prefix . 'title2">' .
			Linker::revUserTools( $revRecord ) . '<br />' .
			'</div>' .
			'<div id="mw-diff-' . $prefix . 'title3">' .
			$minor . Linker::revComment( $revRecord ) . $rdel . '<br />' .
			'</div>' .
			'<div id="mw-diff-' . $prefix . 'title5">' .
			$tagSummary[0] . '<br />' .
			'</div>';
	}

	/**
	 * Show a form confirming whether a tokenless user really wants to see a file
	 * @param string $key
	 */
	private function showFileConfirmationForm( $key ) {
		$out = $this->getOutput();
		$lang = $this->getLanguage();
		$user = $this->getUser();
		$file = new ArchivedFile( $this->mTargetObj, 0, $this->mFilename );
		$out->addWikiMsg( 'undelete-show-file-confirm',
			$this->mTargetObj->getText(),
			$lang->userDate( $file->getTimestamp(), $user ),
			$lang->userTime( $file->getTimestamp(), $user ) );
		$out->addHTML(
			Xml::openElement( 'form', [
					'method' => 'POST',
					'action' => $this->getPageTitle()->getLocalURL( [
						'target' => $this->mTarget,
						'file' => $key,
						'token' => $user->getEditToken( $key ),
					] ),
				]
			) .
				Xml::submitButton( $this->msg( 'undelete-show-file-submit' )->text() ) .
				'</form>'
		);
	}

	/**
	 * Show a deleted file version requested by the visitor.
	 * @param string $key
	 */
	private function showFile( $key ) {
		$this->getOutput()->disable();

		# We mustn't allow the output to be CDN cached, otherwise
		# if an admin previews a deleted image, and it's cached, then
		# a user without appropriate permissions can toddle off and
		# nab the image, and CDN will serve it
		$response = $this->getRequest()->response();
		$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
		$response->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
		$response->header( 'Pragma: no-cache' );

		$path = $this->localRepo->getZonePath( 'deleted' ) . '/' . $this->localRepo->getDeletedHashPath( $key ) . $key;
		$this->localRepo->streamFileWithStatus( $path );
	}

	protected function showHistory() {
		$this->checkReadOnly();

		$out = $this->getOutput();
		if ( $this->mAllowed ) {
			$out->addModules( 'mediawiki.misc-authed-ooui' );
		}
		$out->wrapWikiMsg(
			"<div class='mw-undelete-pagetitle'>\n$1\n</div>\n",
			[ 'undeletepagetitle', wfEscapeWikiText( $this->mTargetObj->getPrefixedText() ) ]
		);

		$archive = new PageArchive( $this->mTargetObj );
		// FIXME: This hook must be deprecated, passing PageArchive by ref is awful.
		$this->getHookRunner()->onUndeleteForm__showHistory( $archive, $this->mTargetObj );

		$out->addHTML( Html::openElement( 'div', [ 'class' => 'mw-undelete-history' ] ) );
		if ( $this->mAllowed ) {
			$out->addWikiMsg( 'undeletehistory' );
			$out->addWikiMsg( 'undeleterevdel' );
		} else {
			$out->addWikiMsg( 'undeletehistorynoadmin' );
		}
		$out->addHTML( Html::closeElement( 'div' ) );

		# List all stored revisions
		$revisions = $this->archivedRevisionLookup->listRevisions( $this->mTargetObj );
		$files = $archive->listFiles();

		$haveRevisions = $revisions && $revisions->numRows() > 0;
		$haveFiles = $files && $files->numRows() > 0;

		# Batch existence check on user and talk pages
		if ( $haveRevisions || $haveFiles ) {
			$batch = $this->linkBatchFactory->newLinkBatch();
			if ( $haveRevisions ) {
				foreach ( $revisions as $row ) {
					$batch->add( NS_USER, $row->ar_user_text );
					$batch->add( NS_USER_TALK, $row->ar_user_text );
				}
				$revisions->seek( 0 );
			}
			if ( $haveFiles ) {
				foreach ( $files as $row ) {
					$batch->add( NS_USER, $row->fa_user_text );
					$batch->add( NS_USER_TALK, $row->fa_user_text );
				}
				$files->seek( 0 );
			}
			$batch->execute();
		}

		if ( $this->mAllowed ) {
			$out->enableOOUI();

			$action = $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] );
			# Start the form here
			$form = new OOUI\FormLayout( [
				'method' => 'post',
				'action' => $action,
				'id' => 'undelete',
			] );
		}

		# Show relevant lines from the deletion log:
		$deleteLogPage = new LogPage( 'delete' );
		$out->addHTML( Xml::element( 'h2', null, $deleteLogPage->getName()->text() ) . "\n" );
		LogEventsList::showLogExtract( $out, 'delete', $this->mTargetObj );
		# Show relevant lines from the suppression log:
		$suppressLogPage = new LogPage( 'suppress' );
		if ( $this->permissionManager->userHasRight( $this->getUser(), 'suppressionlog' ) ) {
			$out->addHTML( Xml::element( 'h2', null, $suppressLogPage->getName()->text() ) . "\n" );
			LogEventsList::showLogExtract( $out, 'suppress', $this->mTargetObj );
		}

		if ( $this->mAllowed && ( $haveRevisions || $haveFiles ) ) {
			$fields = [];
			$fields[] = new OOUI\Layout( [
				'content' => new OOUI\HtmlSnippet( $this->msg( 'undeleteextrahelp' )->parseAsBlock() )
			] );

			$fields[] = new OOUI\FieldLayout(
				new OOUI\TextInputWidget( [
					'name' => 'wpComment',
					'inputId' => 'wpComment',
					'infusable' => true,
					'value' => $this->mComment,
					'autofocus' => true,
					// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
					// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
					// Unicode codepoints.
					'maxLength' => CommentStore::COMMENT_CHARACTER_LIMIT,
				] ),
				[
					'label' => $this->msg( 'undeletecomment' )->text(),
					'align' => 'top',
				]
			);

			$fields[] = new OOUI\FieldLayout(
				new OOUI\Widget( [
					'content' => new OOUI\HorizontalLayout( [
						'items' => [
							new OOUI\ButtonInputWidget( [
								'name' => 'restore',
								'inputId' => 'mw-undelete-submit',
								'value' => '1',
								'label' => $this->msg( 'undeletebtn' )->text(),
								'flags' => [ 'primary', 'progressive' ],
								'type' => 'submit',
							] ),
							new OOUI\ButtonInputWidget( [
								'name' => 'invert',
								'inputId' => 'mw-undelete-invert',
								'value' => '1',
								'label' => $this->msg( 'undeleteinvert' )->text()
							] ),
						]
					] )
				] )
			);

			if ( $this->permissionManager->userHasRight( $this->getUser(), 'suppressrevision' ) ) {
				$fields[] = new OOUI\FieldLayout(
					new OOUI\CheckboxInputWidget( [
						'name' => 'wpUnsuppress',
						'inputId' => 'mw-undelete-unsuppress',
						'value' => '1',
					] ),
					[
						'label' => $this->msg( 'revdelete-unsuppress' )->text(),
						'align' => 'inline',
					]
				);
			}

			$fieldset = new OOUI\FieldsetLayout( [
				'label' => $this->msg( 'undelete-fieldset-title' )->text(),
				'id' => 'mw-undelete-table',
				'items' => $fields,
			] );

			$form->appendContent(
				new OOUI\PanelLayout( [
					'expanded' => false,
					'padded' => true,
					'framed' => true,
					'content' => $fieldset,
				] ),
				new OOUI\HtmlSnippet(
					Html::hidden( 'target', $this->mTarget ) .
					Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() )
				)
			);
		}

		$history = '';
		$history .= Xml::element( 'h2', null, $this->msg( 'history' )->text() ) . "\n";

		if ( $haveRevisions ) {
			# Show the page's stored (deleted) history

			if ( $this->permissionManager->userHasRight( $this->getUser(), 'deleterevision' ) ) {
				$history .= Html::element(
					'button',
					[
						'name' => 'revdel',
						'type' => 'submit',
						'class' => 'deleterevision-log-submit mw-log-deleterevision-button'
					],
					$this->msg( 'showhideselectedversions' )->text()
				) . "\n";
			}

			$history .= Html::openElement( 'ul', [ 'class' => 'mw-undelete-revlist' ] );
			$remaining = $revisions->numRows();
			$firstRev = $this->revisionStore->getFirstRevision( $this->mTargetObj );
			$earliestLiveTime = $firstRev ? $firstRev->getTimestamp() : null;

			foreach ( $revisions as $row ) {
				$remaining--;
				$history .= $this->formatRevisionRow( $row, $earliestLiveTime, $remaining );
			}
			$revisions->free();
			$history .= Html::closeElement( 'ul' );
		} else {
			$out->addWikiMsg( 'nohistory' );
		}

		if ( $haveFiles ) {
			$history .= Xml::element( 'h2', null, $this->msg( 'filehist' )->text() ) . "\n";
			$history .= Html::openElement( 'ul', [ 'class' => 'mw-undelete-revlist' ] );
			foreach ( $files as $row ) {
				$history .= $this->formatFileRow( $row );
			}
			$files->free();
			$history .= Html::closeElement( 'ul' );
		}

		if ( $this->mAllowed ) {
			# Slip in the hidden controls here
			$misc = Html::hidden( 'target', $this->mTarget );
			$misc .= Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() );
			$history .= $misc;

			$form->appendContent( new OOUI\HtmlSnippet( $history ) );
			$out->addHTML( (string)$form );
		} else {
			$out->addHTML( $history );
		}

		return true;
	}

	protected function formatRevisionRow( $row, $earliestLiveTime, $remaining ) {
		$revRecord = $this->revisionStore->newRevisionFromArchiveRow(
				$row,
				RevisionStore::READ_NORMAL,
				$this->mTargetObj
			);

		$revTextSize = '';
		$ts = wfTimestamp( TS_MW, $row->ar_timestamp );
		// Build checkboxen...
		if ( $this->mAllowed ) {
			if ( $this->mInvert ) {
				if ( in_array( $ts, $this->mTargetTimestamp ) ) {
					$checkBox = Xml::check( "ts$ts" );
				} else {
					$checkBox = Xml::check( "ts$ts", true );
				}
			} else {
				$checkBox = Xml::check( "ts$ts" );
			}
		} else {
			$checkBox = '';
		}

		// Build page & diff links...
		$user = $this->getUser();
		if ( $this->mCanView ) {
			$titleObj = $this->getPageTitle();
			# Last link
			if ( !$revRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
				$pageLink = htmlspecialchars( $this->getLanguage()->userTimeAndDate( $ts, $user ) );
				$last = $this->msg( 'diff' )->escaped();
			} elseif ( $remaining > 0 || ( $earliestLiveTime && $ts > $earliestLiveTime ) ) {
				$pageLink = $this->getPageLink( $revRecord, $titleObj, $ts );
				$last = $this->getLinkRenderer()->makeKnownLink(
					$titleObj,
					$this->msg( 'diff' )->text(),
					[],
					[
						'target' => $this->mTargetObj->getPrefixedText(),
						'timestamp' => $ts,
						'diff' => 'prev'
					]
				);
			} else {
				$pageLink = $this->getPageLink( $revRecord, $titleObj, $ts );
				$last = $this->msg( 'diff' )->escaped();
			}
		} else {
			$pageLink = htmlspecialchars( $this->getLanguage()->userTimeAndDate( $ts, $user ) );
			$last = $this->msg( 'diff' )->escaped();
		}

		// User links
		$userLink = Linker::revUserTools( $revRecord );

		// Minor edit
		$minor = $revRecord->isMinor() ? ChangesList::flag( 'minor' ) : '';

		// Revision text size
		$size = $row->ar_len;
		if ( $size !== null ) {
			$revTextSize = Linker::formatRevisionSize( $size );
		}

		// Edit summary
		$comment = Linker::revComment( $revRecord );

		// Tags
		$attribs = [];
		list( $tagSummary, $classes ) = ChangeTags::formatSummaryRow(
			$row->ts_tags,
			'deletedhistory',
			$this->getContext()
		);
		if ( $classes ) {
			$attribs['class'] = implode( ' ', $classes );
		}

		$revisionRow = $this->msg( 'undelete-revision-row2' )
			->rawParams(
				$checkBox,
				$last,
				$pageLink,
				$userLink,
				$minor,
				$revTextSize,
				$comment,
				$tagSummary
			)
			->escaped();

		return Xml::tags( 'li', $attribs, $revisionRow ) . "\n";
	}

	private function formatFileRow( $row ) {
		$file = ArchivedFile::newFromRow( $row );
		$ts = wfTimestamp( TS_MW, $row->fa_timestamp );
		$user = $this->getUser();

		$checkBox = '';
		if ( $this->mCanView && $row->fa_storage_key ) {
			if ( $this->mAllowed ) {
				$checkBox = Xml::check( 'fileid' . $row->fa_id );
			}
			$key = urlencode( $row->fa_storage_key );
			$pageLink = $this->getFileLink( $file, $this->getPageTitle(), $ts, $key );
		} else {
			$pageLink = htmlspecialchars( $this->getLanguage()->userTimeAndDate( $ts, $user ) );
		}
		$userLink = $this->getFileUser( $file );
		$data = $this->msg( 'widthheight' )->numParams( $row->fa_width, $row->fa_height )->text();
		$bytes = $this->msg( 'parentheses' )
			->plaintextParams( $this->msg( 'nbytes' )->numParams( $row->fa_size )->text() )
			->plain();
		$data = htmlspecialchars( $data . ' ' . $bytes );
		$comment = $this->getFileComment( $file );

		// Add show/hide deletion links if available
		$canHide = $this->isAllowed( 'deleterevision' );
		if ( $canHide || ( $file->getVisibility() && $this->isAllowed( 'deletedhistory' ) ) ) {
			if ( !$file->userCan( File::DELETED_RESTRICTED, $user ) ) {
				// Revision was hidden from sysops
				$revdlink = Linker::revDeleteLinkDisabled( $canHide );
			} else {
				$query = [
					'type' => 'filearchive',
					'target' => $this->mTargetObj->getPrefixedDBkey(),
					'ids' => $row->fa_id
				];
				$revdlink = Linker::revDeleteLink( $query,
					$file->isDeleted( File::DELETED_RESTRICTED ), $canHide );
			}
		} else {
			$revdlink = '';
		}

		return "<li>$checkBox $revdlink $pageLink . . $userLink $data $comment</li>\n";
	}

	/**
	 * Fetch revision text link if it's available to all users
	 *
	 * @param RevisionRecord $revRecord
	 * @param Title $titleObj
	 * @param string $ts Timestamp
	 * @return string
	 */
	private function getPageLink( RevisionRecord $revRecord, $titleObj, $ts ) {
		$user = $this->getUser();
		$time = $this->getLanguage()->userTimeAndDate( $ts, $user );

		if ( !$revRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
			// TODO The condition cannot be true when the function is called
			// TODO use Html::element and let it handle escaping
			return Html::rawElement(
				'span',
				[ 'class' => 'history-deleted' ],
				htmlspecialchars( $time )
			);
		}

		$link = $this->getLinkRenderer()->makeKnownLink(
			$titleObj,
			$time,
			[],
			[
				'target' => $this->mTargetObj->getPrefixedText(),
				'timestamp' => $ts
			]
		);

		if ( $revRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			$class = Linker::getRevisionDeletedClass( $revRecord );
			$link = '<span class="' . $class . '">' . $link . '</span>';
		}

		return $link;
	}

	/**
	 * Fetch image view link if it's available to all users
	 *
	 * @param File|ArchivedFile $file
	 * @param Title $titleObj
	 * @param string $ts A timestamp
	 * @param string $key A storage key
	 *
	 * @return string HTML fragment
	 */
	private function getFileLink( $file, $titleObj, $ts, $key ) {
		$user = $this->getUser();
		$time = $this->getLanguage()->userTimeAndDate( $ts, $user );

		if ( !$file->userCan( File::DELETED_FILE, $user ) ) {
			// TODO use Html::element and let it handle escaping
			return Html::rawElement(
				'span',
				[ 'class' => 'history-deleted' ],
				htmlspecialchars( $time )
			);
		}

		$link = $this->getLinkRenderer()->makeKnownLink(
			$titleObj,
			$time,
			[],
			[
				'target' => $this->mTargetObj->getPrefixedText(),
				'file' => $key,
				'token' => $user->getEditToken( $key )
			]
		);

		if ( $file->isDeleted( File::DELETED_FILE ) ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}

		return $link;
	}

	/**
	 * Fetch file's user id if it's available to this user
	 *
	 * @param File|ArchivedFile $file
	 * @return string HTML fragment
	 */
	private function getFileUser( $file ) {
		$uploader = $file->getUploader( File::FOR_THIS_USER, $this->getAuthority() );
		if ( !$uploader ) {
			return Html::rawElement(
				'span',
				[ 'class' => 'history-deleted' ],
				$this->msg( 'rev-deleted-user' )->escaped()
			);
		}

		$link = Linker::userLink( $uploader->getId(), $uploader->getName() ) .
			Linker::userToolLinks( $uploader->getId(), $uploader->getName() );

		if ( $file->isDeleted( File::DELETED_USER ) ) {
			$link = Html::rawElement(
				'span',
				[ 'class' => 'history-deleted' ],
				$link
			);
		}

		return $link;
	}

	/**
	 * Fetch file upload comment if it's available to this user
	 *
	 * @param File|ArchivedFile $file
	 * @return string HTML fragment
	 */
	private function getFileComment( $file ) {
		if ( !$file->userCan( File::DELETED_COMMENT, $this->getAuthority() ) ) {
			return Html::rawElement(
				'span',
				[ 'class' => 'history-deleted' ],
				Html::rawElement(
					'span',
					[ 'class' => 'comment' ],
					$this->msg( 'rev-deleted-comment' )->escaped()
				)
			);
		}

		$comment = $file->getDescription( File::FOR_THIS_USER, $this->getAuthority() );
		$link = Linker::commentBlock( $comment );

		if ( $file->isDeleted( File::DELETED_COMMENT ) ) {
			$link = Html::rawElement(
				'span',
				[ 'class' => 'history-deleted' ],
				$link
			);
		}

		return $link;
	}

	private function undelete() {
		if ( $this->getConfig()->get( 'UploadMaintenance' )
			&& $this->mTargetObj->getNamespace() === NS_FILE
		) {
			throw new ErrorPageError( 'undelete-error', 'filedelete-maintenance' );
		}

		$this->checkReadOnly();

		$out = $this->getOutput();
		$undeletePage = $this->undeletePageFactory->newUndeletePage(
			$this->wikiPageFactory->newFromTitle( $this->mTargetObj ),
			$this->getAuthority()
		);
		$status = $undeletePage
			->setUndeleteOnlyTimestamps( $this->mTargetTimestamp )
			->setUndeleteOnlyFileVersions( $this->mFileVersions )
			->setUnsuppress( $this->mUnsuppress )
			// TODO This is currently duplicating some permission checks, but we do need it (T305680)
			->undeleteIfAllowed( $this->mComment );

		if ( !$status->isGood() ) {
			$out->setPageTitle( $this->msg( 'undelete-error' ) );
			$out->wrapWikiTextAsInterface(
				'error',
				Status::wrap( $status )->getWikiText(
					'cannotundelete',
					'cannotundelete',
					$this->getLanguage()
				)
			);
			return;
		}

		$restoredRevs = $status->getValue()[UndeletePage::REVISIONS_RESTORED];
		$restoredFiles = $status->getValue()[UndeletePage::FILES_RESTORED];

		if ( $restoredRevs === 0 && $restoredFiles === 0 ) {
			// TODO Should use a different message here
			$out->setPageTitle( $this->msg( 'undelete-error' ) );
		} else {
			if ( $status->getValue()[UndeletePage::FILES_RESTORED] !== 0 ) {
				$this->getHookRunner()->onFileUndeleteComplete(
					$this->mTargetObj, $this->mFileVersions, $this->getUser(), $this->mComment );
			}

			$link = $this->getLinkRenderer()->makeKnownLink( $this->mTargetObj );
			$out->addWikiMsg( 'undeletedpage', Message::rawParam( $link ) );
		}
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
		return $this->prefixSearchString( $search, $limit, $offset, $this->searchEngineFactory );
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
