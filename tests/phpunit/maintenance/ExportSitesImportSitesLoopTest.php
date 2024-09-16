<?php

namespace MediaWiki\Tests\Maintenance;

use ExportSites;
use ImportSites;
use MediaWiki\Site\MediaWikiSite;
use MediaWiki\Site\Site;

/**
 * Tests that a XML file exported by {@link ExportSites} can be imported by {@link ImportSites}.
 *
 * @covers ExportSites
 * @covers ImportSites
 * @group Database
 * @author Dreamy Jazz
 */
class ExportSitesImportSitesLoopTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		// No-op, as the ->maintenance property is not used. We will need to extend MaintenanceBaseTestCase to hide
		// any output from the scripts.
		return ExportSites::class;
	}

	private function setUpSitesStoreForTest() {
		// Copied, with modification, from SiteExporter::provideRoundTrip
		$foo = Site::newForType( Site::TYPE_UNKNOWN );
		$foo->setGlobalId( 'Foo' );

		$dewiki = Site::newForType( Site::TYPE_MEDIAWIKI );
		$dewiki->setGlobalId( 'dewiki' );
		$dewiki->setGroup( 'wikipedia' );
		$dewiki->setForward( true );
		$dewiki->addLocalId( Site::ID_INTERWIKI, 'wikipedia' );
		$dewiki->addLocalId( Site::ID_EQUIVALENT, 'de' );
		$dewiki->setPath( Site::PATH_LINK, 'http://de.wikipedia.org/w/' );
		$dewiki->setPath( MediaWikiSite::PATH_PAGE, 'http://de.wikipedia.org/wiki/' );
		$dewiki->setSource( 'meta.wikimedia.org' );

		$this->getServiceContainer()->getSiteStore()->saveSites( [ $foo, $dewiki ] );
		return $this->getServiceContainer()->getSiteLookup()->getSites();
	}

	public function testExportAndThenImportLoop() {
		// Test structure inspired by SiteExporterTest::testRoundTrip
		$sitesConfigBeforeTest = $this->setUpSitesStoreForTest();
		// Export the site config to a file using the ExportSites maintenance script
		$xmlFilename = $this->getNewTempFile();
		$importSites = new ExportSites();
		$importSites->setArg( 'file', $xmlFilename );
		$importSites->execute();
		// Blank the site config
		$this->getServiceContainer()->getSiteStore()->clear();
		$this->truncateTables( [ 'sites', 'site_identifiers' ] );
		// Check that the site config is actually empty
		$sitesConfigHalfWayThroughTest = $this->getServiceContainer()->getSiteLookup()->getSites();
		$this->assertSame( 0, $sitesConfigHalfWayThroughTest->count() );
		// Import the site config from the file using the ImportSites maintenance script
		$importSites = new ImportSites();
		$importSites->setArg( 'file', $xmlFilename );
		$importSites->execute();
		// Check that the config is now the same as at the start of the test
		$sitesConfigAfterTest = $this->getServiceContainer()->getSiteStore()->getSites();
		$this->assertEquals( $sitesConfigBeforeTest, $sitesConfigAfterTest );
	}
}
