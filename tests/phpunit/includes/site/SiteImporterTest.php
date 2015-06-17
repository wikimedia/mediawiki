<?php

/**
 * Tests for the SiteImporter class.
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
 *
 * @ingroup Site
 * @ingroup Test
 *
 * @group Site
 *
 * @covers SiteImporter
 *
 * @author Daniel Kinzler
 */
class SiteImporterTest extends PHPUnit_Framework_TestCase {

	private function newSiteImporter( array $expectedSites, $errorCount ) {
		$store = $this->getMock( 'SiteStore' );

		$that = $this;
		$store->expects( $this->once() )
			->method( 'saveSites' )
			->will( $this->returnCallback( function ( $sites ) use ( $expectedSites, $that ) {
				$that->assertSitesEqual( $expectedSites, $sites );
			} ) );

		$store->expects( $this->any() )
			->method( 'getSites' )
			->will( $this->returnValue( new SiteList() ) );

		$errorHandler = $this->getMock( 'Psr\Log\LoggerInterface' );
		$errorHandler->expects( $this->exactly( $errorCount ) )
			->method( 'error' );

		$importer = new SiteImporter( $store );
		$importer->setExceptionCallback( array( $errorHandler, 'error' ) );

		return $importer;
	}

	public function assertSitesEqual( $expected, $actual, $message = '' ) {
		$this->assertEquals(
			$this->getSerializedSiteList( $expected ),
			$this->getSerializedSiteList( $actual ),
			$message
		);
	}

	public function provideImportFromXML() {
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

		return array(
			'empty' => array(
				'<sites></sites>',
				array(),
			),
			'no sites' => array(
				'<sites><Foo><globalid>Foo</globalid></Foo><Bar><quux>Bla</quux></Bar></sites>',
				array(),
			),
			'minimal' => array(
				'<sites>' .
					'<site><globalid>Foo</globalid></site>' .
				'</sites>',
				array( $foo ),
			),
			'full' => array(
				'<sites>' .
					'<site><globalid>Foo</globalid></site>' .
					'<site>' .
						'<globalid>acme.com</globalid>' .
						'<localid type="interwiki">acme</localid>' .
						'<group>Test</group>' .
						'<path type="link">http://acme.com/</path>' .
					'</site>' .
					'<site type="mediawiki">' .
						'<source>meta.wikimedia.org</source>' .
						'<globalid>dewiki</globalid>' .
						'<localid type="interwiki">wikipedia</localid>' .
						'<localid type="equivalent">de</localid>' .
						'<group>wikipedia</group>' .
						'<forward/>' .
						'<path type="link">http://de.wikipedia.org/w/</path>' .
						'<path type="page_path">http://de.wikipedia.org/wiki/</path>' .
					'</site>' .
				'</sites>',
				array( $foo, $acme, $dewiki ),
			),
			'skip' => array(
				'<sites>' .
					'<site><globalid>Foo</globalid></site>' .
					'<site><barf>Foo</barf></site>' .
					'<site>' .
						'<globalid>acme.com</globalid>' .
						'<localid type="interwiki">acme</localid>' .
						'<silly>boop!</silly>' .
						'<group>Test</group>' .
						'<path type="link">http://acme.com/</path>' .
					'</site>' .
				'</sites>',
				array( $foo, $acme ),
				1
			),
		);
	}

	/**
	 * @dataProvider provideImportFromXML
	 */
	public function testImportFromXML( $xml, array $expectedSites, $errorCount = 0 ) {
		$importer = $this->newSiteImporter( $expectedSites, $errorCount );
		$importer->importFromXML( $xml );
	}

	public function testImportFromXML_malformed() {
		$this->setExpectedException( 'Exception' );

		$store = $this->getMock( 'SiteStore' );
		$importer = new SiteImporter( $store );
		$importer->importFromXML( 'THIS IS NOT XML' );
	}

	public function testImportFromFile() {
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

		$importer = $this->newSiteImporter( array( $foo, $acme, $dewiki ), 0 );

		$file = __DIR__ . '/SiteImporterTest.xml';
		$importer->importFromFile( $file );
	}

	/**
	 * @param Site[] $sites
	 *
	 * @return array[]
	 */
	private function getSerializedSiteList( $sites ) {
		$serialized = array();

		foreach ( $sites as $site ) {
			$key = $site->getGlobalId();
			$data = unserialize( $site->serialize() );

			$serialized[$key] = $data;
		}

		return $serialized;
	}
}
