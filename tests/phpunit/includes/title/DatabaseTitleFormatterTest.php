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
 * @covers DatabaseTitleFormatter
 *
 * @group Title
 */
class DatabaseTitleFormatterTest extends MediaWikiTestCase {

	public function provideFormat() {
		return array(
			array( new TitleValue( TitleValue::TITLE_FORM, NS_MAIN, 'Foo Bar' ), 'Foo_Bar' ),
			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_USER, 'Hansi Maier', 'stuff' ), 'Hansi_Maier' ),
		);
	}

	/**
	 * @dataProvider provideFormat
	 */
	public function testFormat( TitleValue $title, $expected ) {
		$formatter = new DatabaseTitleFormatter();
		$actual = $formatter->format( $title );

		$this->assertEquals( $expected, $actual );
	}
}
