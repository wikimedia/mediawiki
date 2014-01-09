<?php
/**
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
 * @license GPL 2+
 * @author Daniel Kinzler
 */

/**
 * @covers WikitextPageLinkRenderer
 *
 * @group Title
 */
class WikitextPageLinkRendererTest extends MediaWikiTestCase {

	public function provideFormat() {
		//NOTE: the link target is using the output generated from the mock TitleFormatter,
		//      which uses TitleValue's toString() method.

		return array(
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Foo Bar' ), 'Foo Bar', '[[:ns0:Foo Bar#|Foo Bar]]' ),
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Hansi_Maier', 'stuff' ), 'Hansi Maier\'s Stuff', '[[:ns2:Hansi_Maier#stuff|Hansi Maier\'s Stuff]]' ),
		);
	}

	/**
	 * @dataProvider provideFormat
	 */
	public function testFormat( TitleValue $title, $text, $expected ) {
		$formatter = $this->getMock( 'TitleFormatter' );
		$formatter->expects( $this->any() )
			->method( 'format' )
			->will( $this->returnCallback(
				function( TitleValue $title ) {
					return "$title";
				}
			));

		$renderer = new WikitextPageLinkRenderer( $formatter );
		$actual = $renderer->renderLink( $title, $text );

		$this->assertEquals( $expected, $actual );
	}
}
