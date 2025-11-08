<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author This, that and the other
 */

use MediaWiki\MainConfigNames;
use MediaWiki\Title\ForeignTitle;
use MediaWiki\Title\NaiveImportTitleFactory;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Title\NaiveImportTitleFactory
 *
 * @group Title
 *
 * TODO convert to unit tests
 */
class NaiveImportTitleFactoryTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'en',
			MainConfigNames::ExtraNamespaces => [ 100 => 'Portal' ],
		] );
	}

	public static function basicProvider() {
		return [
			[
				new ForeignTitle( 0, '', 'MainNamespaceArticle' ),
				'MainNamespaceArticle'
			],
			[
				new ForeignTitle( null, '', 'MainNamespaceArticle' ),
				'MainNamespaceArticle'
			],
			[
				new ForeignTitle( 1, 'Discussion', 'Nice_talk' ),
				'Talk:Nice_talk'
			],
			[
				new ForeignTitle( 0, '', 'Bogus:Nice_talk' ),
				'Bogus:Nice_talk'
			],
			[
				new ForeignTitle( 100, 'Bogus', 'Nice_talk' ),
				'Bogus:Nice_talk' // not Portal:Nice_talk
			],
			[
				new ForeignTitle( 1, 'Bogus', 'Nice_talk' ),
				'Talk:Nice_talk' // not Bogus:Nice_talk
			],
			[
				new ForeignTitle( 100, 'Portal', 'Nice_talk' ),
				'Portal:Nice_talk'
			],
			[
				new ForeignTitle( 724, 'Portal', 'Nice_talk' ),
				'Portal:Nice_talk'
			],
			[
				new ForeignTitle( 2, 'Portal', 'Nice_talk' ),
				'User:Nice_talk'
			],
		];
	}

	/**
	 * @dataProvider basicProvider
	 */
	public function testBasic( ForeignTitle $foreignTitle, $titleText ) {
		$factory = new NaiveImportTitleFactory(
			$this->getServiceContainer()->getContentLanguage(),
			$this->getServiceContainer()->getNamespaceInfo(),
			$this->getServiceContainer()->getTitleFactory()
		);
		$testTitle = $factory->createTitleFromForeignTitle( $foreignTitle );
		$title = Title::newFromText( $titleText );

		$this->assertTrue( $title->equals( $testTitle ) );
	}
}
