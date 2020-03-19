<?php
/**
 * Copyright © 2009 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 */

/**
 * API module that imports an XML file like Special:Import does
 *
 * @ingroup API
 */
class ApiImport extends ApiBase {

	public function execute() {
		$this->useTransactionalTimeLimit();
		$user = $this->getUser();
		$params = $this->extractRequestParams();

		$this->requireMaxOneParameter( $params, 'namespace', 'rootpage' );

		$isUpload = false;
		if ( isset( $params['interwikisource'] ) ) {
			if ( !$this->getPermissionManager()->userHasRight( $user, 'import' ) ) {
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
			if ( !$this->getPermissionManager()->userHasRight( $user, 'importupload' ) ) {
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
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $user );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		$importer = new WikiImporter( $source->value, $this->getConfig() );
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
			$params['summary']
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
		$importSources = $this->getConfig()->get( 'ImportSources' );
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

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return [
			'summary' => null,
			'xml' => [
				ApiBase::PARAM_TYPE => 'upload',
			],
			'interwikiprefix' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'interwikisource' => [
				ApiBase::PARAM_TYPE => $this->getAllowedImportSources(),
			],
			'interwikipage' => null,
			'fullhistory' => false,
			'templates' => false,
			'namespace' => [
				ApiBase::PARAM_TYPE => 'namespace'
			],
			'assignknownusers' => false,
			'rootpage' => null,
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
		];
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=import&interwikisource=meta&interwikipage=Help:ParserFunctions&' .
				'namespace=100&fullhistory=&token=123ABC'
				=> 'apihelp-import-example-import',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Import';
	}
}
