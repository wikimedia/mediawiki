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

use Wikimedia\Rdbms\IResultWrapper;

/**
 * Special page allowing users with the appropriate permissions to view
 * and restore deleted content.
 *
 * @ingroup SpecialPage
 */
class SpecialUndelete extends SpecialPage {
	private	$mAction;
	private	$mTarget;
	private	$mTimestamp;
	private	$mRestore;
	private	$mRevdel;
	private	$mInvert;
	private	$mFilename;
	private	$mTargetTimestamp;
	private	$mAllowed;
	private	$mCanView;
	private	$mComment;
	private	$mToken;

	/** @var Title */
	private $mTargetObj;
	/**
	 * @var string Search prefix
	 */
	private $mSearchPrefix;

	function __construct() {
		parent::__construct( 'Undelete', 'deletedhistory' );
	}

	public function doesWrites() {
		return true;
	}

	function loadRequest( $par ) {
		$request = $this->getRequest();
		$user = $this->getUser();

		$this->mAction = $request->getVal( 'action' );
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
		$this->mDiffOnly = $request->getBool( 'diffonly', $this->getUser()->getOption( 'diffonly' ) );
		$this->mComment = $request->getText( 'wpComment' );
		$this->mUnsuppress = $request->getVal( 'wpUnsuppress' ) && $user->isAllowed( 'suppressrevision' );
		$this->mToken = $request->getVal( 'token' );

		if ( $this->isAllowed( 'undelete' ) && !$user->isBlocked() ) {
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
	 * @param User $user
	 * @return bool
	 */
	protected function isAllowed( $permission, User $user = null ) {
		$user = $user ?: $this->getUser();
		if ( $this->mTargetObj !== null ) {
			return $this->mTargetObj->userCan( $permission, $user );
		} else {
			return $user->isAllowed( $permission );
		}
	}

	function userCanExecute( User $user ) {
		return $this->isAllowed( $this->mRestriction, $user );
	}

	function execute( $par ) {
		$this->useTransactionalTimeLimit();

		$user = $this->getUser();

		$this->setHeaders();
		$this->outputHeader();

		$this->loadRequest( $par );
		$this->checkPermissions(); // Needs to be after mTargetObj is set

		$out = $this->getOutput();

		if ( is_null( $this->mTargetObj ) ) {
			$out->addWikiMsg( 'undelete-header' );

			# Not all users can just browse every deleted page from the list
			if ( $user->isAllowed( 'browsearchive' ) ) {
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
			$file = new ArchivedFile( $this->mTargetObj, '', $this->mFilename );
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
		} elseif ( $this->mAction === "submit" ) {
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
		$archive = new PageArchive( $this->mTargetObj );

		$revisions = [];

		foreach ( $this->getRequest()->getValues() as $key => $val ) {
			$matches = [];
			if ( preg_match( "/^ts(\d{14})$/", $key, $matches ) ) {
				$revisions[ $archive->getRevision( $matches[1] )->getId() ] = 1;
			}
		}
		$query = [
			"type" => "revision",
			"ids" => $revisions,
			"target" => $this->mTargetObj->getPrefixedText()
		];
		$url = SpecialPage::getTitleFor( 'Revisiondelete' )->getFullURL( $query );
		$this->getOutput()->redirect( $url );
	}

	function showSearchForm() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'undelete-search-title' ) );
		$fuzzySearch = $this->getRequest()->getVal( 'fuzzy', true );

		$out->enableOOUI();

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
			$out->addHTML( "<li class='undeleteResult'>{$item} ({$revs})</li>\n" );
		}
		$result->free();
		$out->addHTML( "</ul>\n" );

		return true;
	}

	private function showRevision( $timestamp ) {
		if ( !preg_match( '/[0-9]{14}/', $timestamp ) ) {
			return;
		}

		$archive = new PageArchive( $this->mTargetObj, $this->getConfig() );
		if ( !Hooks::run( 'UndeleteForm::showRevision', [ &$archive, $this->mTargetObj ] ) ) {
			return;
		}
		$rev = $archive->getRevision( $timestamp );

		$out = $this->getOutput();
		$user = $this->getUser();

		if ( !$rev ) {
			$out->addWikiMsg( 'undeleterevision-missing' );

			return;
		}

		if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			if ( !$rev->userCan( Revision::DELETED_TEXT, $user ) ) {
				$out->wrapWikiMsg(
					"<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				$rev->isDeleted( Revision::DELETED_RESTRICTED ) ?
					'rev-suppressed-text-permission' : 'rev-deleted-text-permission'
				);

				return;
			}

			$out->wrapWikiMsg(
				"<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				$rev->isDeleted( Revision::DELETED_RESTRICTED ) ?
					'rev-suppressed-text-view' : 'rev-deleted-text-view'
			);
			$out->addHTML( '<br />' );
			// and we are allowed to see...
		}

		if ( $this->mDiff ) {
			$previousRev = $archive->getPreviousRevision( $timestamp );
			if ( $previousRev ) {
				$this->showDiff( $previousRev, $rev );
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
		$userLink = Linker::revUserTools( $rev );

		$content = $rev->getContent( Revision::FOR_THIS_USER, $user );

		$isText = ( $content instanceof TextContent );

		if ( $this->mPreview || $isText ) {
			$openDiv = '<div id="mw-undelete-revision" class="mw-warning">';
		} else {
			$openDiv = '<div id="mw-undelete-revision">';
		}
		$out->addHTML( $openDiv );

		// Revision delete links
		if ( !$this->mDiff ) {
			$revdel = Linker::getRevDeleteLink( $user, $rev, $this->mTargetObj );
			if ( $revdel ) {
				$out->addHTML( "$revdel " );
			}
		}

		$out->addHTML( $this->msg( 'undelete-revision' )->rawParams( $link )->params(
			$time )->rawParams( $userLink )->params( $d, $t )->parse() . '</div>' );

		if ( !Hooks::run( 'UndeleteShowRevision', [ $this->mTargetObj, $rev ] ) ) {
			return;
		}

		if ( ( $this->mPreview || !$isText ) && $content ) {
			// NOTE: non-text content has no source view, so always use rendered preview

			$popts = $out->parserOptions();

			$pout = $content->getParserOutput( $this->mTargetObj, $rev->getId(), $popts, true );
			$out->addParserOutput( $pout, [
				'enableSectionEditLinks' => false,
			] );
		}

		$out->enableOOUI();
		$buttonFields = [];

		if ( $isText ) {
			// source view for textual content
			$sourceView = Xml::element( 'textarea', [
				'readonly' => 'readonly',
				'cols' => 80,
				'rows' => 25
			], $content->getNativeData() . "\n" );

			$buttonFields[] = new OOUI\ButtonInputWidget( [
				'type' => 'submit',
				'name' => 'preview',
				'label' => $this->msg( 'showpreview' )->text()
			] );
		} else {
			$sourceView = '';
			$previewButton = '';
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
	 * @param Revision $previousRev
	 * @param Revision $currentRev
	 * @return string HTML
	 */
	function showDiff( $previousRev, $currentRev ) {
		$diffContext = clone $this->getContext();
		$diffContext->setTitle( $currentRev->getTitle() );
		$diffContext->setWikiPage( WikiPage::factory( $currentRev->getTitle() ) );

		$diffEngine = $currentRev->getContentHandler()->createDifferenceEngine( $diffContext );
		$diffEngine->showDiffStyle();

		$formattedDiff = $diffEngine->generateContentDiffBody(
			$previousRev->getContent( Revision::FOR_THIS_USER, $this->getUser() ),
			$currentRev->getContent( Revision::FOR_THIS_USER, $this->getUser() )
		);

		$formattedDiff = $diffEngine->addHeader(
			$formattedDiff,
			$this->diffHeader( $previousRev, 'o' ),
			$this->diffHeader( $currentRev, 'n' )
		);

		$this->getOutput()->addHTML( "<div>$formattedDiff</div>\n" );
	}

	/**
	 * @param Revision $rev
	 * @param string $prefix
	 * @return string
	 */
	private function diffHeader( $rev, $prefix ) {
		$isDeleted = !( $rev->getId() && $rev->getTitle() );
		if ( $isDeleted ) {
			/// @todo FIXME: $rev->getTitle() is null for deleted revs...?
			$targetPage = $this->getPageTitle();
			$targetQuery = [
				'target' => $this->mTargetObj->getPrefixedText(),
				'timestamp' => wfTimestamp( TS_MW, $rev->getTimestamp() )
			];
		} else {
			/// @todo FIXME: getId() may return non-zero for deleted revs...
			$targetPage = $rev->getTitle();
			$targetQuery = [ 'oldid' => $rev->getId() ];
		}

		// Add show/hide deletion links if available
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$rdel = Linker::getRevDeleteLink( $user, $rev, $this->mTargetObj );

		if ( $rdel ) {
			$rdel = " $rdel";
		}

		$minor = $rev->isMinor() ? ChangesList::flag( 'minor' ) : '';

		$tags = wfGetDB( DB_REPLICA )->selectField(
			'tag_summary',
			'ts_tags',
			[ 'ts_rev_id' => $rev->getId() ],
			__METHOD__
		);
		$tagSummary = ChangeTags::formatSummaryRow( $tags, 'deleteddiff', $this->getContext() );

		// FIXME This is reimplementing DifferenceEngine#getRevisionHeader
		// and partially #showDiffPage, but worse
		return '<div id="mw-diff-' . $prefix . 'title1"><strong>' .
			$this->getLinkRenderer()->makeLink(
				$targetPage,
				$this->msg(
					'revisionasof',
					$lang->userTimeAndDate( $rev->getTimestamp(), $user ),
					$lang->userDate( $rev->getTimestamp(), $user ),
					$lang->userTime( $rev->getTimestamp(), $user )
				)->text(),
				[],
				$targetQuery
			) .
			'</strong></div>' .
			'<div id="mw-diff-' . $prefix . 'title2">' .
			Linker::revUserTools( $rev ) . '<br />' .
			'</div>' .
			'<div id="mw-diff-' . $prefix . 'title3">' .
			$minor . Linker::revComment( $rev ) . $rdel . '<br />' .
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
		$file = new ArchivedFile( $this->mTargetObj, '', $this->mFilename );
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

		$repo = RepoGroup::singleton()->getLocalRepo();
		$path = $repo->getZonePath( 'deleted' ) . '/' . $repo->getDeletedHashPath( $key ) . $key;
		$repo->streamFile( $path );
	}

	protected function showHistory() {
		$this->checkReadOnly();

		$out = $this->getOutput();
		if ( $this->mAllowed ) {
			$out->addModules( 'mediawiki.special.undelete' );
		}
		$out->wrapWikiMsg(
			"<div class='mw-undelete-pagetitle'>\n$1\n</div>\n",
			[ 'undeletepagetitle', wfEscapeWikiText( $this->mTargetObj->getPrefixedText() ) ]
		);

		$archive = new PageArchive( $this->mTargetObj, $this->getConfig() );
		Hooks::run( 'UndeleteForm::showHistory', [ &$archive, $this->mTargetObj ] );

		$out->addHTML( '<div class="mw-undelete-history">' );
		if ( $this->mAllowed ) {
			$out->addWikiMsg( 'undeletehistory' );
			$out->addWikiMsg( 'undeleterevdel' );
		} else {
			$out->addWikiMsg( 'undeletehistorynoadmin' );
		}
		$out->addHTML( '</div>' );

		# List all stored revisions
		$revisions = $archive->listRevisions();
		$files = $archive->listFiles();

		$haveRevisions = $revisions && $revisions->numRows() > 0;
		$haveFiles = $files && $files->numRows() > 0;

		# Batch existence check on user and talk pages
		if ( $haveRevisions ) {
			$batch = new LinkBatch();
			foreach ( $revisions as $row ) {
				$batch->addObj( Title::makeTitleSafe( NS_USER, $row->ar_user_text ) );
				$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->ar_user_text ) );
			}
			$batch->execute();
			$revisions->seek( 0 );
		}
		if ( $haveFiles ) {
			$batch = new LinkBatch();
			foreach ( $files as $row ) {
				$batch->addObj( Title::makeTitleSafe( NS_USER, $row->fa_user_text ) );
				$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->fa_user_text ) );
			}
			$batch->execute();
			$files->seek( 0 );
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
		if ( $this->getUser()->isAllowed( 'suppressionlog' ) ) {
			$out->addHTML( Xml::element( 'h2', null, $suppressLogPage->getName()->text() ) . "\n" );
			LogEventsList::showLogExtract( $out, 'suppress', $this->mTargetObj );
		}

		if ( $this->mAllowed && ( $haveRevisions || $haveFiles ) ) {
			$fields[] = new OOUI\Layout( [
				'content' => new OOUI\HtmlSnippet( $this->msg( 'undeleteextrahelp' )->parseAsBlock() )
			] );

			$conf = $this->getConfig();
			$oldCommentSchema = $conf->get( 'CommentTableSchemaMigrationStage' ) === MIGRATION_OLD;

			$fields[] = new OOUI\FieldLayout(
				new OOUI\TextInputWidget( [
					'name' => 'wpComment',
					'inputId' => 'wpComment',
					'infusable' => true,
					'value' => $this->mComment,
					'autofocus' => true,
					// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
					// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
					// Unicode codepoints (or 255 UTF-8 bytes for old schema).
					'maxLength' => $oldCommentSchema ? 255 : CommentStore::COMMENT_CHARACTER_LIMIT,
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

			if ( $this->getUser()->isAllowed( 'suppressrevision' ) ) {
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

			if ( $this->getUser()->isAllowed( 'deleterevision' ) ) {
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

			$history .= '<ul class="mw-undelete-revlist">';
			$remaining = $revisions->numRows();
			$earliestLiveTime = $this->mTargetObj->getEarliestRevTime();

			foreach ( $revisions as $row ) {
				$remaining--;
				$history .= $this->formatRevisionRow( $row, $earliestLiveTime, $remaining );
			}
			$revisions->free();
			$history .= '</ul>';
		} else {
			$out->addWikiMsg( 'nohistory' );
		}

		if ( $haveFiles ) {
			$history .= Xml::element( 'h2', null, $this->msg( 'filehist' )->text() ) . "\n";
			$history .= '<ul class="mw-undelete-revlist">';
			foreach ( $files as $row ) {
				$history .= $this->formatFileRow( $row );
			}
			$files->free();
			$history .= '</ul>';
		}

		if ( $this->mAllowed ) {
			# Slip in the hidden controls here
			$misc = Html::hidden( 'target', $this->mTarget );
			$misc .= Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() );
			$history .= $misc;

			$form->appendContent( new OOUI\HtmlSnippet( $history ) );
			$out->addHTML( $form );
		} else {
			$out->addHTML( $history );
		}

		return true;
	}

	protected function formatRevisionRow( $row, $earliestLiveTime, $remaining ) {
		$rev = Revision::newFromArchiveRow( $row,
			[
				'title' => $this->mTargetObj
			] );

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
			if ( !$rev->userCan( Revision::DELETED_TEXT, $this->getUser() ) ) {
				$pageLink = htmlspecialchars( $this->getLanguage()->userTimeAndDate( $ts, $user ) );
				$last = $this->msg( 'diff' )->escaped();
			} elseif ( $remaining > 0 || ( $earliestLiveTime && $ts > $earliestLiveTime ) ) {
				$pageLink = $this->getPageLink( $rev, $titleObj, $ts );
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
				$pageLink = $this->getPageLink( $rev, $titleObj, $ts );
				$last = $this->msg( 'diff' )->escaped();
			}
		} else {
			$pageLink = htmlspecialchars( $this->getLanguage()->userTimeAndDate( $ts, $user ) );
			$last = $this->msg( 'diff' )->escaped();
		}

		// User links
		$userLink = Linker::revUserTools( $rev );

		// Minor edit
		$minor = $rev->isMinor() ? ChangesList::flag( 'minor' ) : '';

		// Revision text size
		$size = $row->ar_len;
		if ( !is_null( $size ) ) {
			$revTextSize = Linker::formatRevisionSize( $size );
		}

		// Edit summary
		$comment = Linker::revComment( $rev );

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
	 * @param Revision $rev
	 * @param Title $titleObj
	 * @param string $ts Timestamp
	 * @return string
	 */
	function getPageLink( $rev, $titleObj, $ts ) {
		$user = $this->getUser();
		$time = $this->getLanguage()->userTimeAndDate( $ts, $user );

		if ( !$rev->userCan( Revision::DELETED_TEXT, $user ) ) {
			return '<span class="history-deleted">' . $time . '</span>';
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

		if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
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
	function getFileLink( $file, $titleObj, $ts, $key ) {
		$user = $this->getUser();
		$time = $this->getLanguage()->userTimeAndDate( $ts, $user );

		if ( !$file->userCan( File::DELETED_FILE, $user ) ) {
			return '<span class="history-deleted">' . htmlspecialchars( $time ) . '</span>';
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
	function getFileUser( $file ) {
		if ( !$file->userCan( File::DELETED_USER, $this->getUser() ) ) {
			return '<span class="history-deleted">' .
				$this->msg( 'rev-deleted-user' )->escaped() .
				'</span>';
		}

		$link = Linker::userLink( $file->getRawUser(), $file->getRawUserText() ) .
			Linker::userToolLinks( $file->getRawUser(), $file->getRawUserText() );

		if ( $file->isDeleted( File::DELETED_USER ) ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}

		return $link;
	}

	/**
	 * Fetch file upload comment if it's available to this user
	 *
	 * @param File|ArchivedFile $file
	 * @return string HTML fragment
	 */
	function getFileComment( $file ) {
		if ( !$file->userCan( File::DELETED_COMMENT, $this->getUser() ) ) {
			return '<span class="history-deleted"><span class="comment">' .
				$this->msg( 'rev-deleted-comment' )->escaped() . '</span></span>';
		}

		$link = Linker::commentBlock( $file->getRawDescription() );

		if ( $file->isDeleted( File::DELETED_COMMENT ) ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}

		return $link;
	}

	function undelete() {
		if ( $this->getConfig()->get( 'UploadMaintenance' )
			&& $this->mTargetObj->getNamespace() == NS_FILE
		) {
			throw new ErrorPageError( 'undelete-error', 'filedelete-maintenance' );
		}

		$this->checkReadOnly();

		$out = $this->getOutput();
		$archive = new PageArchive( $this->mTargetObj, $this->getConfig() );
		Hooks::run( 'UndeleteForm::undelete', [ &$archive, $this->mTargetObj ] );
		$ok = $archive->undelete(
			$this->mTargetTimestamp,
			$this->mComment,
			$this->mFileVersions,
			$this->mUnsuppress,
			$this->getUser()
		);

		if ( is_array( $ok ) ) {
			if ( $ok[1] ) { // Undeleted file count
				Hooks::run( 'FileUndeleteComplete', [
					$this->mTargetObj, $this->mFileVersions,
					$this->getUser(), $this->mComment ] );
			}

			$link = $this->getLinkRenderer()->makeKnownLink( $this->mTargetObj );
			$out->addHTML( $this->msg( 'undeletedpage' )->rawParams( $link )->parse() );
		} else {
			$out->setPageTitle( $this->msg( 'undelete-error' ) );
		}

		// Show revision undeletion warnings and errors
		$status = $archive->getRevisionStatus();
		if ( $status && !$status->isGood() ) {
			$out->addWikiText( '<div class="error" id="mw-error-cannotundelete">' .
				$status->getWikiText(
					'cannotundelete',
					'cannotundelete'
				) . '</div>'
			);
		}

		// Show file undeletion warnings and errors
		$status = $archive->getFileStatus();
		if ( $status && !$status->isGood() ) {
			$out->addWikiText( '<div class="error">' .
				$status->getWikiText(
					'undelete-error-short',
					'undelete-error-long'
				) . '</div>'
			);
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
		return $this->prefixSearchString( $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
