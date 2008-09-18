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

/**
 * Constructor
 */
function wfSpecialImport( $page = '' ) {
	global $wgUser, $wgOut, $wgRequest, $wgTitle, $wgImportSources;
	global $wgImportTargetNamespace;

	$interwiki = false;
	$namespace = $wgImportTargetNamespace;
	$frompage = '';
	$history = true;

	if ( wfReadOnly() ) {
		$wgOut->readOnlyPage();
		return;
	}

	if( $wgRequest->wasPosted() && $wgRequest->getVal( 'action' ) == 'submit') {
		$isUpload = false;
		$namespace = $wgRequest->getIntOrNull( 'namespace' );

		switch( $wgRequest->getVal( "source" ) ) {
		case "upload":
			$isUpload = true;
			if( $wgUser->isAllowed( 'importupload' ) ) {
				$source = ImportStreamSource::newFromUpload( "xmlimport" );
			} else {
				return $wgOut->permissionRequired( 'importupload' );
			}
			break;
		case "interwiki":
			$interwiki = $wgRequest->getVal( 'interwiki' );
			$history = $wgRequest->getCheck( 'interwikiHistory' );
			$frompage = $wgRequest->getText( "frompage" );
			$source = ImportStreamSource::newFromInterwiki(
				$interwiki,
				$frompage,
				$history );
			break;
		default:
			$source = new WikiErrorMsg( "importunknownsource" );
		}

		if( WikiError::isError( $source ) ) {
			$wgOut->wrapWikiMsg( '<p class="error">$1</p>', array( 'importfailed', $source->getMessage() ) );
		} else {
			$wgOut->addWikiMsg( "importstart" );

			$importer = new WikiImporter( $source );
			if( !is_null( $namespace ) ) {
				$importer->setTargetNamespace( $namespace );
			}
			$reporter = new ImportReporter( $importer, $isUpload, $interwiki );

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

	$action = $wgTitle->getLocalUrl( 'action=submit' );

	if( $wgUser->isAllowed( 'importupload' ) ) {
		$wgOut->addWikiMsg( "importtext" );
		$wgOut->addHTML(
			Xml::openElement( 'fieldset' ).
			Xml::element( 'legend', null, wfMsg( 'import-upload' ) ) .
			Xml::openElement( 'form', array( 'enctype' => 'multipart/form-data', 'method' => 'post', 'action' => $action ) ) .
			Xml::hidden( 'action', 'submit' ) .
			Xml::hidden( 'source', 'upload' ) .
			Xml::input( 'xmlimport', 50, '', array( 'type' => 'file' ) ) . ' ' .
			Xml::submitButton( wfMsg( 'uploadbtn' ) ) .
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
			Xml::openElement( 'table', array( 'id' => 'mw-import-table' ) ) .
			"<tr>
				<td>" .
					Xml::openElement( 'select', array( 'name' => 'interwiki' ) )
		);
		foreach( $wgImportSources as $prefix ) {
			$selected = ( $interwiki === $prefix ) ? ' selected="selected"' : '';
			$wgOut->addHTML( Xml::option( $prefix, $prefix, $selected ) );
		}
		$wgOut->addHTML(
					Xml::closeElement( 'select' ) .
				"</td>
				<td>" .
					Xml::input( 'frompage', 50, $frompage ) .
				"</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>" .
					Xml::checkLabel( wfMsg( 'import-interwiki-history' ), 'interwikiHistory', 'interwikiHistory', $history ) .
				"</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>" .
					Xml::label( wfMsg( 'import-interwiki-namespace' ), 'namespace' ) .
					Xml::namespaceSelector( $namespace, '' ) .
				"</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>" .
					Xml::submitButton( wfMsg( 'import-interwiki-submit' ), array( 'accesskey' => 's' ) ) .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ).
			Xml::closeElement( 'form' ) .
			Xml::closeElement( 'fieldset' )
		);
	}
}

/**
 * Reporting callback
 * @ingroup SpecialPage
 */
class ImportReporter {
	function __construct( $importer, $upload, $interwiki ) {
		$importer->setPageOutCallback( array( $this, 'reportPage' ) );
		$this->mPageCount = 0;
		$this->mIsUpload = $upload;
		$this->mInterwiki = $interwiki;
	}

	function open() {
		global $wgOut;
		$wgOut->addHtml( "<ul>\n" );
	}

	function reportPage( $title, $origTitle, $revisionCount, $successCount ) {
		global $wgOut, $wgUser, $wgLang, $wgContLang;

		$skin = $wgUser->getSkin();

		$this->mPageCount++;

		$localCount = $wgLang->formatNum( $successCount );
		$contentCount = $wgContLang->formatNum( $successCount );

		if( $successCount > 0 ) {
			$wgOut->addHtml( "<li>" . $skin->makeKnownLinkObj( $title ) . " " .
				wfMsgExt( 'import-revision-count', array( 'parsemag', 'escape' ), $localCount ) .
				"</li>\n"
			);

			$log = new LogPage( 'import' );
			if( $this->mIsUpload ) {
				$detail = wfMsgExt( 'import-logentry-upload-detail', array( 'content', 'parsemag' ),
					$contentCount );
				$log->addEntry( 'upload', $title, $detail );
			} else {
				$interwiki = '[[:' . $this->mInterwiki . ':' .
					$origTitle->getPrefixedText() . ']]';
				$detail = wfMsgExt( 'import-logentry-interwiki-detail', array( 'content', 'parsemag' ),
					$contentCount, $interwiki );
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
			wfRunHooks( 'NewRevisionFromEditComplete', array($article, $nullRevision, $latest) );
		} else {
			$wgOut->addHtml( '<li>' . wfMsgHtml( 'import-nonewrevisions' ) . '</li>' );
		}
	}

	function close() {
		global $wgOut;
		if( $this->mPageCount == 0 ) {
			$wgOut->addHtml( "</ul>\n" );
			return new WikiErrorMsg( "importnopages" );
		}
		$wgOut->addHtml( "</ul>\n" );

		return $this->mPageCount;
	}
}
