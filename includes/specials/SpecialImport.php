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
	private $sourceName = false;
	private $interwiki = false;
	private $subproject;
	private $fullInterwikiPrefix;
	private $mapping = 'default';
	private $namespace;
	private $rootpage = '';
	private $frompage = '';
	private $logcomment = false;
	private $history = true;
	private $includeTemplates = false;
	private $pageLinkDepth;
	private $importSources;
	private $assignKnownUsers;
	private $usernamePrefix;

	public function __construct() {
		parent::__construct( 'Import', 'import' );
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * Execute
	 * @param string|null $par
	 * @throws PermissionsError
	 * @throws ReadOnlyError
	 */
	function execute( $par ) {
		$this->useTransactionalTimeLimit();

		$this->setHeaders();
		$this->outputHeader();

		$this->namespace = $this->getConfig()->get( 'ImportTargetNamespace' );

		$this->getOutput()->addModules( 'mediawiki.special.import' );

		$this->importSources = $this->getConfig()->get( 'ImportSources' );
		Hooks::run( 'ImportSources', [ &$this->importSources ] );

		$user = $this->getUser();
		if ( !$user->isAllowedAny( 'import', 'importupload' ) ) {
			throw new PermissionsError( 'import' );
		}

		# @todo Allow Title::getUserPermissionsErrors() to take an array
		# @todo FIXME: Title::checkSpecialsAndNSPermissions() has a very weird expectation of what
		# getUserPermissionsErrors() might actually be used for, hence the 'ns-specialprotected'
		$errors = wfMergeErrorArrays(
			$this->getPageTitle()->getUserPermissionsErrors(
				'import', $user, true,
				[ 'ns-specialprotected', 'badaccess-group0', 'badaccess-groups' ]
			),
			$this->getPageTitle()->getUserPermissionsErrors(
				'importupload', $user, true,
				[ 'ns-specialprotected', 'badaccess-group0', 'badaccess-groups' ]
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
		$this->sourceName = $request->getVal( "source" );
		$this->assignKnownUsers = $request->getCheck( 'assignKnownUsers' );

		$this->logcomment = $request->getText( 'log-comment' );
		$this->pageLinkDepth = $this->getConfig()->get( 'ExportMaxLinkDepth' ) == 0
			? 0
			: $request->getIntOrNull( 'pagelink-depth' );

		$this->mapping = $request->getVal( 'mapping' );
		if ( $this->mapping === 'namespace' ) {
			$this->namespace = $request->getIntOrNull( 'namespace' );
		} elseif ( $this->mapping === 'subpage' ) {
			$this->rootpage = $request->getText( 'rootpage' );
		} else {
			$this->mapping = 'default';
		}

		$user = $this->getUser();
		if ( !$user->matchEditToken( $request->getVal( 'editToken' ) ) ) {
			$source = Status::newFatal( 'import-token-mismatch' );
		} elseif ( $this->sourceName === 'upload' ) {
			$isUpload = true;
			$this->usernamePrefix = $this->fullInterwikiPrefix = $request->getVal( 'usernamePrefix' );
			if ( $user->isAllowed( 'importupload' ) ) {
				$source = ImportStreamSource::newFromUpload( "xmlimport" );
			} else {
				throw new PermissionsError( 'importupload' );
			}
		} elseif ( $this->sourceName === 'interwiki' ) {
			if ( !$user->isAllowed( 'import' ) ) {
				throw new PermissionsError( 'import' );
			}
			$this->interwiki = $this->fullInterwikiPrefix = $request->getVal( 'interwiki' );
			// does this interwiki have subprojects?
			$hasSubprojects = array_key_exists( $this->interwiki, $this->importSources );
			if ( !$hasSubprojects && !in_array( $this->interwiki, $this->importSources ) ) {
				$source = Status::newFatal( "import-invalid-interwiki" );
			} else {
				if ( $hasSubprojects ) {
					$this->subproject = $request->getVal( 'subproject' );
					$this->fullInterwikiPrefix .= ':' . $request->getVal( 'subproject' );
				}
				if ( $hasSubprojects &&
					!in_array( $this->subproject, $this->importSources[$this->interwiki] )
				) {
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

		if ( (string)$this->fullInterwikiPrefix === '' ) {
			$source->fatal( 'importnoprefix' );
		}

		$out = $this->getOutput();
		if ( !$source->isGood() ) {
			$out->addWikiText( "<div class=\"error\">\n" .
				$this->msg( 'importfailed', $source->getWikiText() )->parse() . "\n</div>" );
		} else {
			$importer = new WikiImporter( $source->value, $this->getConfig() );
			if ( !is_null( $this->namespace ) ) {
				$importer->setTargetNamespace( $this->namespace );
			} elseif ( !is_null( $this->rootpage ) ) {
				$statusRootPage = $importer->setTargetRootPage( $this->rootpage );
				if ( !$statusRootPage->isGood() ) {
					$out->wrapWikiMsg(
						"<div class=\"error\">\n$1\n</div>",
						[
							'import-options-wrong',
							$statusRootPage->getWikiText(),
							count( $statusRootPage->getErrorsArray() )
						]
					);

					return;
				}
			}
			$importer->setUsernamePrefix( $this->fullInterwikiPrefix, $this->assignKnownUsers );

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
			} catch ( Exception $e ) {
				$exception = $e;
			}
			$result = $reporter->close();

			if ( $exception ) {
				# No source or XML parse error
				$out->wrapWikiMsg(
					"<div class=\"error\">\n$1\n</div>",
					[ 'importfailed', $exception->getMessage() ]
				);
			} elseif ( !$result->isGood() ) {
				# Zero revisions
				$out->wrapWikiMsg(
					"<div class=\"error\">\n$1\n</div>",
					[ 'importfailed', $result->getWikiText() ]
				);
			} else {
				# Success!
				$out->addWikiMsg( 'importsuccess' );
			}
			$out->addHTML( '<hr />' );
		}
	}

	private function getMappingFormPart( $sourceName ) {
		$isSameSourceAsBefore = ( $this->sourceName === $sourceName );
		$defaultNamespace = $this->getConfig()->get( 'ImportTargetNamespace' );
		return "<tr>
					<td>
					</td>
					<td class='mw-input'>" .
					Xml::radioLabel(
						$this->msg( 'import-mapping-default' )->text(),
						'mapping',
						'default',
						// mw-import-mapping-interwiki-default, mw-import-mapping-upload-default
						"mw-import-mapping-$sourceName-default",
						( $isSameSourceAsBefore ?
							( $this->mapping === 'default' ) :
							is_null( $defaultNamespace ) )
					) .
					"</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class='mw-input'>" .
					Xml::radioLabel(
						$this->msg( 'import-mapping-namespace' )->text(),
						'mapping',
						'namespace',
						// mw-import-mapping-interwiki-namespace, mw-import-mapping-upload-namespace
						"mw-import-mapping-$sourceName-namespace",
						( $isSameSourceAsBefore ?
							( $this->mapping === 'namespace' ) :
							!is_null( $defaultNamespace ) )
					) . ' ' .
					Html::namespaceSelector(
						[
							'selected' => ( $isSameSourceAsBefore ?
								$this->namespace :
								( $defaultNamespace || '' ) ),
						], [
							'name' => "namespace",
							// mw-import-namespace-interwiki, mw-import-namespace-upload
							'id' => "mw-import-namespace-$sourceName",
							'class' => 'namespaceselector',
						]
					) .
					"</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class='mw-input'>" .
					Xml::radioLabel(
						$this->msg( 'import-mapping-subpage' )->text(),
						'mapping',
						'subpage',
						// mw-import-mapping-interwiki-subpage, mw-import-mapping-upload-subpage
						"mw-import-mapping-$sourceName-subpage",
						( $isSameSourceAsBefore ? ( $this->mapping === 'subpage' ) : '' )
					) . ' ' .
					Xml::input( 'rootpage', 50,
						( $isSameSourceAsBefore ? $this->rootpage : '' ),
						[
							// Should be "mw-import-rootpage-...", but we keep this inaccurate
							// ID for legacy reasons
							// mw-interwiki-rootpage-interwiki, mw-interwiki-rootpage-upload
							'id' => "mw-interwiki-rootpage-$sourceName",
							'type' => 'text'
						]
					) . ' ' .
					"</td>
				</tr>";
	}

	private function showForm() {
		$action = $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] );
		$user = $this->getUser();
		$out = $this->getOutput();
		$this->addHelpLink( '//meta.wikimedia.org/wiki/Special:MyLanguage/Help:Import', true );

		if ( $user->isAllowed( 'importupload' ) ) {
			$mappingSelection = $this->getMappingFormPart( 'upload' );
			$out->addHTML(
				Xml::fieldset( $this->msg( 'import-upload' )->text() ) .
					Xml::openElement(
						'form',
						[
							'enctype' => 'multipart/form-data',
							'method' => 'post',
							'action' => $action,
							'id' => 'mw-import-upload-form'
						]
					) .
					$this->msg( 'importtext' )->parseAsBlock() .
					Html::hidden( 'action', 'submit' ) .
					Html::hidden( 'source', 'upload' ) .
					Xml::openElement( 'table', [ 'id' => 'mw-import-table-upload' ] ) .
					"<tr>
					<td class='mw-label'>" .
					Xml::label( $this->msg( 'import-upload-filename' )->text(), 'xmlimport' ) .
					"</td>
					<td class='mw-input'>" .
					Html::input( 'xmlimport', '', 'file', [ 'id' => 'xmlimport' ] ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td class='mw-label'>" .
					Xml::label( $this->msg( 'import-upload-username-prefix' )->text(),
						'mw-import-usernamePrefix' ) .
					"</td>
					<td class='mw-input'>" .
					Xml::input( 'usernamePrefix', 50,
						$this->usernamePrefix,
						[ 'id' => 'usernamePrefix', 'type' => 'text' ] ) . ' ' .
					"</td>
				</tr>
				<tr>
					<td></td>
					<td class='mw-input'>" .
					Xml::checkLabel(
						$this->msg( 'import-assign-known-users' )->text(),
						'assignKnownUsers',
						'assignKnownUsers',
						$this->assignKnownUsers
					) .
					"</td>
				</tr>
				<tr>
					<td class='mw-label'>" .
					Xml::label( $this->msg( 'import-comment' )->text(), 'mw-import-comment' ) .
					"</td>
					<td class='mw-input'>" .
					Xml::input( 'log-comment', 50,
						( $this->sourceName === 'upload' ? $this->logcomment : '' ),
						[ 'id' => 'mw-import-comment', 'type' => 'text' ] ) . ' ' .
					"</td>
				</tr>
				$mappingSelection
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
			if ( empty( $this->importSources ) ) {
				$out->addWikiMsg( 'importnosources' );
			}
		}

		if ( $user->isAllowed( 'import' ) && !empty( $this->importSources ) ) {
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
			$mappingSelection = $this->getMappingFormPart( 'interwiki' );

			$out->addHTML(
				Xml::fieldset( $this->msg( 'importinterwiki' )->text() ) .
					Xml::openElement(
						'form',
						[
							'method' => 'post',
							'action' => $action,
							'id' => 'mw-import-interwiki-form'
						]
					) .
					$this->msg( 'import-interwiki-text' )->parseAsBlock() .
					Html::hidden( 'action', 'submit' ) .
					Html::hidden( 'source', 'interwiki' ) .
					Html::hidden( 'editToken', $user->getEditToken() ) .
					Xml::openElement( 'table', [ 'id' => 'mw-import-table-interwiki' ] ) .
					"<tr>
					<td class='mw-label'>" .
					Xml::label( $this->msg( 'import-interwiki-sourcewiki' )->text(), 'interwiki' ) .
					"</td>
					<td class='mw-input'>" .
					Xml::openElement(
						'select',
						[ 'name' => 'interwiki', 'id' => 'interwiki' ]
					)
			);

			$needSubprojectField = false;
			foreach ( $this->importSources as $key => $value ) {
				if ( is_int( $key ) ) {
					$key = $value;
				} elseif ( $value !== $key ) {
					$needSubprojectField = true;
				}

				$attribs = [
					'value' => $key,
				];
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
						[ 'name' => 'subproject', 'id' => 'subproject' ]
					)
				);

				$subprojectsToAdd = [];
				foreach ( $this->importSources as $key => $value ) {
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
					Xml::input( 'frompage', 50, $this->frompage, [ 'id' => 'frompage' ] ) .
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
				<tr>
					<td></td>
					<td class='mw-input'>" .
					Xml::checkLabel(
						$this->msg( 'import-assign-known-users' )->text(),
						'assignKnownUsers',
						'interwikiAssignKnownUsers',
						$this->assignKnownUsers
					) .
					"</td>
				</tr>
				$importDepth
				<tr>
					<td class='mw-label'>" .
					Xml::label( $this->msg( 'import-comment' )->text(), 'mw-interwiki-comment' ) .
					"</td>
					<td class='mw-input'>" .
					Xml::input( 'log-comment', 50,
						( $this->sourceName === 'interwiki' ? $this->logcomment : '' ),
						[ 'id' => 'mw-interwiki-comment', 'type' => 'text' ] ) . ' ' .
					"</td>
				</tr>
				$mappingSelection
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
