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

class ImportSource {
	/**
	 * @param $fieldname string
	 * @return Status
	 */
	static function newFromUpload( $fieldname = "xmlimport" ) {
		$upload =& $_FILES[$fieldname];

		if( !isset( $upload ) || !$upload['name'] ) {
			return Status::newFatal( 'importnofile' );
		}
		if( !empty( $upload['error'] ) ) {
			switch($upload['error']){
				case 1: # The uploaded file exceeds the upload_max_filesize directive in php.ini.
					return Status::newFatal( 'importuploaderrorsize' );
				case 2: # The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
					return Status::newFatal( 'importuploaderrorsize' );
				case 3: # The uploaded file was only partially uploaded
					return Status::newFatal( 'importuploaderrorpartial' );
				case 6: #Missing a temporary folder.
					return Status::newFatal( 'importuploaderrortemp' );
				# case else: # Currently impossible
			}

		}
		$fname = $upload['tmp_name'];
		if( is_uploaded_file( $fname ) ) {
			return Status::newGood( DumpArchive::newFromFile( $fname, $upload['name'] ) );
		} else {
			return Status::newFatal( 'importnofile' );
		}
	}

	/**
	 * @param $url
	 * @param $method string
	 * @return Status
	 */
	static function newFromURL( $url, $method = 'GET' ) {
		wfDebug( __METHOD__ . ": opening $url\n" );
		# Use the standard HTTP fetch function; it times out
		# quicker and sorts out user-agent problems which might
		# otherwise prevent importing from large sites, such
		# as the Wikimedia cluster, etc.
		$data = Http::request( $method, $url, array( 'followRedirects' => true ) );
		if( $data !== false ) {
			$file = tmpfile();
			fwrite( $file, $data );
			fflush( $file );
			fseek( $file, 0 );
			return Status::newGood( DumpArchive::newFromFile( $file ) );
		} else {
			return Status::newFatal( 'importcantopen' );
		}
	}

	/**
	 * @param $interwiki
	 * @param $page
	 * @param $history bool
	 * @param $templates bool
	 * @param $pageLinkDepth int
	 * @return Status
	 */
	static function newFromInterwiki( $interwiki, $page, $history = false, $templates = false, $pageLinkDepth = 0 ) {
		if( $page == '' ) {
			return Status::newFatal( 'import-noarticle' );
		}
		$link = Title::newFromText( "$interwiki:Special:Export/$page" );
		if( is_null( $link ) || $link->getInterwiki() == '' ) {
			return Status::newFatal( 'importbadinterwiki' );
		} else {
			$params = array();
			if ( $history ) $params['history'] = 1;
			if ( $templates ) $params['templates'] = 1;
			if ( $pageLinkDepth ) $params['pagelink-depth'] = $pageLinkDepth;
			$url = $link->getFullUrl( $params );
			# For interwikis, use POST to avoid redirects.
			return ImportStreamSource::newFromURL( $url, "POST" );
		}
	}

}

/**
 * MediaWiki page data importer
 *
 * @ingroup SpecialPage
 */
class SpecialImport extends SpecialPage {

	private $interwiki = false;
	private $namespace;
	private $rootpage = '';
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
		$this->rootpage = $request->getText( 'rootpage' );

		$user = $this->getUser();
		if ( !$user->matchEditToken( $request->getVal( 'editToken' ) ) ) {
			$importer = Status::newFatal( 'import-token-mismatch' );
		} elseif ( $sourceName == 'upload' ) {
			$isUpload = true;
			if( $user->isAllowed( 'importupload' ) ) {
				$importer = ImportSource::newFromUpload( "xmlimport" );
			} else {
				throw new PermissionsError( 'importupload' );
			}
		} elseif ( $sourceName == "interwiki" ) {
			if( !$user->isAllowed( 'import' ) ){
				throw new PermissionsError( 'import' );
			}
			$this->interwiki = $request->getVal( 'interwiki' );
			if ( !in_array( $this->interwiki, $wgImportSources ) ) {
				$importer = Status::newFatal( "import-invalid-interwiki" );
			} else {
				$this->history = $request->getCheck( 'interwikiHistory' );
				$this->frompage = $request->getText( "frompage" );
				$this->includeTemplates = $request->getCheck( 'interwikiTemplates' );
				$importer = ImportSource::newFromInterwiki(
					$this->interwiki,
					$this->frompage,
					$this->history,
					$this->includeTemplates,
					$this->pageLinkDepth );
			}
		} else {
			$importer = Status::newFatal( "importunknownsource" );
		}
		if( !$importer ) {
			$importer = Status::newFatal( "importunknownformat" );
		}

		$out = $this->getOutput();
		if( !$importer->isGood() ) {
			$out->wrapWikiMsg( "<p class=\"error\">\n$1\n</p>", array( 'importfailed', $importer->getWikiText() ) );
		} else {
			$importer = $importer->value;
			if( !is_null( $this->namespace ) ) {
				$importer->setTargetNamespace( $this->namespace );
			}
			if( !is_null( $this->rootpage ) ) {
				$statusRootPage = $importer->setTargetRootPage( $this->rootpage );
				if( !$statusRootPage->isGood() ) {
					$out->wrapWikiMsg( "<p class=\"error\">\n$1\n</p>", array( 'import-options-wrong', $statusRootPage->getWikiText(), count( $statusRootPage->getErrorsArray() ) ) );
					return;
				}
			}

			$out->addWikiMsg( "importstart" );

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
			$out->addHTML(
				Xml::fieldset( $this->msg( 'import-upload' )->text() ).
				Xml::openElement( 'form', array( 'enctype' => 'multipart/form-data', 'method' => 'post',
					'action' => $action, 'id' => 'mw-import-upload-form' ) ) .
				$this->msg( 'importtext' )->parseAsBlock() .
				Html::hidden( 'action', 'submit' ) .
				Html::hidden( 'source', 'upload' ) .
				Xml::openElement( 'table', array( 'id' => 'mw-import-table' ) ) .

				"<tr>
					<td class='mw-label'>" .
						Xml::label( $this->msg( 'import-upload-filename' )->text(), 'xmlimport' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::input( 'xmlimport', 50, '', array( 'type' => 'file' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td class='mw-label'>" .
						Xml::label( $this->msg( 'import-comment' )->text(), 'mw-import-comment' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::input( 'log-comment', 50, '',
							array( 'id' => 'mw-import-comment', 'type' => 'text' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td class='mw-label'>" .
						Xml::label( $this->msg( 'import-interwiki-rootpage' )->text(), 'mw-interwiki-rootpage' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::input( 'rootpage', 50, $this->rootpage,
							array( 'id' => 'mw-interwiki-rootpage', 'type' => 'text' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td></td>
					<td class='mw-submit'>" .
						Xml::submitButton( $this->msg( 'uploadbtn' )->text() ) .
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
								$this->msg( 'export-pagelinks' )->parse() .
							"</td>
							<td class='mw-input'>" .
								Xml::input( 'pagelink-depth', 3, 0 ) .
							"</td>
						</tr>";
			}

			$out->addHTML(
				Xml::fieldset( $this->msg( 'importinterwiki' )->text() ) .
				Xml::openElement( 'form', array( 'method' => 'post', 'action' => $action, 'id' => 'mw-import-interwiki-form' ) ) .
				$this->msg( 'import-interwiki-text' )->parseAsBlock() .
				Html::hidden( 'action', 'submit' ) .
				Html::hidden( 'source', 'interwiki' ) .
				Html::hidden( 'editToken', $user->getEditToken() ) .
				Xml::openElement( 'table', array( 'id' => 'mw-import-table' ) ) .
				"<tr>
					<td class='mw-label'>" .
						Xml::label( $this->msg( 'import-interwiki-source' )->text(), 'interwiki' ) .
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
						Xml::checkLabel( $this->msg( 'import-interwiki-history' )->text(), 'interwikiHistory', 'interwikiHistory', $this->history ) .
					"</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class='mw-input'>" .
						Xml::checkLabel( $this->msg( 'import-interwiki-templates' )->text(), 'interwikiTemplates', 'interwikiTemplates', $this->includeTemplates ) .
					"</td>
				</tr>
				$importDepth
				<tr>
					<td class='mw-label'>" .
						Xml::label( $this->msg( 'import-interwiki-namespace' )->text(), 'namespace' ) .
					"</td>
					<td class='mw-input'>" .
						Html::namespaceSelector(
							array(
								'selected' => $this->namespace,
								'all' => '',
							), array(
								'name'  => 'namespace',
								'id'    => 'namespace',
								'class' => 'namespaceselector',
							)
						) .
					"</td>
				</tr>
				<tr>
					<td class='mw-label'>" .
						Xml::label( $this->msg( 'import-comment' )->text(), 'mw-interwiki-comment' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::input( 'log-comment', 50, '',
							array( 'id' => 'mw-interwiki-comment', 'type' => 'text' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td class='mw-label'>" .
						Xml::label( $this->msg( 'import-interwiki-rootpage' )->text(), 'mw-interwiki-rootpage' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::input( 'rootpage', 50, $this->rootpage,
							array( 'id' => 'mw-interwiki-rootpage', 'type' => 'text' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class='mw-submit'>" .
						Xml::submitButton( $this->msg( 'import-interwiki-submit' )->text(), Linker::tooltipAndAccesskeyAttribs( 'import' ) ) .
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
		global $wgLang, $wgContLang;

		$args = func_get_args();
		call_user_func_array( $this->mOriginalPageOutCallback, $args );

		if ( $title === null ) {
			# Invalid or non-importable title; a notice is already displayed
			return;
		}

		$skin = $this->getUser()->getSkin();

		$this->mPageCount++;

		$localCount = $wgLang->formatNum( $successCount );
		$contentCount = $wgContLang->formatNum( $successCount );
		$lastRevision = $pageInfo['lastRevision'];
		$lastExistingRevision = $pageInfo['lastExistingRevision'];
		$lastLocalRevision = $pageInfo['lastLocalRevision'];

		/* No revisions in import */
		if ( !$lastExistingRevision && $successCount == 0 ) {
			$msg = wfMsgHtml( 'import-norevisions' );
		} elseif ( !$lastLocalRevision && $successCount > 0 ) {
			// New page imported
			$msg = wfMsgExt( 'import-revision-count-newpage', array( 'parsemag', 'escape' ), $localCount );
		} else {
			$newer = !$lastExistingRevision ||
				$lastLocalRevision->getTimestamp() > $lastExistingRevision->getTimestamp();
			if ( $successCount > 0 ) {
				if ( $newer ) {
					// "Conflict"
					$linktext = wfMsgExt( 'import-conflict-difflink',
						array( 'parsemag', 'escape' ),
						$lastRevision->getId(),
						$lastLocalRevision->getId() );
					$link = $skin->makeKnownLinkObj(
						$title, $linktext,
						'diff=' . $lastRevision->getId() .
						"&oldid=" . $lastLocalRevision->getId() );
					$msg = wfMsgExt( 'import-conflict',
						array( 'parsemag' ),
						$localCount,
						$link );
				} else {
					// Page history continued with new revisions
					$msg = wfMsgExt( 'import-revision-count', array( 'parsemag', 'escape' ), $localCount );
				}
			} else {
				if ( $newer ) {
					// Local revision is newer
					$msg = wfMsgHtml( 'import-nonewrevisions-localnewer' );
				} else {
					// No changes nowhere
					$msg = wfMsgHtml( 'import-nonewrevisions' );
				}
			}
		}
		if ( isset( $pageInfo[ 'fileRevisionsUploaded' ] ) ) {
			$msg .= wfMsgExt( 'import-file-revisions', array( 'parsemag', 'escape' ), $pageInfo[ 'fileRevisionsUploaded' ] );
		}

		$msg = $skin->makeKnownLinkObj( $title ) . ': ' . $msg;

		$out = $this->getOutput();
		$out->addHtml( "<li>$msg</li>" );

		if( $successCount > 0 ) {
			$log = new LogPage( 'import' );
			if( $this->mIsUpload ) {
				$detail = $this->msg( 'import-logentry-upload-detail' )->numParams(
					$successCount )->inContentLanguage()->text();
				if ( $this->reason ) {
					$detail .=  $this->msg( 'colon-separator' )->inContentLanguage()->text() . $this->reason;
				}
				$log->addEntry( 'upload', $title, $detail );
			} else {
				$interwiki = '[[:' . $this->mInterwiki . ':' .
					$origTitle->getPrefixedText() . ']]';
				$detail = $this->msg( 'import-logentry-interwiki-detail' )->numParams(
					$successCount )->params( $interwiki )->inContentLanguage()->text();
				if ( $this->reason ) {
					$detail .=  $this->msg( 'colon-separator' )->inContentLanguage()->text() . $this->reason;
				}
				$log->addEntry( 'interwiki', $title, $detail );
			}
			// [MediaWiki4Intranet] do not insert any empty revisions because it leads
			// to fancy bugs (infinitely multiplicated revisions) in the case of cross
			// (2-way) import-export.
		}
	}

	function close() {
		$out = $this->getOutput();
		if ( $this->mLogItemCount > 0 ) {
			$msg = $this->msg( 'imported-log-entries' )->numParams( $this->mLogItemCount )->parse();
			$out->addHTML( Xml::tags( 'li', null, $msg ) );
		} elseif( $this->mPageCount == 0 && $this->mLogItemCount == 0 ) {
			$out->addHTML( "</ul>\n" );
			return Status::newFatal( 'importnopages' );
		}
		$out->addHTML( "</ul>\n" );

		return Status::newGood( $this->mPageCount );
	}
}
