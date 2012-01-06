<?php
/**
 * Implements Special:Import
 *
 * Copyright © 2003,2005 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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
 * MediaWiki page data importer
 *
 * @ingroup SpecialPage
 */
class SpecialImport extends SpecialPage {

	private $interwiki = false;
	private $namespace;
	private $frompage = '';
	private $logcomment= false;
	private $history = true;
	private $includeTemplates = false;
	private $pageLinkDepth;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'Import', 'import' );
		global $wgImportTargetNamespace;
		$this->namespace = $wgImportTargetNamespace;
	}

	/**
	 * Execute
	 */
	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$user = $this->getUser();
		if ( !$user->isAllowedAny( 'import', 'importupload' ) ) {
			throw new PermissionsError( 'import' );
		}

		# @todo Allow Title::getUserPermissionsErrors() to take an array
		# @todo FIXME: Title::checkSpecialsAndNSPermissions() has a very wierd expectation of what
		# getUserPermissionsErrors() might actually be used for, hence the 'ns-specialprotected'
		$errors = wfMergeErrorArrays(
			$this->getTitle()->getUserPermissionsErrors(
				'import', $user, true,
				array( 'ns-specialprotected', 'badaccess-group0', 'badaccess-groups' )
			),
			$this->getTitle()->getUserPermissionsErrors(
				'importupload', $user, true,
				array( 'ns-specialprotected', 'badaccess-group0', 'badaccess-groups' )
			)
		);

		if ( $errors ) {
			throw new PermissionsError( 'import', $errors );
		}

		$this->checkReadOnly();

		$request = $this->getRequest();
		if ( $request->wasPosted() && $request->getVal( 'action' ) == 'submit' ) {
			$this->doImport();
		}
		$this->showForm();
	}

	/**
	 * Do the actual import
	 */
	private function doImport() {
		global $wgImportSources, $wgExportMaxLinkDepth;

		$isUpload = false;
		$request = $this->getRequest();
		$this->namespace = $request->getIntOrNull( 'namespace' );
		$sourceName = $request->getVal( "source" );

		$this->logcomment = $request->getText( 'log-comment' );
		$this->pageLinkDepth = $wgExportMaxLinkDepth == 0 ? 0 : $request->getIntOrNull( 'pagelink-depth' );

		$user = $this->getUser();
		if ( !$user->matchEditToken( $request->getVal( 'editToken' ) ) ) {
			$source = Status::newFatal( 'import-token-mismatch' );
		} elseif ( $sourceName == 'upload' ) {
			$isUpload = true;
			if( $user->isAllowed( 'importupload' ) ) {
				$source = ImportStreamSource::newFromUpload( "xmlimport" );
			} else {
				throw new PermissionsError( 'importupload' );
			}
		} elseif ( $sourceName == "interwiki" ) {
			if( !$user->isAllowed( 'import' ) ){
				throw new PermissionsError( 'import' );
			}
			$this->interwiki = $request->getVal( 'interwiki' );
			if ( !in_array( $this->interwiki, $wgImportSources ) ) {
				$source = Status::newFatal( "import-invalid-interwiki" );
			} else {
				$this->history = $request->getCheck( 'interwikiHistory' );
				$this->frompage = $request->getText( "frompage" );
				$this->includeTemplates = $request->getCheck( 'interwikiTemplates' );
				$source = ImportStreamSource::newFromInterwiki(
					$this->interwiki,
					$this->frompage,
					$this->history,
					$this->includeTemplates,
					$this->pageLinkDepth );
			}
		} else {
			$source = Status::newFatal( "importunknownsource" );
		}

		$out = $this->getOutput();
		if( !$source->isGood() ) {
			$out->wrapWikiMsg( "<p class=\"error\">\n$1\n</p>", array( 'importfailed', $source->getWikiText() ) );
		} else {
			$out->addWikiMsg( "importstart" );

			$importer = new WikiImporter( $source->value );
			if( !is_null( $this->namespace ) ) {
				$importer->setTargetNamespace( $this->namespace );
			}
			$reporter = new ImportReporter( $importer, $isUpload, $this->interwiki , $this->logcomment);
			$reporter->setContext( $this->getContext() );
			$exception = false;

			$reporter->open();
			try {
				$importer->doImport();
			} catch ( MWException $e ) {
				$exception = $e;
			}
			$result = $reporter->close();

			if ( $exception ) {
				# No source or XML parse error
				$out->wrapWikiMsg( "<p class=\"error\">\n$1\n</p>", array( 'importfailed', $exception->getMessage() ) );
			} elseif( !$result->isGood() ) {
				# Zero revisions
				$out->wrapWikiMsg( "<p class=\"error\">\n$1\n</p>", array( 'importfailed', $result->getWikiText() ) );
			} else {
				# Success!
				$out->addWikiMsg( 'importsuccess' );
			}
			$out->addHTML( '<hr />' );
		}
	}

	private function showForm() {
		global $wgImportSources, $wgExportMaxLinkDepth;

		$action = $this->getTitle()->getLocalUrl( array( 'action' => 'submit' ) );
		$user = $this->getUser();
		$out = $this->getOutput();

		if( $user->isAllowed( 'importupload' ) ) {
			$out->addWikiMsg( "importtext" );
			$out->addHTML(
				Xml::fieldset( wfMsg( 'import-upload' ) ).
				Xml::openElement( 'form', array( 'enctype' => 'multipart/form-data', 'method' => 'post',
					'action' => $action, 'id' => 'mw-import-upload-form' ) ) .
				Html::hidden( 'action', 'submit' ) .
				Html::hidden( 'source', 'upload' ) .
				Xml::openElement( 'table', array( 'id' => 'mw-import-table' ) ) .

				"<tr>
					<td class='mw-label'>" .
						Xml::label( wfMsg( 'import-upload-filename' ), 'xmlimport' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::input( 'xmlimport', 50, '', array( 'type' => 'file' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td class='mw-label'>" .
						Xml::label( wfMsg( 'import-comment' ), 'mw-import-comment' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::input( 'log-comment', 50, '',
							array( 'id' => 'mw-import-comment', 'type' => 'text' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td></td>
					<td class='mw-submit'>" .
						Xml::submitButton( wfMsg( 'uploadbtn' ) ) .
					"</td>
				</tr>" .
				Xml::closeElement( 'table' ).
				Html::hidden( 'editToken', $user->getEditToken() ) .
				Xml::closeElement( 'form' ) .
				Xml::closeElement( 'fieldset' )
			);
		} else {
			if( empty( $wgImportSources ) ) {
				$out->addWikiMsg( 'importnosources' );
			}
		}

		if( $user->isAllowed( 'import' ) && !empty( $wgImportSources ) ) {
			# Show input field for import depth only if $wgExportMaxLinkDepth > 0
			$importDepth = '';
			if( $wgExportMaxLinkDepth > 0 ) {
				$importDepth = "<tr>
							<td class='mw-label'>" .
								wfMsgExt( 'export-pagelinks', 'parseinline' ) .
							"</td>
							<td class='mw-input'>" .
								Xml::input( 'pagelink-depth', 3, 0 ) .
							"</td>
						</tr>";
			}

			$out->addHTML(
				Xml::fieldset(  wfMsg( 'importinterwiki' ) ) .
				Xml::openElement( 'form', array( 'method' => 'post', 'action' => $action, 'id' => 'mw-import-interwiki-form' ) ) .
				wfMsgExt( 'import-interwiki-text', array( 'parse' ) ) .
				Html::hidden( 'action', 'submit' ) .
				Html::hidden( 'source', 'interwiki' ) .
				Html::hidden( 'editToken', $user->getEditToken() ) .
				Xml::openElement( 'table', array( 'id' => 'mw-import-table' ) ) .
				"<tr>
					<td class='mw-label'>" .
						Xml::label( wfMsg( 'import-interwiki-source' ), 'interwiki' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::openElement( 'select', array( 'name' => 'interwiki' ) )
			);
			foreach( $wgImportSources as $prefix ) {
				$selected = ( $this->interwiki === $prefix ) ? ' selected="selected"' : '';
				$out->addHTML( Xml::option( $prefix, $prefix, $selected ) );
			}

			$out->addHTML(
						Xml::closeElement( 'select' ) .
						Xml::input( 'frompage', 50, $this->frompage ) .
					"</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'import-interwiki-history' ), 'interwikiHistory', 'interwikiHistory', $this->history ) .
					"</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'import-interwiki-templates' ), 'interwikiTemplates', 'interwikiTemplates', $this->includeTemplates ) .
					"</td>
				</tr>
				$importDepth
				<tr>
					<td class='mw-label'>" .
						Xml::label( wfMsg( 'import-interwiki-namespace' ), 'namespace' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::namespaceSelector( $this->namespace, '' ) .
					"</td>
				</tr>
				<tr>
					<td class='mw-label'>" .
						Xml::label( wfMsg( 'import-comment' ), 'mw-interwiki-comment' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::input( 'log-comment', 50, '',
							array( 'id' => 'mw-interwiki-comment', 'type' => 'text' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class='mw-submit'>" .
						Xml::submitButton( wfMsg( 'import-interwiki-submit' ), Linker::tooltipAndAccesskeyAttribs( 'import' ) ) .
					"</td>
				</tr>" .
				Xml::closeElement( 'table' ).
				Xml::closeElement( 'form' ) .
				Xml::closeElement( 'fieldset' )
			);
		}
	}
}

/**
 * Reporting callback
 * @ingroup SpecialPage
 */
class ImportReporter extends ContextSource {
	private $reason=false;
	private $mOriginalLogCallback = null;
	private $mOriginalPageOutCallback = null;
	private $mLogItemCount = 0;

	function __construct( $importer, $upload, $interwiki , $reason=false ) {
		$this->mOriginalPageOutCallback =
				$importer->setPageOutCallback( array( $this, 'reportPage' ) );
		$this->mOriginalLogCallback =
			$importer->setLogItemCallback( array( $this, 'reportLogItem' ) );
		$importer->setNoticeCallback( array( $this, 'reportNotice' ) );
		$this->mPageCount = 0;
		$this->mIsUpload = $upload;
		$this->mInterwiki = $interwiki;
		$this->reason = $reason;
	}

	function open() {
		$this->getOutput()->addHTML( "<ul>\n" );
	}

	function reportNotice( $msg, array $params ) {
		$this->getOutput()->addHTML( Html::element( 'li', array(), $this->msg( $msg, $params )->text() ) );
	}

	function reportLogItem( /* ... */ ) {
		$this->mLogItemCount++;
		if ( is_callable( $this->mOriginalLogCallback ) ) {
			call_user_func_array( $this->mOriginalLogCallback, func_get_args() );
		}
	}

	/**
	 * @param Title $title
	 * @param Title $origTitle
	 * @param int $revisionCount
	 * @param  $successCount
	 * @param  $pageInfo
	 * @return void
	 */
	function reportPage( $title, $origTitle, $revisionCount, $successCount, $pageInfo ) {
		global $wgContLang;

		$args = func_get_args();
		call_user_func_array( $this->mOriginalPageOutCallback, $args );

		if ( $title === null ) {
			# Invalid or non-importable title; a notice is already displayed
			return;
		}

		$this->mPageCount++;

		$localCount = $this->getLanguage()->formatNum( $successCount );
		$contentCount = $wgContLang->formatNum( $successCount );

		if( $successCount > 0 ) {
			$this->getOutput()->addHTML( "<li>" . Linker::linkKnown( $title ) . " " .
				wfMsgExt( 'import-revision-count', array( 'parsemag', 'escape' ), $localCount ) .
				"</li>\n"
			);

			$log = new LogPage( 'import' );
			if( $this->mIsUpload ) {
				$detail = wfMsgExt( 'import-logentry-upload-detail', array( 'content', 'parsemag' ),
					$contentCount );
				if ( $this->reason ) {
					$detail .=  wfMsgForContent( 'colon-separator' ) . $this->reason;
				}
				$log->addEntry( 'upload', $title, $detail );
			} else {
				$interwiki = '[[:' . $this->mInterwiki . ':' .
					$origTitle->getPrefixedText() . ']]';
				$detail = wfMsgExt( 'import-logentry-interwiki-detail', array( 'content', 'parsemag' ),
					$contentCount, $interwiki );
				if ( $this->reason ) {
					$detail .=  wfMsgForContent( 'colon-separator' ) . $this->reason;
				}
				$log->addEntry( 'interwiki', $title, $detail );
			}

			$comment = $detail; // quick
			$dbw = wfGetDB( DB_MASTER );
			$latest = $title->getLatestRevID();
			$nullRevision = Revision::newNullRevision( $dbw, $title->getArticleId(), $comment, true );
			if (!is_null($nullRevision)) {
				$nullRevision->insertOn( $dbw );
				$page = WikiPage::factory( $title );
				# Update page record
				$page->updateRevisionOn( $dbw, $nullRevision );
				wfRunHooks( 'NewRevisionFromEditComplete', array( $page, $nullRevision, $latest, $this->getUser() ) );
			}
		} else {
			$this->getOutput()->addHTML( "<li>" . Linker::linkKnown( $title ) . " " .
				wfMsgHtml( 'import-nonewrevisions' ) . "</li>\n" );
		}
	}

	function close() {
		$out = $this->getOutput();
		if ( $this->mLogItemCount > 0 ) {
			$msg = wfMsgExt( 'imported-log-entries', 'parseinline',
						$this->getLanguage()->formatNum( $this->mLogItemCount ) );
			$out->addHTML( Xml::tags( 'li', null, $msg ) );
		} elseif( $this->mPageCount == 0 && $this->mLogItemCount == 0 ) {
			$out->addHTML( "</ul>\n" );
			return Status::newFatal( 'importnopages' );
		}
		$out->addHTML( "</ul>\n" );

		return Status::newGood( $this->mPageCount );
	}
}
