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

	private function newSiteImporter( array $expectedSites ) {
		$store = $this->getMock( 'SiteStore' );
		$store->expects( $this->once() )
			->method( 'saveSites' )
			->with( $expectedSites );

		return new SiteImporter( $store );
	}

	public function provideImportFromXML() {
		return array(

		);
	}

	/**
	 * @dataProvider provideImportFromXML
	 */
	public function testImportFromXML( $xml, array $expectedSites )  {
		$importer = $this->newSiteImporter( $expectedSites );
		$importer->importFromXML( $xml );
	}

	public function testImportFromFile()  {
		!!!
	}

}
