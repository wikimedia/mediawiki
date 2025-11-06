<?php
/**
 * Copyright © 2003,2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use Exception;
use ImportReporter;
use ImportStreamSource;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use UnexpectedValueException;
use WikiImporterFactory;
use Wikimedia\Rdbms\DBError;
use Wikimedia\RequestTimeout\TimeoutException;

/**
 * MediaWiki page data importer
 *
 * @ingroup SpecialPage
 */
class SpecialImport extends SpecialPage {
	/** @var array */
	private $importSources;

	private WikiImporterFactory $wikiImporterFactory;

	public function __construct(
		WikiImporterFactory $wikiImporterFactory
	) {
		parent::__construct( 'Import', 'import' );

		$this->wikiImporterFactory = $wikiImporterFactory;
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/**
	 * Execute
	 * @param string|null $par
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

		$authority = $this->getAuthority();
		$statusImport = PermissionStatus::newEmpty();
		$authority->isDefinitelyAllowed( 'import', $statusImport );
		$statusImportUpload = PermissionStatus::newEmpty();
		$authority->isDefinitelyAllowed( 'importupload', $statusImportUpload );
		// Only show an error here if the user can't import using either method.
		// If they can use at least one of the methods, allow access, and checks elsewhere
		// will ensure that we only show the form(s) they can use.
		$out = $this->getOutput();
		if ( !$statusImport->isGood() && !$statusImportUpload->isGood() ) {
			// Show separate messages for each check. There isn't a good way to merge them into a single
			// message if the checks failed for different reasons.
			$out->prepareErrorPage();
			$out->setPageTitleMsg( $this->msg( 'permissionserrors' ) );
			$out->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
			$out->addWikiTextAsInterface( Html::errorBox(
				$out->formatPermissionStatus( $statusImport, 'import' )
			) );
			$out->addWikiTextAsInterface( Html::errorBox(
				$out->formatPermissionStatus( $statusImportUpload, 'importupload' )
			) );
			return;
		}

		$out->addModules( 'mediawiki.misc-authed-ooui' );
		$out->addModuleStyles( 'mediawiki.special.import.styles.ooui' );

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
		$authority = $this->getAuthority();
		$status = PermissionStatus::newEmpty();

		$fullInterwikiPrefix = null;
		if ( !$user->matchEditToken( $request->getVal( 'wpEditToken' ) ) ) {
			$source = Status::newFatal( 'import-token-mismatch' );
		} elseif ( $sourceName === 'upload' ) {
			$isUpload = true;
			$fullInterwikiPrefix = $request->getVal( 'usernamePrefix' );
			if ( $authority->authorizeAction( 'importupload', $status ) ) {
				$source = ImportStreamSource::newFromUpload( "xmlimport" );
			} else {
				throw new PermissionsError( 'importupload', $status );
			}
		} elseif ( $sourceName === 'interwiki' ) {
			if ( !$authority->authorizeAction( 'import', $status ) ) {
				throw new PermissionsError( 'import', $status );
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
					if ( str_starts_with( $subproject, $interwiki . '::' ) ) {
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
			$out->wrapWikiMsg(
				Html::errorBox( '$1' ),
				[
					'importfailed',
					$source->getWikiText( false, false, $this->getLanguage() ),
					count( $source->getMessages() )
				]
			);
		} else {
			$importer = $this->wikiImporterFactory->getWikiImporter( $source->value, $this->getAuthority() );
			if ( $namespace !== null ) {
				$importer->setTargetNamespace( $namespace );
			} elseif ( $rootpage !== null ) {
				$statusRootPage = $importer->setTargetRootPage( $rootpage );
				if ( !$statusRootPage->isGood() ) {
					$out->wrapWikiMsg(
						Html::errorBox( '$1' ),
						[
							'import-options-wrong',
							$statusRootPage->getWikiText( false, false, $this->getLanguage() ),
							count( $statusRootPage->getMessages() )
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
				$logcomment,
				$this->getContext()
			);
			$exception = false;

			$reporter->open();
			try {
				$importer->doImport();
			} catch ( DBError | TimeoutException $e ) {
				// Re-throw exceptions which are not safe to catch (T383933).
				throw $e;
			} catch ( Exception $e ) {
				$exception = $e;
			} finally {
				$result = $reporter->close();
			}

			if ( $exception ) {
				# No source or XML parse error
				$out->wrapWikiMsg(
					Html::errorBox( '$1' ),
					[ 'importfailed', wfEscapeWikiText( $exception->getMessage() ), 1 ]
				);
			} elseif ( !$result->isGood() ) {
				# Zero revisions
				$out->wrapWikiMsg(
					Html::errorBox( '$1' ),
					[
						'importfailed',
						$result->getWikiText( false, false, $this->getLanguage() ),
						count( $result->getMessages() )
					]
				);
			} else {
				# Success!
				$out->addWikiMsg( 'importsuccess' );
			}
		}
	}

	private function getMappingFormPart( string $sourceName ): array {
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
		$authority = $this->getAuthority();
		$out = $this->getOutput();
		$this->addHelpLink( 'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:Import', true );

		$interwikiFormDescriptor = [];
		$uploadFormDescriptor = [];

		if ( $authority->isDefinitelyAllowed( 'importupload' ) ) {
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
					'required' => true,
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

		} elseif ( !$this->importSources ) {
			$out->addWikiMsg( 'importnosources' );
		}

		if ( $authority->isDefinitelyAllowed( 'import' ) && $this->importSources ) {

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

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pagetools';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialImport::class, 'SpecialImport' );
