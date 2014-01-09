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
 * @covers HtmlPageLinkRenderer
 */
class HtmlPageLinkRendererTest extends MediaWikiTestCase {

	public function provideFormat() {
		return array(
			array( new TitleValue( NS_MAIN, 'Foo_Bar' ), 'Foo Bar', '!<a .*href=".*?Foo_Bar.*?".*?>Foo Bar</a>!' ),
			array( new TitleValue( NS_USER, 'Hansi_Maier', 'stuff' ), 'Hansi Maier\'s Stuff', '!<a .*href=".*?User:Hansi_Maier.*?#stuff.*?".*?>Hansi Maier\'s Stuff</a>!' ),
		);
	}

	/**
	 * @dataProvider provideFormat
	 */
	public function testFormat( TitleValue $title, $text, $pattern ) {
		$lang = Language::factory( 'en' );

		$formatter = new UrlTitleFormatter( $lang );
		$renderer = new HtmlPageLinkRenderer( $formatter );
		$actual = $renderer->renderLink( $title, $text );

		$this->assertRegExp( $pattern, $actual );
	}
}
