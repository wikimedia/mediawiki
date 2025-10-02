<?php
/**
 * Copyright © 2009 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use Exception;
use ImportStreamSource;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\MainConfigNames;
use WikiImporterFactory;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * API module that imports an XML file like Special:Import does
 *
 * @ingroup API
 */
class ApiImport extends ApiBase {

	private WikiImporterFactory $wikiImporterFactory;

	public function __construct(
		ApiMain $main,
		string $action,
		WikiImporterFactory $wikiImporterFactory
	) {
		parent::__construct( $main, $action );

		$this->wikiImporterFactory = $wikiImporterFactory;
	}

	public function execute() {
		$this->useTransactionalTimeLimit();
		$params = $this->extractRequestParams();

		$this->requireMaxOneParameter( $params, 'namespace', 'rootpage' );

		$isUpload = false;
		if ( isset( $params['interwikisource'] ) ) {
			if ( !$this->getAuthority()->isAllowed( 'import' ) ) {
				$this->dieWithError( 'apierror-cantimport' );
			}
			if ( !isset( $params['interwikipage'] ) ) {
				$this->dieWithError( [ 'apierror-missingparam', 'interwikipage' ] );
			}
			$source = ImportStreamSource::newFromInterwiki(
				$params['interwikisource'],
				$params['interwikipage'],
				$params['fullhistory'],
				$params['templates']
			);
			$usernamePrefix = $params['interwikisource'];
		} else {
			$isUpload = true;
			if ( !$this->getAuthority()->isAllowed( 'importupload' ) ) {
				$this->dieWithError( 'apierror-cantimport-upload' );
			}
			$source = ImportStreamSource::newFromUpload( 'xml' );
			$usernamePrefix = (string)$params['interwikiprefix'];
			if ( $usernamePrefix === '' ) {
				$encParamName = $this->encodeParamName( 'interwikiprefix' );
				$this->dieWithError( [ 'apierror-missingparam', $encParamName ] );
			}
		}
		if ( !$source->isOK() ) {
			$this->dieStatus( $source );
		}

		// Check if user can add the log entry tags which were requested
		if ( $params['tags'] ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $this->getAuthority() );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		$importer = $this->wikiImporterFactory->getWikiImporter( $source->value, $this->getAuthority() );
		if ( isset( $params['namespace'] ) ) {
			$importer->setTargetNamespace( $params['namespace'] );
		} elseif ( isset( $params['rootpage'] ) ) {
			$statusRootPage = $importer->setTargetRootPage( $params['rootpage'] );
			if ( !$statusRootPage->isGood() ) {
				$this->dieStatus( $statusRootPage );
			}
		}
		$importer->setUsernamePrefix( $usernamePrefix, $params['assignknownusers'] );
		$reporter = new ApiImportReporter(
			$importer,
			$isUpload,
			$params['interwikisource'],
			$params['summary'],
			$this
		);
		if ( $params['tags'] ) {
			$reporter->setChangeTags( $params['tags'] );
		}

		try {
			$importer->doImport();
		} catch ( Exception $e ) {
			$this->dieWithException( $e, [ 'wrap' => 'apierror-import-unknownerror' ] );
		}

		$resultData = $reporter->getData();
		$result = $this->getResult();
		ApiResult::setIndexedTagName( $resultData, 'page' );
		$result->addValue( null, $this->getModuleName(), $resultData );
	}

	/**
	 * Returns a list of interwiki prefixes corresponding to each defined import
	 * source.
	 *
	 * @return array
	 * @since 1.27
	 */
	public function getAllowedImportSources() {
		$importSources = $this->getConfig()->get( MainConfigNames::ImportSources );
		$this->getHookRunner()->onImportSources( $importSources );

		$result = [];
		foreach ( $importSources as $key => $value ) {
			if ( is_int( $key ) ) {
				$result[] = $value;
			} else {
				foreach ( $value as $subproject ) {
					$result[] = "$key:$subproject";
				}
			}
		}
		return $result;
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'summary' => null,
			'xml' => [
				ParamValidator::PARAM_TYPE => 'upload',
			],
			'interwikiprefix' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
			'interwikisource' => [
				ParamValidator::PARAM_TYPE => $this->getAllowedImportSources(),
			],
			'interwikipage' => null,
			'fullhistory' => false,
			'templates' => false,
			'namespace' => [
				ParamValidator::PARAM_TYPE => 'namespace'
			],
			'assignknownusers' => false,
			'rootpage' => null,
			'tags' => [
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true,
			],
		];
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=import&interwikisource=meta&interwikipage=Help:ParserFunctions&' .
				'namespace=100&fullhistory=&token=123ABC'
				=> 'apihelp-import-example-import',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Import';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiImport::class, 'ApiImport' );
