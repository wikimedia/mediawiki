<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author This, that and the other
 */

use MediaWiki\Language\Language;
use MediaWiki\Title\ForeignTitle;
use MediaWiki\Title\NaiveForeignTitleFactory;

/**
 * @covers \MediaWiki\Title\NaiveForeignTitleFactory
 *
 * @group Title
 */
class NaiveForeignTitleFactoryTest extends MediaWikiUnitTestCase {

	public static function basicProvider() {
		return [
			[
				'MainNamespaceArticle', 0,
				new ForeignTitle( 0, '', 'MainNamespaceArticle' ),
			],
			[
				'MainNamespaceArticle', null,
				new ForeignTitle( null, '', 'MainNamespaceArticle' ),
			],
			[
				'Talk:Nice_talk', 1,
				new ForeignTitle( 1, 'Talk', 'Nice_talk' ),
			],
			[
				'Bogus:Nice_talk', 0,
				new ForeignTitle( 0, '', 'Bogus:Nice_talk' ),
			],
			[
				'Bogus:Nice_talk', 9000, // non-existent local namespace ID
				new ForeignTitle( 9000, 'Bogus', 'Nice_talk' ),
			],
			[
				'Bogus:Nice_talk', 4, // existing local namespace ID
				new ForeignTitle( 4, 'Bogus', 'Nice_talk' ),
			],
			[
				'Talk:Extra:Nice_talk', 1,
				new ForeignTitle( 1, 'Talk', 'Extra:Nice_talk' ),
			],
			[
				'Talk:Extra:Nice_talk', null,
				new ForeignTitle( null, 'Talk', 'Extra:Nice_talk' ),
			],
		];
	}

	/**
	 * @dataProvider basicProvider
	 */
	public function testBasic( $title, $ns, ForeignTitle $foreignTitle ) {
		$contentLanguage = $this->createMock( Language::class );
		$contentLanguage->method( 'getNsIndex' )
			->willReturnCallback(
				static function ( $text ) {
					$text = strtolower( $text );
					if ( $text === '' ) {
						return NS_MAIN;
					}
					$namespaces = [
						'talk' => NS_TALK,
						'user' => NS_USER,
					];
					return $namespaces[ $text ] ?? false;
				}
			);
		$factory = new NaiveForeignTitleFactory( $contentLanguage );

		$testTitle = $factory->createForeignTitle( $title, $ns );

		$this->assertEquals( $testTitle->isNamespaceIdKnown(),
			$foreignTitle->isNamespaceIdKnown() );

		if (
			$testTitle->isNamespaceIdKnown() &&
			$foreignTitle->isNamespaceIdKnown()
		) {
			$this->assertEquals( $testTitle->getNamespaceId(),
				$foreignTitle->getNamespaceId() );
		}

		$this->assertEquals( $testTitle->getNamespaceName(),
			$foreignTitle->getNamespaceName() );
		$this->assertEquals( $testTitle->getText(), $foreignTitle->getText() );

		$this->assertEquals( str_replace( ' ', '_', $title ),
			$foreignTitle->getFullText() );
	}
}
