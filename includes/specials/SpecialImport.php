<?php
/**
 * MediaWiki page data importer
 * Copyright (C) 2003,2005 Brion Vibber <brion@pobox.com>
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

class SpecialImport extends SpecialPage {
	
	private $interwiki = false;
	private $namespace;
	private $frompage = '';
	private $logcomment= false;
	private $history = true;
	
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
		global $wgRequest;
		
		$this->setHeaders();
		$this->outputHeader();
		
		if ( wfReadOnly() ) {
			global $wgOut;
			$wgOut->readOnlyPage();
			return;
		}
		
		if ( $wgRequest->wasPosted() && $wgRequest->getVal( 'action' ) == 'submit' ) {
			$this->doImport();
		}
		$this->showForm();
	}
	
	/**
	 * Do the actual import
	 */
	private function doImport() {
		global $wgOut, $wgRequest, $wgUser, $wgImportSources;
		$isUpload = false;
		$this->namespace = $wgRequest->getIntOrNull( 'namespace' );
		$sourceName = $wgRequest->getVal( "source" );

		$this->logcomment = $wgRequest->getText( 'log-comment' );

		if ( !$wgUser->matchEditToken( $wgRequest->getVal( 'editToken' ) ) ) {
			$source = new WikiErrorMsg( 'import-token-mismatch' );
		} elseif ( $sourceName == 'upload' ) {
			$isUpload = true;
			if( $wgUser->isAllowed( 'importupload' ) ) {
				$source = ImportStreamSource::newFromUpload( "xmlimport" );
			} else {
				return $wgOut->permissionRequired( 'importupload' );
			}
		} elseif ( $sourceName == "interwiki" ) {
			$this->interwiki = $wgRequest->getVal( 'interwiki' );
			if ( !in_array( $this->interwiki, $wgImportSources ) ) {
				$source = new WikiErrorMsg( "import-invalid-interwiki" );
			} else {
				$this->history = $wgRequest->getCheck( 'interwikiHistory' );
				$this->frompage = $wgRequest->getText( "frompage" );
				$source = ImportStreamSource::newFromInterwiki(
					$this->interwiki,
					$this->frompage,
					$this->history );
			}
		} else {
			$source = new WikiErrorMsg( "importunknownsource" );
		}

		if( WikiError::isError( $source ) ) {
			$wgOut->wrapWikiMsg( '<p class="error">$1</p>', array( 'importfailed', $source->getMessage() ) );
		} else {
			$wgOut->addWikiMsg( "importstart" );

			$importer = new WikiImporter( $source );
			if( !is_null( $this->namespace ) ) {
				$importer->setTargetNamespace( $this->namespace );
			}
			$reporter = new ImportReporter( $importer, $isUpload, $this->interwiki , $this->logcomment);

			$reporter->open();
			$result = $importer->doImport();
			$resultCount = $reporter->close();

			if( WikiError::isError( $result ) ) {
				# No source or XML parse error
				$wgOut->wrapWikiMsg( '<p class="error">$1</p>', array( 'importfailed', $result->getMessage() ) );
			} elseif( WikiError::isError( $resultCount ) ) {
				# Zero revisions
				$wgOut->wrapWikiMsg( '<p class="error">$1</p>', array( 'importfailed', $resultCount->getMessage() ) );
			} else {
				# Success!
				$wgOut->addWikiMsg( 'importsuccess' );
			}
			$wgOut->addWikiText( '<hr />' );
		}
	}

	private function showForm() {
		global $wgUser, $wgOut, $wgRequest, $wgTitle, $wgImportSources;
		$action = $wgTitle->getLocalUrl( 'action=submit' );

		if( $wgUser->isAllowed( 'importupload' ) ) {
			$wgOut->addWikiMsg( "importtext" );
			$wgOut->addHTML(
				Xml::openElement( 'fieldset' ).
				Xml::element( 'legend', null, wfMsg( 'import-upload' ) ) .
				Xml::openElement( 'form', array( 'enctype' => 'multipart/form-data', 'method' => 'post', 'action' => $action ) ) .
				Xml::hidden( 'action', 'submit' ) .
				Xml::hidden( 'source', 'upload' ) .
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
						Xml::label( wfMsg( 'import-comment' ), 'log-comment' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::input( 'log-comment', 50, '', array( 'id' => 'log-comment', 'type' => 'text' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td></td>
					<td class='mw-input'>" .
						Xml::submitButton( wfMsg( 'uploadbtn' ) ) .
					"</td>
				</tr>" .
				Xml::closeElement( 'table' ).
				Xml::hidden( 'editToken', $wgUser->editToken() ) .
				Xml::closeElement( 'form' ) .
				Xml::closeElement( 'fieldset' )
			);
		} else {
			if( empty( $wgImportSources ) ) {
				$wgOut->addWikiMsg( 'importnosources' );
			}
		}
	
		if( !empty( $wgImportSources ) ) {
			$wgOut->addHTML(
				Xml::openElement( 'fieldset' ) .
				Xml::element( 'legend', null, wfMsg( 'importinterwiki' ) ) .
				Xml::openElement( 'form', array( 'method' => 'post', 'action' => $action ) ) .
				wfMsgExt( 'import-interwiki-text', array( 'parse' ) ) .
				Xml::hidden( 'action', 'submit' ) .
				Xml::hidden( 'source', 'interwiki' ) .
				Xml::hidden( 'editToken', $wgUser->editToken() ) .
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
				$wgOut->addHTML( Xml::option( $prefix, $prefix, $selected ) );
			}
			$wgOut->addHTML(
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
					<td>" .
						Xml::label( wfMsg( 'import-interwiki-namespace' ), 'namespace' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::namespaceSelector( $this->namespace, '' ) .
					"</td>
				</tr>
				<tr>
					<td class='mw-label'>" .
						Xml::label( wfMsg( 'import-comment' ), 'comment' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::input( 'log-comment', 50, '', array( 'type' => 'text' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class='mw-input'>" .
						Xml::submitButton( wfMsg( 'import-interwiki-submit' ), array( 'accesskey' => 's' ) ) .
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
class ImportReporter {
      private $reason=false;

	function __construct( $importer, $upload, $interwiki , $reason=false ) {
		$importer->setPageOutCallback( array( $this, 'reportPage' ) );
		$this->mPageCount = 0;
		$this->mIsUpload = $upload;
		$this->mInterwiki = $interwiki;
		$this->reason = $reason;
	}

	function open() {
		global $wgOut;
		$wgOut->addHTML( "<ul>\n" );
	}

	function reportPage( $title, $origTitle, $revisionCount, $successCount ) {
		global $wgOut, $wgUser, $wgLang, $wgContLang;

		$skin = $wgUser->getSkin();

		$this->mPageCount++;

		$localCount = $wgLang->formatNum( $successCount );
		$contentCount = $wgContLang->formatNum( $successCount );

		if( $successCount > 0 ) {
			$wgOut->addHTML( "<li>" . $skin->makeKnownLinkObj( $title ) . " " .
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
			$nullRevision->insertOn( $dbw );
			$article = new Article( $title );
			# Update page record
			$article->updateRevisionOn( $dbw, $nullRevision );
			wfRunHooks( 'NewRevisionFromEditComplete', array($article, $nullRevision, $latest, $wgUser) );
		} else {
			$wgOut->addHTML( '<li>' . wfMsgHtml( 'import-nonewrevisions' ) . '</li>' );
		}
	}

	function close() {
		global $wgOut;
		if( $this->mPageCount == 0 ) {
			$wgOut->addHTML( "</ul>\n" );
			return new WikiErrorMsg( "importnopages" );
		}
		$wgOut->addHTML( "</ul>\n" );

		return $this->mPageCount;
	}
}
