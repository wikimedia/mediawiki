<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author This, that and the other
 */

use MediaWiki\Title\ForeignTitle;

/**
 * @covers \MediaWiki\Title\ForeignTitle
 *
 * @group Title
 */
class ForeignTitleTest extends \MediaWikiUnitTestCase {

	public static function basicProvider() {
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
		$this->expectException( RuntimeException::class );
		$title = new ForeignTitle( null, 'this', 'that' );
		$title->getNamespaceId();
	}

	public static function fullTextProvider() {
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
