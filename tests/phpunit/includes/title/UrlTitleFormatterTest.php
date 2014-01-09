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
 * @covers UrlTitleFormatter
 *
 * @group Title
 */
class UrlTitleFormatterTest extends MediaWikiTestCase {

	public function provideFormat() {
		//NOTE: the output is based on the output generated from the mock TitleFormatter,
		//      which uses TitleValue's toString() method.

		return array(
			array( new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Foo Bar', 'quux' ), 'ns' . NS_MAIN . ':Foo_Bar#quux' ),
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Hansi_Maier', 'some stuff' ), 'ns' . NS_USER . ':Hansi_Maier#some_stuff' ),
			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_USER_TALK, 'Hansi Maier' ), 'ns' . NS_USER_TALK . ':Hansi_Maier#' ),
		);
	}

	/**
	 * @dataProvider provideFormat
	 */
	public function testFormat( TitleValue $title, $expected ) {
		$titleFormatter = $this->getMock( 'TitleFormatter' );
		$titleFormatter->expects( $this->any() )
			->method( 'format' )
			->will( $this->returnCallback( function( TitleValue $title ) {
				return "$title";
			} ) );

		$formatter = new UrlTitleFormatter( $titleFormatter );
		$actual = $formatter->format( $title );

		$this->assertEquals( $expected, $actual );
	}
}
