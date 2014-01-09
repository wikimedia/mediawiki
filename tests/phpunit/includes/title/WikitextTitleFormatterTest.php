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
 * @covers WikitextTitleFormatter
 *
 * @group Title
 * @group Database
 *        ^--- needed because of global state in
 */
class WikitextTitleFormatterTest extends MediaWikiTestCase {

	/**
	 * Returns a mock GenderCache that will consider a user "female" if the
	 * first part of the user name ends with "a".
	 *
	 * @return GenderCache
	 */
	private function getGenderCache() {
		$genderCache = $this->getMockBuilder( 'GenderCache' )
			->disableOriginalConstructor()
			->getMock();

		$genderCache->expects( $this->any() )
			->method( 'getGenderOf' )
			->will( $this->returnCallback( function( $userName ) {
				return preg_match( '/^[^- _]+a( |_|$)/u', $userName ) ? 'female' : 'male';
			} ) );

		return $genderCache;
	}

	public function provideFormat() {
		return array(
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_MAIN, 'Foo_Bar' ), 'en', true, 'Foo Bar' ),
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Hansi_Maier', 'stuff' ), 'en', false, 'Hansi Maier#stuff' ),
			array( new TitleValue( TitleValue::UNKNOWN_FORM, NS_USER_TALK, 'Hansi_Maier' ), 'en', true, 'User talk:Hansi Maier' ),
			array( new TitleValue( TitleValue::DBKEY_FORM, NS_USER, 'Lisa_Müller', 'stuff' ), 'de', true, 'Benutzerin:Lisa Müller#stuff' ),
		);
	}

	/**
	 * @dataProvider provideFormat
	 *
	 * @todo Test gendered namespaces. This currently uses global state,
	 * accessing user preferences in the database.
	 */
	public function testFormat( TitleValue $title, $lang, $showNamespace, $expected ) {
		$lang = Language::factory( $lang );
		$genderCache = $this->getGenderCache();

		$formatter = new WikitextTitleFormatter( $lang, $genderCache, $showNamespace );
		$actual = $formatter->format( $title );

		$this->assertEquals( $expected, $actual );
	}
}
