<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author This, that and the other
 */

use MediaWiki\Title\ForeignTitle;
use MediaWiki\Title\NamespaceImportTitleFactory;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Title\NamespaceImportTitleFactory
 *
 * @group Title
 *
 * TODO convert to unit tests
 */
class NamespaceImportTitleFactoryTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->setContentLang( 'en' );
	}

	public static function basicProvider() {
		return [
			[
				new ForeignTitle( 0, '', 'MainNamespaceArticle' ),
				0,
				'MainNamespaceArticle'
			],
			[
				new ForeignTitle( 0, '', 'MainNamespaceArticle' ),
				2,
				'User:MainNamespaceArticle'
			],
			[
				new ForeignTitle( 1, 'Discussion', 'Nice_talk' ),
				0,
				'Nice_talk'
			],
			[
				new ForeignTitle( 0, '', 'Bogus:Nice_talk' ),
				0,
				'Bogus:Nice_talk'
			],
			[
				new ForeignTitle( 0, '', 'Bogus:Nice_talk' ),
				2,
				'User:Bogus:Nice_talk'
			],
		];
	}

	/**
	 * @dataProvider basicProvider
	 */
	public function testBasic( ForeignTitle $foreignTitle, $ns, $titleText ) {
		$factory = new NamespaceImportTitleFactory(
			$this->getServiceContainer()->getNamespaceInfo(),
			$this->getServiceContainer()->getTitleFactory(),
			$ns
		);
		$testTitle = $factory->createTitleFromForeignTitle( $foreignTitle );
		$title = Title::newFromText( $titleText );

		$this->assertTrue( $title->equals( $testTitle ) );
	}
}
