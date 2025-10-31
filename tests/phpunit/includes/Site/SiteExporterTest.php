<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Site;

use DOMDocument;
use InvalidArgumentException;
use MediaWiki\Site\MediaWikiSite;
use MediaWiki\Site\Site;
use MediaWiki\Site\SiteExporter;
use MediaWiki\Site\SiteImporter;
use MediaWiki\Site\SiteList;
use MediaWiki\Site\SiteStore;
use MediaWikiIntegrationTestCase;

/**
 * @ingroup Site
 * @ingroup Test
 *
 * @group Site
 *
 * @covers \MediaWiki\Site\SiteExporter
 *
 * @author Daniel Kinzler
 */
class SiteExporterTest extends MediaWikiIntegrationTestCase {

	public function testConstructor_InvalidArgument() {
		$this->expectException( InvalidArgumentException::class );

		new SiteExporter( 'Foo' );
	}

	public function testExportSites() {
		$foo = Site::newForType( Site::TYPE_UNKNOWN );
		$foo->setGlobalId( 'Foo' );

		$acme = Site::newForType( Site::TYPE_UNKNOWN );
		$acme->setGlobalId( 'acme.com' );
		$acme->setGroup( 'Test' );
		$acme->addLocalId( Site::ID_INTERWIKI, 'acme' );
		$acme->setPath( Site::PATH_LINK, 'http://acme.com/' );

		$tmp = tmpfile();
		$exporter = new SiteExporter( $tmp );

		$exporter->exportSites( [ $foo, $acme ] );

		fseek( $tmp, 0 );
		$xml = fread( $tmp, 16 * 1024 );

		$this->assertStringContainsString( '<sites ', $xml );
		$this->assertStringContainsString( '<site>', $xml );
		$this->assertStringContainsString( '<globalid>Foo</globalid>', $xml );
		$this->assertStringContainsString( '</site>', $xml );
		$this->assertStringContainsString( '<globalid>acme.com</globalid>', $xml );
		$this->assertStringContainsString( '<group>Test</group>', $xml );
		$this->assertStringContainsString( '<localid type="interwiki">acme</localid>', $xml );
		$this->assertStringContainsString( '<path type="link">http://acme.com/</path>', $xml );
		$this->assertStringContainsString( '</sites>', $xml );

		$xsdFile = __DIR__ . '/../../../../docs/sitelist-1.0.xsd';
		$xsdData = file_get_contents( $xsdFile );

		$document = new DOMDocument();
		$document->loadXML( $xml, LIBXML_NONET );
		$document->schemaValidateSource( $xsdData );
	}

	private function newSiteStore( SiteList $sites ) {
		$store = $this->createMock( SiteStore::class );

		$store->expects( $this->once() )
			->method( 'saveSites' )
			->willReturnCallback( static function ( $moreSites ) use ( $sites ) {
				foreach ( $moreSites as $site ) {
					$sites->setSite( $site );
				}
			} );

		$store->method( 'getSites' )
			->willReturn( new SiteList() );

		return $store;
	}

	public static function provideRoundTrip() {
		$foo = Site::newForType( Site::TYPE_UNKNOWN );
		$foo->setGlobalId( 'Foo' );

		$acme = Site::newForType( Site::TYPE_UNKNOWN );
		$acme->setGlobalId( 'acme.com' );
		$acme->setGroup( 'Test' );
		$acme->addLocalId( Site::ID_INTERWIKI, 'acme' );
		$acme->setPath( Site::PATH_LINK, 'http://acme.com/' );

		$dewiki = Site::newForType( Site::TYPE_MEDIAWIKI );
		$dewiki->setGlobalId( 'dewiki' );
		$dewiki->setGroup( 'wikipedia' );
		$dewiki->setForward( true );
		$dewiki->addLocalId( Site::ID_INTERWIKI, 'wikipedia' );
		$dewiki->addLocalId( Site::ID_EQUIVALENT, 'de' );
		$dewiki->setPath( Site::PATH_LINK, 'http://de.wikipedia.org/w/' );
		$dewiki->setPath( MediaWikiSite::PATH_PAGE, 'http://de.wikipedia.org/wiki/' );
		$dewiki->setSource( 'meta.wikimedia.org' );

		return [
			'empty' => [
				new SiteList()
			],

			'some' => [
				new SiteList( [ $foo, $acme, $dewiki ] ),
			],
		];
	}

	/**
	 * @dataProvider provideRoundTrip
	 */
	public function testRoundTrip( SiteList $sites ) {
		$tmp = tmpfile();
		$exporter = new SiteExporter( $tmp );

		$exporter->exportSites( $sites );

		fseek( $tmp, 0 );
		$xml = fread( $tmp, 16 * 1024 );

		$actualSites = new SiteList();
		$store = $this->newSiteStore( $actualSites );

		$importer = new SiteImporter( $store );
		$importer->importFromXML( $xml );

		$this->assertEquals( $sites, $actualSites );
	}

}
