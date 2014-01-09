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
 *
 * @group Title
 */
class HtmlPageLinkRendererTest extends MediaWikiTestCase {

	/**
	 * Returns a mock GenderCache that will return "female" always.
	 *
	 * @return GenderCache
	 */
	private function getGenderCache() {
		$genderCache = $this->getMockBuilder( 'GenderCache' )
			->disableOriginalConstructor()
			->getMock();

		$genderCache->expects( $this->any() )
			->method( 'getGenderOf' )
			->will( $this->returnValue( 'female' ) );

		return $genderCache;
	}

	public function provideFormat() {
		return array(
			array(
				new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Foo_Bar' ),
				'Foo Bar', '!<a .*href=".*?Foo_Bar.*?".*?>Foo Bar</a>!'
			),
			array(
				new TitleValue( TitleValue::UNKNOWN_FORM, NS_USER, 'Hansi_Maier', 'stuff' ),
				'Hansi Maier\'s Stuff', '!<a .*href=".*?User:Hansi_Maier.*?#stuff.*?".*?>Hansi Maier\'s Stuff</a>!'
			),
		);
	}

	/**
	 * @dataProvider provideFormat
	 */
	public function testFormat( TitleValue $title, $text, $pattern ) {
		// NOTE: was of Feb 2014, HtmlPageLinkRenderer *ignores* the
		// WikitextTitleFormatter we pass here, and relies on the Linker
		// class for generating the link! This may break the test e.g.
		// of Linker uses a different language for the namespace names.

		$lang = Language::factory( 'en' );

		$formatter = new WikitextTitleFormatter( $lang, $this->getGenderCache(), false );
		$renderer = new HtmlPageLinkRenderer( $formatter );
		$actual = $renderer->renderLink( $title, $text );

		$this->assertRegExp( $pattern, $actual );
	}
}
