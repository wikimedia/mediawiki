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
 */
class UrlTitleFormatterTest extends MediaWikiTestCase {

	public function provideFormat() {
		return array(
			array( new TitleValue( NS_MAIN, 'Foo Bar' ), true, 'Foo_Bar' ),
			array( new TitleValue( NS_USER, 'Hansi Maier', 'some stuff' ), false, 'Hansi_Maier#some_stuff' ),
			array( new TitleValue( NS_USER_TALK, 'Hansi Maier' ), true, 'User_talk:Hansi_Maier' ),
		);
	}

	/**
	 * @dataProvider provideFormat
	 */
	public function testFormat( TitleValue $title, $showNamespace, $expected ) {
		$lang = Language::factory( 'en' );

		$formatter = new UrlTitleFormatter( $lang, $showNamespace );
		$actual = $formatter->format( $title );

		$this->assertEquals( $expected, $actual );
	}
}
