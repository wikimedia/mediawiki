<?php
/**
 *
 *
 * Created on Feb 4, 2009
 *
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
			if ( !$user->isAllowed( 'import' ) ) {
				$this->dieUsageMsg( 'cantimport' );
			}
			if ( !isset( $params['interwikipage'] ) ) {
				$this->dieUsageMsg( [ 'missingparam', 'interwikipage' ] );
			}
			$source = ImportStreamSource::newFromInterwiki(
				$params['interwikisource'],
				$params['interwikipage'],
				$params['fullhistory'],
				$params['templates']
			);
		} else {
			$isUpload = true;
			if ( !$user->isAllowed( 'importupload' ) ) {
				$this->dieUsageMsg( 'cantimport-upload' );
			}
			$source = ImportStreamSource::newFromUpload( 'xml' );
		}
		if ( !$source->isOK() ) {
			$this->dieStatus( $source );
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
		$reporter = new ApiImportReporter(
			$importer,
			$isUpload,
			$params['interwikisource'],
			$params['summary']
		);

		try {
			$importer->doImport();
		} catch ( Exception $e ) {
			$this->dieUsageMsg( [ 'import-unknownerror', $e->getMessage() ] );
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
		Hooks::run( 'ImportSources', [ &$importSources ] );

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
			'interwikisource' => [
				ApiBase::PARAM_TYPE => $this->getAllowedImportSources(),
			],
			'interwikipage' => null,
			'fullhistory' => false,
			'templates' => false,
			'namespace' => [
				ApiBase::PARAM_TYPE => 'namespace'
			],
			'rootpage' => null,
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
		return 'https://www.mediawiki.org/wiki/API:Import';
	}
}

/**
 * Import reporter for the API
 * @ingroup API
 */
class ApiImportReporter extends ImportReporter {
	private $mResultArr = [];

	/**
	 * @param Title $title
	 * @param Title $origTitle
	 * @param int $revisionCount
	 * @param int $successCount
	 * @param array $pageInfo
	 * @return void
	 */
	public function reportPage( $title, $origTitle, $revisionCount, $successCount, $pageInfo ) {
		// Add a result entry
		$r = [];

		if ( $title === null ) {
			# Invalid or non-importable title
			$r['title'] = $pageInfo['title'];
			$r['invalid'] = true;
		} else {
			ApiQueryBase::addTitleInfo( $r, $title );
			$r['revisions'] = intval( $successCount );
		}

		$this->mResultArr[] = $r;

		// Piggyback on the parent to do the logging
		parent::reportPage( $title, $origTitle, $revisionCount, $successCount, $pageInfo );
	}

	public function getData() {
		return $this->mResultArr;
	}
}
