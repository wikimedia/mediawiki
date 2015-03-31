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
 * @author This, that and the other
 */

/**
 * @covers SubpageImportTitleFactory
 *
 * @group Title
 */
class SubpageImportTitleFactoryTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgLanguageCode' => 'en',
			'wgContLang' => Language::factory( 'en' ),
			'wgNamespacesWithSubpages' => array( 0 => false, 2 => true ),
		) );
	}

	public function basicProvider() {
		return array(
			array(
				new ForeignTitle( 0, '', 'MainNamespaceArticle' ),
				Title::newFromText( 'User:Graham' ),
				Title::newFromText( 'User:Graham/MainNamespaceArticle' )
			),
			array(
				new ForeignTitle( 1, 'Discussion', 'Nice_talk' ),
				Title::newFromText( 'User:Graham' ),
				Title::newFromText( 'User:Graham/Discussion:Nice_talk' )
			),
			array(
				new ForeignTitle( 0, '', 'Bogus:Nice_talk' ),
				Title::newFromText( 'User:Graham' ),
				Title::newFromText( 'User:Graham/Bogus:Nice_talk' )
			),
		);
	}

	/**
	 * @dataProvider basicProvider
	 */
	public function testBasic( ForeignTitle $foreignTitle, Title $rootPage,
		Title $title ) {

		$factory = new SubpageImportTitleFactory( $rootPage );
		$testTitle = $factory->createTitleFromForeignTitle( $foreignTitle );

		$this->assertTrue( $testTitle->equals( $title ) );
	}

	public function failureProvider() {
		return array(
			array(
				Title::newFromText( 'Graham' ),
			),
		);
	}

	/**
	 * @dataProvider failureProvider
	 */
	public function testFailures( Title $rootPage ) {
		$this->setExpectedException( 'MWException' );
		new SubpageImportTitleFactory( $rootPage );
	}
}
