<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Tests\Maintenance\DumpAsserter;

/**
 * Import/export round trip test.
 *
 * @group Database
 * @covers WikiExporter
 * @covers WikiImporter
 */
class ImportExportTest extends MediaWikiLangTestCase {

	public function setUp(): void {
		parent::setUp();

		$slotRoleRegistry = MediaWikiServices::getInstance()->getSlotRoleRegistry();

		if ( !$slotRoleRegistry->isDefinedRole( 'ImportExportTest' ) ) {
			$slotRoleRegistry->defineRoleWithModel( 'ImportExportTest', CONTENT_MODEL_WIKITEXT );
		}
	}

	/**
	 * @param string $schemaVersion
	 *
	 * @return WikiExporter
	 */
	private function getExporter( string $schemaVersion ) {
		$exporter = new WikiExporter( $this->db, WikiExporter::FULL );
		$exporter->setSchemaVersion( $schemaVersion );
		return $exporter;
	}

	/**
	 * @param ImportSource $source
	 *
	 * @return WikiImporter
	 */
	private function getImporter( ImportSource $source ) {
		$config = new HashConfig( [
			'CommandLineMode' => true,
		] );
		$importer = new WikiImporter( $source, $config );
		return $importer;
	}

	/**
	 * @param string $testName
	 *
	 * @return string[]
	 */
	private function getFilesToImport( string $testName ) {
		return glob( __DIR__ . "/../../data/import/$testName.import-*.xml" );
	}

	/**
	 * @param string $name
	 * @param string $schemaVersion
	 *
	 * @return string path of the dump file
	 */
	protected function getDumpTemplatePath( $name, $schemaVersion ) {
		return __DIR__ . "/../../data/import/$name.$schemaVersion.xml";
	}

	/**
	 * @param string $prefix
	 *
	 * @return string[]
	 */
	private function getUniqueNames( string $prefix, array $keys ) {
		$names = [];

		foreach ( $keys as $k ) {
			$names[$k] = "$prefix-$k-" . wfRandomString( 6 );
		}

		return $names;
	}

	/**
	 * @param string $xmlData
	 * @param string[] pageTitles
	 *
	 * @return string
	 */
	private function injectPageTitles( string $xmlData, array $pageTitles ) {
		$keys = array_map( function ( $name ) {
			return "{{{$name}_title}}";
		}, array_keys( $pageTitles ) );

		return str_replace(
			$keys,
			array_values( $pageTitles ),
			$xmlData
		);
	}

	/**
	 * @param Title $title
	 *
	 * @return RevisionRecord[]
	 */
	private function getRevisions( Title $title ) {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$qi = $store->getQueryInfo();

		$conds = [ 'rev_page' => $title->getArticleID() ];
		$opt = [ 'ORDER BY' => 'rev_id ASC' ];

		$rows = $this->db->select(
			$qi['tables'],
			$qi['fields'],
			$conds,
			__METHOD__,
			$opt,
			$qi['joins']
		);

		$status = $store->newRevisionsFromBatch( $rows );
		return $status->getValue();
	}

	/**
	 * @param string[] pageTitles
	 *
	 * @return string[]
	 */
	private function getPageInfoVars( array $pageTitles ) {
		$vars = [];
		foreach ( $pageTitles as $name => $page ) {
			$title = Title::newFromText( $page );

			if ( !$title->exists( Title::READ_LATEST ) ) {
				// map only existing pages, since only they can appear in a dump
				continue;
			}

			$vars[ $name . '_pageid' ] = $title->getArticleID();
			$vars[ $name . '_title' ] = $title->getPrefixedDBkey();
			$vars[ $name . '_namespace' ] = $title->getNamespace();

			$n = 1;
			$revisions = $this->getRevisions( $title );
			foreach ( $revisions as $i => $rev ) {
				$revkey = "{$name}_rev" . $n++;

				$vars[ $revkey . '_id' ] = $rev->getId();
				$vars[ $revkey . '_userid' ] = $rev->getUser()->getId();
			}
		}

		return $vars;
	}

	/**
	 * @return string[]
	 */
	private function getSiteVars( $schemaVersion ) {
		global $wgSitename, $wgDBname, $wgVersion, $wgCapitalLinks;

		$vars = [];
		$vars['mw_version'] = $wgVersion;
		$vars['schema_version'] = $schemaVersion;

		$vars['site_name'] = $wgSitename;
		$vars['project_namespace'] =
			MediaWikiServices::getInstance()->getTitleFormatter()->getNamespaceName(
				NS_PROJECT,
				'Dummy'
			);
		$vars['site_db'] = $wgDBname;
		$vars['site_case'] = $wgCapitalLinks ? 'first-letter' : 'case-sensitive';
		$vars['site_base'] = Title::newMainPage()->getCanonicalURL();
		$vars['site_language'] = MediaWikiServices::getInstance()->getContentLanguage()->getHtmlCode();

		return $vars;
	}

	public function provideImportExport() {
		foreach ( XmlDumpWriter::$supportedSchemas as $schemaVersion ) {
			yield [ 'Basic', $schemaVersion ];
			yield [ 'Dupes', $schemaVersion ];
			yield [ 'Slots', $schemaVersion ];
			yield [ 'Interleaved', $schemaVersion ];
			yield [ 'InterleavedMulti', $schemaVersion ];
			yield [ 'MissingMainContentModel', $schemaVersion ];
			yield [ 'MissingSlotContentModel', $schemaVersion ];
		}
	}

	/**
	 * @dataProvider provideImportExport
	 */
	public function testImportExport( $testName, $schemaVersion ) {
		$asserter = new DumpAsserter( $schemaVersion );

		$filesToImport = $this->getFilesToImport( $testName );
		$fileToExpect = $this->getDumpTemplatePath( "$testName.expected", $schemaVersion );
		$siteInfoExpect = $this->getDumpTemplatePath( 'SiteInfo', $schemaVersion );

		$pageKeys = [ 'page1', 'page2', 'page3', 'page4', ];
		$pageTitles = $this->getUniqueNames( $testName, $pageKeys );

		// import each file
		foreach ( $filesToImport as $fileName ) {
			$xmlData = file_get_contents( $fileName );
			$xmlData = $this->injectPageTitles( $xmlData, $pageTitles );

			$source = new ImportStringSource( $xmlData );
			$importer = $this->getImporter( $source );
			$importer->doImport();
		}

		// write dump
		$exporter = $this->getExporter( $schemaVersion );

		$tmpFile = $this->getNewTempFile();
		$buffer = new DumpFileOutput( $tmpFile );

		$exporter->setOutputSink( $buffer );
		$exporter->openStream();
		$exporter->pagesByName( $pageTitles );
		$exporter->closeStream();

		// determine expected variable values
		$vars = array_merge(
			$this->getSiteVars( $schemaVersion ),
			$this->getPageInfoVars( $pageTitles )
		);

		foreach ( $vars as $key => $value ) {
			$asserter->setVarMapping( $key, $value );
		}

		// sanity check
		$dumpData = file_get_contents( $tmpFile );
		$this->assertNotEmpty( $dumpData, 'Dump XML' );

		// check dump content
		$asserter->assertDumpStart( $tmpFile, $siteInfoExpect );
		$asserter->assertDOM( $fileToExpect );
		$asserter->assertDumpEnd();
	}

}
