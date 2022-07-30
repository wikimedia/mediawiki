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

use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\PermissionManager;

/**
 * MediaWiki page data importer
 *
 * @ingroup SpecialPage
 */
class SpecialImport extends SpecialPage {
	/** @var array */
	private $importSources;

	/** @var PermissionManager */
	private $permManager;

	/** @var WikiImporterFactory */
	private $wikiImporterFactory;

	/**
	 * @param PermissionManager $permManager
	 * @param WikiImporterFactory $wikiImporterFactory
	 */
	public function __construct(
		PermissionManager $permManager,
		WikiImporterFactory $wikiImporterFactory
	) {
		parent::__construct( 'Import', 'import' );

		$this->permManager = $permManager;
		$this->wikiImporterFactory = $wikiImporterFactory;
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
	public function execute( $par ) {
		$this->useTransactionalTimeLimit();

		$this->setHeaders();
		$this->outputHeader();

		$this->importSources = $this->getConfig()->get( MainConfigNames::ImportSources );
		// Avoid phan error by checking the type
		if ( !is_array( $this->importSources ) ) {
			throw new UnexpectedValueException( '$wgImportSources must be an array' );
		}
		$this->getHookRunner()->onImportSources( $this->importSources );

		$user = $this->getUser();
		if ( !$this->permManager->userHasAnyRight( $user, 'import', 'importupload' ) ) {
			throw new PermissionsError( 'import' );
		}

		# @todo Allow PermissionManager::getPermissionErrors() to take an array
		$errors = wfMergeErrorArrays(
			$this->permManager->getPermissionErrors(
				'import', $user, $this->getPageTitle(),
				PermissionManager::RIGOR_FULL,
				[ 'ns-specialprotected', 'badaccess-group0', 'badaccess-groups' ]
			),
			$this->permManager->getPermissionErrors(
				'importupload', $user, $this->getPageTitle(),
				PermissionManager::RIGOR_FULL,
				[ 'ns-specialprotected', 'badaccess-group0', 'badaccess-groups' ]
			)
		);

		if ( $errors ) {
			throw new PermissionsError( 'import', $errors );
		}

		$this->getOutput()->addModules( 'mediawiki.misc-authed-ooui' );
		$this->getOutput()->addModuleStyles( 'mediawiki.special.import.styles.ooui' );

		$this->checkReadOnly();

		$request = $this->getRequest();
		if ( $request->wasPosted() && $request->getRawVal( 'action' ) == 'submit' ) {
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
		$sourceName = $request->getVal( 'source' );
		$assignKnownUsers = $request->getCheck( 'assignKnownUsers' );

		$logcomment = $request->getText( 'log-comment' );
		$pageLinkDepth = $this->getConfig()->get( MainConfigNames::ExportMaxLinkDepth ) == 0
			? 0
			: $request->getIntOrNull( 'pagelink-depth' );

		$rootpage = '';
		$mapping = $request->getVal( 'mapping' );
		$namespace = $this->getConfig()->get( MainConfigNames::ImportTargetNamespace );
		if ( $mapping === 'namespace' ) {
			$namespace = $request->getIntOrNull( 'namespace' );
		} elseif ( $mapping === 'subpage' ) {
			$rootpage = $request->getText( 'rootpage' );
		}

		$user = $this->getUser();

		$fullInterwikiPrefix = null;
		if ( !$user->matchEditToken( $request->getVal( 'wpEditToken' ) ) ) {
			$source = Status::newFatal( 'import-token-mismatch' );
		} elseif ( $sourceName === 'upload' ) {
			$isUpload = true;
			$fullInterwikiPrefix = $request->getVal( 'usernamePrefix' );
			if ( $this->permManager->userHasRight( $user, 'importupload' ) ) {
				$source = ImportStreamSource::newFromUpload( "xmlimport" );
			} else {
				throw new PermissionsError( 'importupload' );
			}
		} elseif ( $sourceName === 'interwiki' ) {
			if ( !$this->permManager->userHasRight( $user, 'import' ) ) {
				throw new PermissionsError( 'import' );
			}
			$interwiki = $fullInterwikiPrefix = $request->getVal( 'interwiki' );
			// does this interwiki have subprojects?
			$hasSubprojects = array_key_exists( $interwiki, $this->importSources );
			if ( !$hasSubprojects && !in_array( $interwiki, $this->importSources ) ) {
				$source = Status::newFatal( "import-invalid-interwiki" );
			} else {
				$subproject = null;
				if ( $hasSubprojects ) {
					$subproject = $request->getVal( 'subproject' );
					// Trim "project::" prefix added for JS
					if ( strpos( $subproject, $interwiki . '::' ) === 0 ) {
						$subproject = substr( $subproject, strlen( $interwiki . '::' ) );
					}
					$fullInterwikiPrefix .= ':' . $subproject;
				}
				if ( $hasSubprojects &&
					!in_array( $subproject, $this->importSources[$interwiki] )
				) {
					$source = Status::newFatal( 'import-invalid-interwiki' );
				} else {
					$history = $request->getCheck( 'interwikiHistory' );
					$frompage = $request->getText( 'frompage' );
					$includeTemplates = $request->getCheck( 'interwikiTemplates' );
					$source = ImportStreamSource::newFromInterwiki(
						// @phan-suppress-next-line PhanTypeMismatchArgumentNullable False positive
						$fullInterwikiPrefix,
						$frompage,
						$history,
						$includeTemplates,
						$pageLinkDepth );
				}
			}
		} else {
			$source = Status::newFatal( "importunknownsource" );
		}

		if ( (string)$fullInterwikiPrefix === '' ) {
			$source->fatal( 'importnoprefix' );
		}

		$out = $this->getOutput();
		if ( !$source->isGood() ) {
			$out->wrapWikiTextAsInterface( 'error',
				$this->msg( 'importfailed', $source->getWikiText( false, false, $this->getLanguage() ) )
					->plain()
			);
		} else {
			$importer = $this->wikiImporterFactory->getWikiImporter( $source->value );
			if ( $namespace !== null ) {
				$importer->setTargetNamespace( $namespace );
			} elseif ( $rootpage !== null ) {
				$statusRootPage = $importer->setTargetRootPage( $rootpage );
				if ( !$statusRootPage->isGood() ) {
					$out->wrapWikiMsg(
						"<div class=\"error\">\n$1\n</div>",
						[
							'import-options-wrong',
							$statusRootPage->getWikiText( false, false, $this->getLanguage() ),
							count( $statusRootPage->getErrorsArray() )
						]
					);

					return;
				}
			}
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable False positive
			$importer->setUsernamePrefix( $fullInterwikiPrefix, $assignKnownUsers );

			$out->addWikiMsg( "importstart" );

			$reporter = new ImportReporter(
				$importer,
				$isUpload,
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable False positive
				$fullInterwikiPrefix,
				$logcomment
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
					[ 'importfailed', $result->getWikiText( false, false, $this->getLanguage() ) ]
				);
			} else {
				# Success!
				$out->addWikiMsg( 'importsuccess' );
			}
			$out->addHTML( '<hr />' );
		}
	}

	private function getMappingFormPart( $sourceName ) {
		$defaultNamespace = $this->getConfig()->get( MainConfigNames::ImportTargetNamespace );
		return [
			'mapping' => [
				'type' => 'radio',
				'name' => 'mapping',
				// IDs: mw-import-mapping-interwiki, mw-import-mapping-upload
				'id' => "mw-import-mapping-$sourceName",
				'options-messages' => [
					'import-mapping-default' => 'default',
					'import-mapping-namespace' => 'namespace',
					'import-mapping-subpage' => 'subpage'
				],
				'default' => $defaultNamespace !== null ? 'namespace' : 'default'
			],
			'namespace' => [
				'type' => 'namespaceselect',
				'name' => 'namespace',
				// IDs: mw-import-namespace-interwiki, mw-import-namespace-upload
				'id' => "mw-import-namespace-$sourceName",
				'default' => $defaultNamespace ?: '',
				'all' => null,
				'disable-if' => [ '!==', 'mapping', 'namespace' ],
			],
			'rootpage' => [
				'type' => 'text',
				'name' => 'rootpage',
				// Should be "mw-import-...", but we keep the inaccurate ID for compat
				// IDs: mw-interwiki-rootpage-interwiki, mw-interwiki-rootpage-upload
				'id' => "mw-interwiki-rootpage-$sourceName",
				'disable-if' => [ '!==', 'mapping', 'subpage' ],
			],
		];
	}

	private function showForm() {
		$action = $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] );
		$user = $this->getUser();
		$out = $this->getOutput();
		$this->addHelpLink( 'https://meta.wikimedia.org/wiki/Special:MyLanguage/Help:Import', true );

		$interwikiFormDescriptor = [];
		$uploadFormDescriptor = [];

		if ( $this->permManager->userHasRight( $user, 'importupload' ) ) {
			$mappingSelection = $this->getMappingFormPart( 'upload' );
			$uploadFormDescriptor += [
				'intro' => [
					'type' => 'info',
					'raw' => true,
					'default' => $this->msg( 'importtext' )->parseAsBlock()
				],
				'xmlimport' => [
					'type' => 'file',
					'name' => 'xmlimport',
					'accept' => [ 'application/xml', 'text/xml' ],
					'label-message' => 'import-upload-filename',
					'required' => true,
				],
				'usernamePrefix' => [
					'type' => 'text',
					'name' => 'usernamePrefix',
					'label-message' => 'import-upload-username-prefix',
					// TODO: Is this field required?
				],
				'assignKnownUsers' => [
					'type' => 'check',
					'name' => 'assignKnownUsers',
					'label-message' => 'import-assign-known-users'
				],
				'log-comment' => [
					'type' => 'text',
					'name' => 'log-comment',
					'label-message' => 'import-comment'
				],
				'source' => [
					'type' => 'hidden',
					'name' => 'source',
					'default' => 'upload',
					'id' => '',
				],
			];

			$uploadFormDescriptor += $mappingSelection;

			$htmlForm = HTMLForm::factory( 'ooui', $uploadFormDescriptor, $this->getContext() );
			$htmlForm->setAction( $action );
			$htmlForm->setId( 'mw-import-upload-form' );
			$htmlForm->setWrapperLegendMsg( 'import-upload' );
			$htmlForm->setSubmitTextMsg( 'uploadbtn' );
			$htmlForm->prepareForm()->displayForm( false );

		} elseif ( empty( $this->importSources ) ) {
			$out->addWikiMsg( 'importnosources' );
		}

		if ( $this->permManager->userHasRight( $user, 'import' ) && !empty( $this->importSources ) ) {

			$projects = [];
			$needSubprojectField = false;
			foreach ( $this->importSources as $key => $value ) {
				if ( is_int( $key ) ) {
					$key = $value;
				} elseif ( $value !== $key ) {
					$needSubprojectField = true;
				}

				$projects[ $key ] = $key;
			}

			$interwikiFormDescriptor += [
				'intro' => [
					'type' => 'info',
					'raw' => true,
					'default' => $this->msg( 'import-interwiki-text' )->parseAsBlock()
				],
				'interwiki' => [
					'type' => 'select',
					'name' => 'interwiki',
					'label-message' => 'import-interwiki-sourcewiki',
					'options' => $projects
				],
			];

			if ( $needSubprojectField ) {
				$subprojects = [];
				foreach ( $this->importSources as $key => $value ) {
					if ( is_array( $value ) ) {
						foreach ( $value as $subproject ) {
							$subprojects[ $subproject ] = $key . '::' . $subproject;
						}
					}
				}

				$interwikiFormDescriptor += [
					'subproject' => [
						'type' => 'select',
						'name' => 'subproject',
						'options' => $subprojects
					]
				];
			}

			$interwikiFormDescriptor += [
				'frompage' => [
					'type' => 'text',
					'name' => 'frompage',
					'label-message' => 'import-interwiki-sourcepage'
				],
				'interwikiHistory' => [
					'type' => 'check',
					'name' => 'interwikiHistory',
					'label-message' => 'import-interwiki-history'
				],
				'interwikiTemplates' => [
					'type' => 'check',
					'name' => 'interwikiTemplates',
					'label-message' => 'import-interwiki-templates'
				],
				'assignKnownUsers' => [
					'type' => 'check',
					'name' => 'assignKnownUsers',
					'label-message' => 'import-assign-known-users'
				],
			];

			if ( $this->getConfig()->get( MainConfigNames::ExportMaxLinkDepth ) > 0 ) {
				$interwikiFormDescriptor += [
					'pagelink-depth' => [
						'type' => 'int',
						'name' => 'pagelink-depth',
						'label-message' => 'export-pagelinks',
						'default' => 0
					]
				];
			}

			$interwikiFormDescriptor += [
				'log-comment' => [
					'type' => 'text',
					'name' => 'log-comment',
					'label-message' => 'import-comment'
				],
				'source' => [
					'type' => 'hidden',
					'name' => 'source',
					'default' => 'interwiki',
					'id' => '',
				],
			];
			$mappingSelection = $this->getMappingFormPart( 'interwiki' );

			$interwikiFormDescriptor += $mappingSelection;

			$htmlForm = HTMLForm::factory( 'ooui', $interwikiFormDescriptor, $this->getContext() );
			$htmlForm->setAction( $action );
			$htmlForm->setId( 'mw-import-interwiki-form' );
			$htmlForm->setWrapperLegendMsg( 'importinterwiki' );
			$htmlForm->setSubmitTextMsg( 'import-interwiki-submit' );
			$htmlForm->prepareForm()->displayForm( false );
		}
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
