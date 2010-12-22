<?php
/**
 * API for MediaWiki 1.8+
 *
 * Created on Feb 4, 2009
 *
 * Copyright © 2009 Roan Kattouw <Firstname>.<Lastname>@gmail.com
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiBase.php' );
}

/**
 * API module that imports an XML file like Special:Import does
 *
 * @ingroup API
 */
class ApiImport extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		global $wgUser;
		if ( !$wgUser->isAllowed( 'import' ) ) {
			$this->dieUsageMsg( array( 'cantimport' ) );
		}
		$params = $this->extractRequestParams();

		$isUpload = false;
		if ( isset( $params['interwikisource'] ) ) {
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
			if ( !$wgUser->isAllowed( 'importupload' ) ) {
				$this->dieUsageMsg( array( 'cantimport-upload' ) );
			}
			$source = ImportStreamSource::newFromUpload( 'xml' );
		}
		if ( !$source->isOK() ) {
			$this->dieUsageMsg( $source->getErrorsArray() );
		}

		$importer = new WikiImporter( $source->value );
		if ( isset( $params['namespace'] ) ) {
			$importer->setTargetNamespace( $params['namespace'] );
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
		$this->getResult()->setIndexedTagName( $resultData, 'page' );
		$this->getResult()->addValue( null, $this->getModuleName(), $resultData );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		global $wgImportSources;
		return array(
			'token' => null,
			'summary' => null,
			'xml' => null,
			'interwikisource' => array(
				ApiBase::PARAM_TYPE => $wgImportSources
			),
			'interwikipage' => null,
			'fullhistory' => false,
			'templates' => false,
			'namespace' => array(
				ApiBase::PARAM_TYPE => 'namespace'
			)
		);
	}

	public function getParamDescription() {
		return array(
			'token' => 'Import token obtained through prop=info',
			'summary' => 'Import summary',
			'xml' => 'Uploaded XML file',
			'interwikisource' => 'For interwiki imports: wiki to import from',
			'interwikipage' => 'For interwiki imports: page to import',
			'fullhistory' => 'For interwiki imports: import the full history, not just the current version',
			'templates' => 'For interwiki imports: import all included templates as well',
			'namespace' => 'For interwiki imports: import to this namespace',
		);
	}

	public function getDescription() {
		return 'Import a page from another wiki, or an XML file';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'cantimport' ),
			array( 'missingparam', 'interwikipage' ),
			array( 'cantimport-upload' ),
			array( 'import-unknownerror', 'source' ),
			array( 'import-unknownerror', 'result' ),
		) );
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	protected function getExamples() {
		return array(
			'Import [[meta:Help:Parserfunctions]] to namespace 100 with full history:',
			'  api.php?action=import&interwikisource=meta&interwikipage=Help:ParserFunctions&namespace=100&fullhistory=&token=123ABC',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}

/**
 * Import reporter for the API
 * @ingroup API
 */
class ApiImportReporter extends ImportReporter {
	private $mResultArr = array();

	function reportPage( $title, $origTitle, $revisionCount, $successCount, $pageInfo ) {
		// Add a result entry
		$r = array();
		ApiQueryBase::addTitleInfo( $r, $title );
		$r['revisions'] = intval( $successCount );
		$this->mResultArr[] = $r;

		// Piggyback on the parent to do the logging
		parent::reportPage( $title, $origTitle, $revisionCount, $successCount, $pageInfo );
	}

	function getData() {
		return $this->mResultArr;
	}
}
