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
		$user = $this->getUser();
		$params = $this->extractRequestParams();

		$isUpload = false;
		if ( isset( $params['interwikisource'] ) ) {
			if ( !$user->isAllowed( 'import' ) ) {
				$this->dieUsageMsg( 'cantimport' );
			}
			if ( !isset( $params['interwikipage'] ) ) {
				$this->dieUsageMsg( array( 'missingparam', 'interwikipage' ) );
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

		$importer = new WikiImporter( $source->value );
		if ( isset( $params['namespace'] ) ) {
			$importer->setTargetNamespace( $params['namespace'] );
		}
		if ( isset( $params['rootpage'] ) ) {
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
		} catch ( MWException $e ) {
			$this->dieUsageMsg( array( 'import-unknownerror', $e->getMessage() ) );
		}

		$resultData = $reporter->getData();
		$result = $this->getResult();
		$result->setIndexedTagName( $resultData, 'page' );
		$result->addValue( null, $this->getModuleName(), $resultData );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'summary' => null,
			'xml' => array(
				ApiBase::PARAM_TYPE => 'upload',
			),
			'interwikisource' => array(
				ApiBase::PARAM_TYPE => $this->getConfig()->get( 'ImportSources' ),
			),
			'interwikipage' => null,
			'fullhistory' => false,
			'templates' => false,
			'namespace' => array(
				ApiBase::PARAM_TYPE => 'namespace'
			),
			'rootpage' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'summary' => 'Import summary',
			'xml' => 'Uploaded XML file',
			'interwikisource' => 'For interwiki imports: wiki to import from',
			'interwikipage' => 'For interwiki imports: page to import',
			'fullhistory' => 'For interwiki imports: import the full history, not just the current version',
			'templates' => 'For interwiki imports: import all included templates as well',
			'namespace' => 'For interwiki imports: import to this namespace',
			'rootpage' => 'Import as subpage of this page',
		);
	}

	public function getDescription() {
		return array(
			'Import a page from another wiki, or an XML file.',
			'Note that the HTTP POST must be done as a file upload (i.e. using multipart/form-data) when',
			'sending a file for the "xml" parameter.'
		);
	}

	public function needsToken() {
		return 'csrf';
	}

	public function getExamples() {
		return array(
			'api.php?action=import&interwikisource=meta&interwikipage=Help:ParserFunctions&' .
				'namespace=100&fullhistory=&token=123ABC'
				=> 'Import [[meta:Help:Parserfunctions]] to namespace 100 with full history',
		);
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
	private $mResultArr = array();

	/**
	 * @param Title $title
	 * @param Title $origTitle
	 * @param int $revisionCount
	 * @param int $successCount
	 * @param array $pageInfo
	 * @return void
	 */
	function reportPage( $title, $origTitle, $revisionCount, $successCount, $pageInfo ) {
		// Add a result entry
		$r = array();

		if ( $title === null ) {
			# Invalid or non-importable title
			$r['title'] = $pageInfo['title'];
			$r['invalid'] = '';
		} else {
			ApiQueryBase::addTitleInfo( $r, $title );
			$r['revisions'] = intval( $successCount );
		}

		$this->mResultArr[] = $r;

		// Piggyback on the parent to do the logging
		parent::reportPage( $title, $origTitle, $revisionCount, $successCount, $pageInfo );
	}

	function getData() {
		return $this->mResultArr;
	}
}
