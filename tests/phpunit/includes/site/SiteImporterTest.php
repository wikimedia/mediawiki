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
 * @since 1.21
 *
 * @ingroup Site
 * @ingroup Test
 *
 * @group Site
 *
 * @covers SiteImporter
 *
 * @licence GNU GPL v2+
 * @author Daniel Kinzler
 */
class SiteImporterTest extends PHPUnit_Framework_TestCase {

	private function newSiteImporter( array $expectedSites, $errorCount ) {
		$store = $this->getMock( 'SiteStore' );
		$store->expects( $this->once() )
			->method( 'saveSites' )
			->with( $expectedSites );

		$errorHandler = $this->getMock( 'Psr\Log\LoggerInterface' );
		$errorHandler->expects( $this->exactly( $errorCount ) )
			->method( 'error' );

		$importer = new SiteImporter( $store );
		$importer->setExceptionCallback( array( $errorHandler, 'error' ) );

		return $importer;
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
				'<Sites></Sites>',
				array(),
			),
			'no sites' => array(
				'<Sites><Foo><GlobalID>Foo</GlobalID></Foo><Bar><quux>Bla</quux></Bar></Sites>',
				array(),
			),
			'minimal' => array(
				'<Sites>' .
					'<Site><GlobalID>Foo</GlobalID></Site>' .
				'</Sites>',
				array( $foo ),
			),
			'full' => array(
				'<Sites>' .
					'<Site><GlobalID>Foo</GlobalID></Site>' .
					'<Site>' .
						'<GlobalID>acme.com</GlobalID>' .
						'<LocalID type="interwiki">acme</LocalID>' .
						'<Group>Test</Group>' .
						'<Path type="link">http://acme.com/</Path>' .
					'</Site>' .
					'<Site type="mediawiki">' .
						'<Source>meta.wikimedia.org</Source>' .
						'<GlobalID>dewiki</GlobalID>' .
						'<LocalID type="interwiki">wikipedia</LocalID>' .
						'<LocalID type="equivalent">de</LocalID>' .
						'<Group>wikipedia</Group>' .
						'<Forward/>' .
						'<Path type="link">http://de.wikipedia.org/w/</Path>' .
						'<Path type="page_path">http://de.wikipedia.org/wiki/</Path>' .
					'</Site>' .
				'</Sites>',
				array( $foo, $acme, $dewiki ),
			),
			'skip' => array(
				'<Sites>' .
					'<Site><GlobalID>Foo</GlobalID></Site>' .
					'<Site><barf>Foo</barf></Site>' .
					'<Site>' .
						'<GlobalID>acme.com</GlobalID>' .
						'<LocalID type="interwiki">acme</LocalID>' .
						'<silly>boop!</silly>' .
						'<Group>Test</Group>' .
						'<Path type="link">http://acme.com/</Path>' .
					'</Site>' .
				'</Sites>',
				array( $foo, $acme ),
				1
			),
		);
	}

	/**
	 * @dataProvider provideImportFromXML
	 */
	public function testImportFromXML( $xml, array $expectedSites, $errorCount = 0 )  {
		$importer = $this->newSiteImporter( $expectedSites, $errorCount );
		$importer->importFromXML( $xml );
	}

	public function testImportFromFile()  {
		$acme = Site::newForType( Site::TYPE_UNKNOWN );
		$acme->setGlobalId( 'acme.com' );
		$acme->setGroup( 'Test' );
		$acme->addLocalId( Site::ID_INTERWIKI, 'acme' );
		$acme->setPath( Site::PATH_LINK, 'http://acme.com/' );

		$importer = $this->newSiteImporter( array( $acme ), 0 );

		$file = __DIR__ . '/SiteImporterTest.xml';
		$importer->importFromFile( $file );
	}

}
