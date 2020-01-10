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
 * @covers ForeignTitle
 *
 * @group Title
 */
class ForeignTitleTest extends \MediaWikiUnitTestCase {

	public function basicProvider() {
		return [
			[
				new ForeignTitle( 20, 'Contributor', 'JohnDoe' ),
				20, 'Contributor', 'JohnDoe'
			],
			[
				new ForeignTitle( '1', 'Discussion', 'Capital' ),
				1, 'Discussion', 'Capital'
			],
			[
				new ForeignTitle( 0, '', 'MainNamespace' ),
				0, '', 'MainNamespace'
			],
			[
				new ForeignTitle( 4, 'Some ns', 'Article title with spaces' ),
				4, 'Some_ns', 'Article_title_with_spaces'
			],
		];
	}

	/**
	 * @dataProvider basicProvider
	 */
	public function testBasic( ForeignTitle $title, $expectedId, $expectedName,
		$expectedText
	) {
		$this->assertTrue( $title->isNamespaceIdKnown() );
		$this->assertEquals( $expectedId, $title->getNamespaceId() );
		$this->assertEquals( $expectedName, $title->getNamespaceName() );
		$this->assertEquals( $expectedText, $title->getText() );
	}

	public function testUnknownNamespaceCheck() {
		$title = new ForeignTitle( null, 'this', 'that' );

		$this->assertFalse( $title->isNamespaceIdKnown() );
		$this->assertEquals( 'this', $title->getNamespaceName() );
		$this->assertEquals( 'that', $title->getText() );
	}

	public function testUnknownNamespaceError() {
		$this->expectException( MWException::class );
		$title = new ForeignTitle( null, 'this', 'that' );
		$title->getNamespaceId();
	}

	public function fullTextProvider() {
		return [
			[
				new ForeignTitle( 20, 'Contributor', 'JohnDoe' ),
				'Contributor:JohnDoe'
			],
			[
				new ForeignTitle( '1', 'Discussion', 'Capital' ),
				'Discussion:Capital'
			],
			[
				new ForeignTitle( 0, '', 'MainNamespace' ),
				'MainNamespace'
			],
			[
				new ForeignTitle( 4, 'Some ns', 'Article title with spaces' ),
				'Some_ns:Article_title_with_spaces'
			],
		];
	}

	/**
	 * @dataProvider fullTextProvider
	 */
	public function testFullText( ForeignTitle $title, $fullText ) {
		$this->assertEquals( $fullText, $title->getFullText() );
	}
}
