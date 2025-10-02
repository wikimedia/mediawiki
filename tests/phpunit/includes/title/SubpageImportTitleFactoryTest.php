<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author This, that and the other
 */

use MediaWiki\MainConfigNames;
use MediaWiki\Title\ForeignTitle;
use MediaWiki\Title\SubpageImportTitleFactory;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Title\SubpageImportTitleFactory
 *
 * @group Title
 *
 * TODO convert to Unit tests
 */
class SubpageImportTitleFactoryTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'en',
			MainConfigNames::NamespacesWithSubpages => [ 0 => false, 2 => true ],
		] );
	}

	private function newSubpageImportTitleFactory( Title $rootPage ) {
		return new SubpageImportTitleFactory(
			$this->getServiceContainer()->getNamespaceInfo(),
			$this->getServiceContainer()->getTitleFactory(),
			$rootPage
		);
	}

	public static function basicProvider() {
		return [
			[
				new ForeignTitle( 0, '', 'MainNamespaceArticle' ),
				Title::makeTitle( NS_USER, 'Graham' ),
				Title::makeTitle( NS_USER, 'Graham/MainNamespaceArticle' )
			],
			[
				new ForeignTitle( 1, 'Discussion', 'Nice_talk' ),
				Title::makeTitle( NS_USER, 'Graham' ),
				Title::makeTitle( NS_USER, 'Graham/Discussion:Nice_talk' )
			],
			[
				new ForeignTitle( 0, '', 'Bogus:Nice_talk' ),
				Title::makeTitle( NS_USER, 'Graham' ),
				Title::makeTitle( NS_USER, 'Graham/Bogus:Nice_talk' )
			],
		];
	}

	/**
	 * @dataProvider basicProvider
	 */
	public function testBasic( ForeignTitle $foreignTitle, Title $rootPage,
		Title $title
	) {
		$factory = $this->newSubpageImportTitleFactory( $rootPage );
		$testTitle = $factory->createTitleFromForeignTitle( $foreignTitle );

		$this->assertTrue( $testTitle->equals( $title ) );
	}

	public function testInvalidNamespace() {
		$this->expectException( InvalidArgumentException::class );
		$this->newSubpageImportTitleFactory( Title::makeTitle( NS_MAIN, 'Graham' ) );
	}
}
