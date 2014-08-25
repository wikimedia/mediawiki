<?php
/**
 * Implements Special:Import
 *
 * Copyright © 2003,2005 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
	private $subproject;
	private $fullInterwikiPrefix;
	private $namespace;
	private $rootpage = '';
	private $frompage = '';
	private $logcomment = false;
	private $history = true;
	private $includeTemplates = false;
	private $pageLinkDepth;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'Import', 'import' );
		$this->namespace = $this->getConfig()->get( 'ImportTargetNamespace' );
	}

	/**
	 * Execute
	 * @param string|null $par
	 */
	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$this->getOutput()->addModules( 'mediawiki.special.import' );

		$user = $this->getUser();
		if ( !$user->isAllowedAny( 'import', 'importupload' ) ) {
			throw new PermissionsError( 'import' );
		}

		# @todo Allow Title::getUserPermissionsErrors() to take an array
		# @todo FIXME: Title::checkSpecialsAndNSPermissions() has a very wierd expectation of what
		# getUserPermissionsErrors() might actually be used for, hence the 'ns-specialprotected'
		$errors = wfMergeErrorArrays(
			$this->getPageTitle()->getUserPermissionsErrors(
				'import', $user, true,
				array( 'ns-specialprotected', 'badaccess-group0', 'badaccess-groups' )
			),
			$this->getPageTitle()->getUserPermissionsErrors(
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
		$isUpload = false;
		$request = $this->getRequest();
		$this->namespace = $request->getIntOrNull( 'namespace' );
		$sourceName = $request->getVal( "source" );

		$this->logcomment = $request->getText( 'log-comment' );
		$this->pageLinkDepth = $this->getConfig()->get( 'ExportMaxLinkDepth' ) == 0
			? 0
			: $request->getIntOrNull( 'pagelink-depth' );
		$this->rootpage = $request->getText( 'rootpage' );

		$user = $this->getUser();
		if ( !$user->matchEditToken( $request->getVal( 'editToken' ) ) ) {
			$source = Status::newFatal( 'import-token-mismatch' );
		} elseif ( $sourceName == 'upload' ) {
			$isUpload = true;
			if ( $user->isAllowed( 'importupload' ) ) {
				$source = ImportStreamSource::newFromUpload( "xmlimport" );
			} else {
				throw new PermissionsError( 'importupload' );
			}
		} elseif ( $sourceName == "interwiki" ) {
			if ( !$user->isAllowed( 'import' ) ) {
				throw new PermissionsError( 'import' );
			}
			$this->interwiki = $this->fullInterwikiPrefix = $request->getVal( 'interwiki' );
			// does this interwiki have subprojects?
			$importSources = $this->getConfig()->get( 'ImportSources' );
			$hasSubprojects = array_key_exists( $this->interwiki, $importSources );
			if ( !$hasSubprojects && !in_array( $this->interwiki, $importSources ) ) {
				$source = Status::newFatal( "import-invalid-interwiki" );
			} else {
				if ( $hasSubprojects ) {
					$this->subproject = $request->getVal( 'subproject' );
					$this->fullInterwikiPrefix .= ':' . $request->getVal( 'subproject' );
				}
				if ( $hasSubprojects && !in_array( $this->subproject, $importSources[$this->interwiki] ) ) {
					$source = Status::newFatal( "import-invalid-interwiki" );
				} else {
					$this->history = $request->getCheck( 'interwikiHistory' );
					$this->frompage = $request->getText( "frompage" );
					$this->includeTemplates = $request->getCheck( 'interwikiTemplates' );
					$source = ImportStreamSource::newFromInterwiki(
						$this->fullInterwikiPrefix,
						$this->frompage,
						$this->history,
						$this->includeTemplates,
						$this->pageLinkDepth );
				}
			}
		} else {
			$source = Status::newFatal( "importunknownsource" );
		}

		$out = $this->getOutput();
		if ( !$source->isGood() ) {
			$out->wrapWikiMsg(
				"<p class=\"error\">\n$1\n</p>",
				array( 'importfailed', $source->getWikiText() )
			);
		} else {
			$importer = new WikiImporter( $source->value );
			if ( !is_null( $this->namespace ) ) {
				$importer->setTargetNamespace( $this->namespace );
			}
			if ( !is_null( $this->rootpage ) ) {
				$statusRootPage = $importer->setTargetRootPage( $this->rootpage );
				if ( !$statusRootPage->isGood() ) {
					$out->wrapWikiMsg(
						"<p class=\"error\">\n$1\n</p>",
						array(
							'import-options-wrong',
							$statusRootPage->getWikiText(),
							count( $statusRootPage->getErrorsArray() )
						)
					);

					return;
				}
			}

			$out->addWikiMsg( "importstart" );

			$reporter = new ImportReporter(
				$importer,
				$isUpload,
				$this->fullInterwikiPrefix,
				$this->logcomment
			);
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
				$out->wrapWikiMsg(
					"<p class=\"error\">\n$1\n</p>",
					array( 'importfailed', $exception->getMessage() )
				);
			} elseif ( !$result->isGood() ) {
				# Zero revisions
				$out->wrapWikiMsg(
					"<p class=\"error\">\n$1\n</p>",
					array( 'importfailed', $result->getWikiText() )
				);
			} else {
				# Success!
				$out->addWikiMsg( 'importsuccess' );
			}
			$out->addHTML( '<hr />' );
		}
	}

	private function showForm() {
		$action = $this->getPageTitle()->getLocalURL( array( 'action' => 'submit' ) );
		$user = $this->getUser();
		$out = $this->getOutput();
		$importSources = $this->getConfig()->get( 'ImportSources' );

		if ( $user->isAllowed( 'importupload' ) ) {
			$out->addHTML(
				Xml::fieldset( $this->msg( 'import-upload' )->text() ) .
					Xml::openElement(
						'form',
						array(
							'enctype' => 'multipart/form-data',
							'method' => 'post',
							'action' => $action,
							'id' => 'mw-import-upload-form'
						)
					) .
					$this->msg( 'importtext' )->parseAsBlock() .
					Html::hidden( 'action', 'submit' ) .
					Html::hidden( 'source', 'upload' ) .
					Xml::openElement( 'table', array( 'id' => 'mw-import-table-upload' ) ) .
					"<tr>
					<td class='mw-label'>" .
					Xml::label( $this->msg( 'import-upload-filename' )->text(), 'xmlimport' ) .
					"</td>
					<td class='mw-input'>" .
					Html::input( 'xmlimport', '', 'file', array( 'id' => 'xmlimport' ) ) . ' ' .
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
					Xml::label(
						$this->msg( 'import-interwiki-rootpage' )->text(),
						'mw-interwiki-rootpage-upload'
					) .
					"</td>
					<td class='mw-input'>" .
					Xml::input( 'rootpage', 50, $this->rootpage,
						array( 'id' => 'mw-interwiki-rootpage-upload', 'type' => 'text' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td></td>
					<td class='mw-submit'>" .
					Xml::submitButton( $this->msg( 'uploadbtn' )->text() ) .
					"</td>
				</tr>" .
					Xml::closeElement( 'table' ) .
					Html::hidden( 'editToken', $user->getEditToken() ) .
					Xml::closeElement( 'form' ) .
					Xml::closeElement( 'fieldset' )
			);
		} else {
			if ( empty( $importSources ) ) {
				$out->addWikiMsg( 'importnosources' );
			}
		}

		if ( $user->isAllowed( 'import' ) && !empty( $importSources ) ) {
			# Show input field for import depth only if $wgExportMaxLinkDepth > 0
			$importDepth = '';
			if ( $this->getConfig()->get( 'ExportMaxLinkDepth' ) > 0 ) {
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
					Xml::openElement(
						'form',
						array(
							'method' => 'post',
							'action' => $action,
							'id' => 'mw-import-interwiki-form'
						)
					) .
					$this->msg( 'import-interwiki-text' )->parseAsBlock() .
					Html::hidden( 'action', 'submit' ) .
					Html::hidden( 'source', 'interwiki' ) .
					Html::hidden( 'editToken', $user->getEditToken() ) .
					Xml::openElement( 'table', array( 'id' => 'mw-import-table-interwiki' ) ) .
					"<tr>
					<td class='mw-label'>" .
					Xml::label( $this->msg( 'import-interwiki-sourcewiki' )->text(), 'interwiki' ) .
					"</td>
					<td class='mw-input'>" .
					Xml::openElement(
						'select',
						array( 'name' => 'interwiki', 'id' => 'interwiki' )
					)
			);

			$needSubprojectField = false;
			foreach ( $importSources as $key => $value ) {
				if ( is_int( $key ) ) {
					$key = $value;
				} elseif ( $value !== $key ) {
					$needSubprojectField = true;
				}

				$attribs = array(
					'value' => $key,
				);
				if ( is_array( $value ) ) {
					$attribs['data-subprojects'] = implode( ' ', $value );
				}
				if ( $this->interwiki === $key ) {
					$attribs['selected'] = 'selected';
				}
				$out->addHTML( Html::element( 'option', $attribs, $key ) );
			}

			$out->addHTML(
				Xml::closeElement( 'select' )
			);

			if ( $needSubprojectField ) {
				$out->addHTML(
					Xml::openElement(
						'select',
						array( 'name' => 'subproject', 'id' => 'subproject' )
					)
				);

				$subprojectsToAdd = array();
				foreach ( $importSources as $key => $value ) {
					if ( is_array( $value ) ) {
						$subprojectsToAdd = array_merge( $subprojectsToAdd, $value );
					}
				}
				$subprojectsToAdd = array_unique( $subprojectsToAdd );
				sort( $subprojectsToAdd );
				foreach ( $subprojectsToAdd as $subproject ) {
					$out->addHTML( Xml::option( $subproject, $subproject, $this->subproject === $subproject ) );
				}

				$out->addHTML(
					Xml::closeElement( 'select' )
				);
			}

			$out->addHTML(
					"</td>
				</tr>
				<tr>
					<td class='mw-label'>" .
					Xml::label( $this->msg( 'import-interwiki-sourcepage' )->text(), 'frompage' ) .
					"</td>
					<td class='mw-input'>" .
					Xml::input( 'frompage', 50, $this->frompage, array( 'id' => 'frompage' ) ) .
					"</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class='mw-input'>" .
					Xml::checkLabel(
						$this->msg( 'import-interwiki-history' )->text(),
						'interwikiHistory',
						'interwikiHistory',
						$this->history
					) .
					"</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class='mw-input'>" .
					Xml::checkLabel(
						$this->msg( 'import-interwiki-templates' )->text(),
						'interwikiTemplates',
						'interwikiTemplates',
						$this->includeTemplates
					) .
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
							'name' => 'namespace',
							'id' => 'namespace',
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
					Xml::label(
						$this->msg( 'import-interwiki-rootpage' )->text(),
						'mw-interwiki-rootpage-interwiki'
					) .
					"</td>
					<td class='mw-input'>" .
					Xml::input( 'rootpage', 50, $this->rootpage,
						array( 'id' => 'mw-interwiki-rootpage-interwiki', 'type' => 'text' ) ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class='mw-submit'>" .
					Xml::submitButton(
						$this->msg( 'import-interwiki-submit' )->text(),
						Linker::tooltipAndAccesskeyAttribs( 'import' )
					) .
					"</td>
				</tr>" .
					Xml::closeElement( 'table' ) .
					Xml::closeElement( 'form' ) .
					Xml::closeElement( 'fieldset' )
			);
		}
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}

/**
 * Reporting callback
 * @ingroup SpecialPage
 */
class ImportReporter extends ContextSource {
	private $reason = false;
	private $mOriginalLogCallback = null;
	private $mOriginalPageOutCallback = null;
	private $mLogItemCount = 0;

	/**
	 * @param WikiImporter $importer
	 * @param bool $upload
	 * @param string $interwiki
	 * @param string|bool $reason
	 */
	function __construct( $importer, $upload, $interwiki, $reason = false ) {
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
		$this->getOutput()->addHTML(
			Html::element( 'li', array(), $this->msg( $msg, $params )->text() )
		);
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
	 * @param int $successCount
	 * @param array $pageInfo
	 * @return void
	 */
	function reportPage( $title, $origTitle, $revisionCount, $successCount, $pageInfo ) {
		$args = func_get_args();
		call_user_func_array( $this->mOriginalPageOutCallback, $args );

		if ( $title === null ) {
			# Invalid or non-importable title; a notice is already displayed
			return;
		}

		$this->mPageCount++;

		if ( $successCount > 0 ) {
			$this->getOutput()->addHTML(
				"<li>" . Linker::linkKnown( $title ) . " " .
					$this->msg( 'import-revision-count' )->numParams( $successCount )->escaped() .
					"</li>\n"
			);

			$log = new LogPage( 'import' );
			if ( $this->mIsUpload ) {
				$detail = $this->msg( 'import-logentry-upload-detail' )->numParams(
					$successCount )->inContentLanguage()->text();
				if ( $this->reason ) {
					$detail .= $this->msg( 'colon-separator' )->inContentLanguage()->text()
						. $this->reason;
				}
				$log->addEntry( 'upload', $title, $detail, array(), $this->getUser() );
			} else {
				$interwiki = '[[:' . $this->mInterwiki . ':' .
					$origTitle->getPrefixedText() . ']]';
				$detail = $this->msg( 'import-logentry-interwiki-detail' )->numParams(
					$successCount )->params( $interwiki )->inContentLanguage()->text();
				if ( $this->reason ) {
					$detail .= $this->msg( 'colon-separator' )->inContentLanguage()->text()
						. $this->reason;
				}
				$log->addEntry( 'interwiki', $title, $detail, array(), $this->getUser() );
			}

			$comment = $detail; // quick
			$dbw = wfGetDB( DB_MASTER );
			$latest = $title->getLatestRevID();
			$nullRevision = Revision::newNullRevision(
				$dbw,
				$title->getArticleID(),
				$comment,
				true,
				$this->getUser()
			);

			if ( !is_null( $nullRevision ) ) {
				$nullRevision->insertOn( $dbw );
				$page = WikiPage::factory( $title );
				# Update page record
				$page->updateRevisionOn( $dbw, $nullRevision );
				wfRunHooks(
					'NewRevisionFromEditComplete',
					array( $page, $nullRevision, $latest, $this->getUser() )
				);
			}
		} else {
			$this->getOutput()->addHTML( "<li>" . Linker::linkKnown( $title ) . " " .
				$this->msg( 'import-nonewrevisions' )->escaped() . "</li>\n" );
		}
	}

	function close() {
		$out = $this->getOutput();
		if ( $this->mLogItemCount > 0 ) {
			$msg = $this->msg( 'imported-log-entries' )->numParams( $this->mLogItemCount )->parse();
			$out->addHTML( Xml::tags( 'li', null, $msg ) );
		} elseif ( $this->mPageCount == 0 && $this->mLogItemCount == 0 ) {
			$out->addHTML( "</ul>\n" );

			return Status::newFatal( 'importnopages' );
		}
		$out->addHTML( "</ul>\n" );

		return Status::newGood( $this->mPageCount );
	}
}
