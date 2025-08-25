<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Search\TitleMatcher;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Search\TitleMatcher
 * @group Database
 */
class TitleMatcherTest extends MediaWikiIntegrationTestCase {
	use LinkCacheTestTrait;

	public function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, false );
	}

	public static function nearMatchProvider() {
		return [
			'empty request returns nothing' => [ null, 'en', '', 'Near Match Test' ],
			'with a hash returns nothing' => [ null, 'en', '#near match test', 'Near Match Test' ],
			'wrong seach string returns nothing' => [
				null, 'en', ':', 'Near Match Test'
			],
			'default behaviour exact' => [
				'Near Match Test', 'en', 'Near Match Test', 'Near Match Test'
			],
			'default behaviour uppercased' => [
				'NEAR MATCH TEST', 'en', 'near match test', 'NEAR MATCH TEST'
			],
			'default behaviour first capitalized' => [
				'Near match test', 'en', 'near match test', 'Near match test'
			],
			'default behaviour capitalized' => [
				'Near Match Test', 'en', 'near match test', 'Near Match Test'
			],
			'default behaviour lowercased' => [
				'Near match test', 'en', 'NEAR MATCH TEST', 'Near match test'
			],
			'default behaviour hyphenated' => [
				'Near-Match-Test', 'en', 'near-match-test', 'Near-Match-Test'
			],
			'default behaviour quoted' => [
				'Near Match Test', 'en', '"Near Match Test"', 'Near Match Test'
			],
			'check language with variants direct' => [ 'Near', 'tg', 'near', 'Near' ],
			'check language with variants converted' => [ 'Near', 'tg', 'неар', 'Near' ],
			'no matching' => [ null, 'en', 'near match test', 'Far Match Test' ],
			// Special cases: files
			'file ok' => [ 'File:Example.svg', 'en', 'File:Example.svg', 'File:Example.svg' ],
			'file not ok' => [ null, 'en', 'File:Example_s.svg', 'File:Example.svg' ],
			// Special cases: users
			'user ok' => [ 'User:Superuser', 'en', 'User:Superuser', 'User:Superuser' ],
			'user ok even if no user' => [
				'User:SuperuserNew', 'en', 'User:SuperuserNew', 'User:Superuser'
			],
			'user search use by IP' => [
				'Special:Contributions/132.17.48.1', 'en', 'User:132.17.48.1', 'User:Superuser', true,
			],
			// Special cases: other content types
			'mediawiki ok even if no page' => [
				'MediaWiki:Add New Page', 'en', 'MediaWiki:Add New Page', 'MediaWiki:Add Old Page'
			],
			'Media ok' => [
				'File:Text', 'en', 'Media:Text', 'File:Text', true,
			],
			'Media not ok' => [
				null, 'en', 'Media:Text', 'Media:Text', true,
			],
		];
	}

	/**
	 * @param HashConfig $config
	 * @param string $langCode
	 *
	 * @return TitleMatcher
	 */
	private function getTitleMatcher( HashConfig $config, $langCode ): TitleMatcher {
		$services = $this->getServiceContainer();
		return new TitleMatcher(
			new ServiceOptions( TitleMatcher::CONSTRUCTOR_OPTIONS, $config ),
			$services->getLanguageFactory()->getLanguage( $langCode ),
			$services->getLanguageConverterFactory(),
			$services->getHookContainer(),
			$services->getWikiPageFactory(),
			$services->getUserNameUtils(),
			$services->getRepoGroup(),
			$services->getTitleFactory()
		);
	}

	/**
	 * @dataProvider nearMatchProvider
	 * @covers \MediaWiki\Search\TitleMatcher::getNearMatchInternal
	 * @covers \MediaWiki\Search\TitleMatcher::getNearMatch
	 */
	public function testNearMatch(
		$expected,
		$langCode,
		$searchterm,
		$titleText,
		$enableSearchContributorsByIP = false
	) {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'en' );
		$this->addGoodLinkObject( 42, Title::newFromText( $titleText ) );

		$config = new HashConfig( [
			MainConfigNames::EnableSearchContributorsByIP => $enableSearchContributorsByIP,
		] );

		$matcher = $this->getTitleMatcher( $config, $langCode );
		$title = $matcher->getNearMatch( $searchterm );
		$this->assertEquals( $expected, $title === null ? null : (string)$title );
	}

	public static function hooksProvider() {
		return [
			'SearchGetNearMatchBefore' => [ 'SearchGetNearMatchBefore' ],
			'SearchAfterNoDirectMatch' => [ 'SearchAfterNoDirectMatch' ],
			'SearchGetNearMatch' => [ 'SearchGetNearMatch' ]
		];
	}

	/**
	 * @dataProvider hooksProvider
	 * @covers \MediaWiki\Search\TitleMatcher::getNearMatchInternal
	 * @covers \MediaWiki\Search\TitleMatcher::getNearMatch
	 */
	public function testNearMatch_Hooks( $hook ) {
		$config = new HashConfig( [
			MainConfigNames::EnableSearchContributorsByIP => false,
		] );

		$this->setTemporaryHook( $hook, static function ( $term, &$title ) {
			if ( $term === [ 'Hook' ] || $term === 'Hook' ) {
				$title = Title::makeTitle( NS_MAIN, 'TitleFromHook' );
				return false;
			}
			return null;
		} );

		$matcher = $this->getTitleMatcher( $config, 'en' );
		$title = $matcher->getNearMatch( 'Hook' );
		$this->assertEquals( 'TitleFromHook', $title );

		$this->assertNull( $matcher->getNearMatch( 'OtherHook' ) );
	}

	/**
	 * @covers \MediaWiki\Search\TitleMatcher::getNearMatchResultSet
	 */
	public function testGetNearMatchResultSet() {
		$this->addGoodLinkObject( 42, Title::makeTitle( NS_MAIN, "Test Link" ) );

		$config = new HashConfig( [
			MainConfigNames::EnableSearchContributorsByIP => false,
		] );

		$matcher = $this->getTitleMatcher( $config, 'en' );
		$result = $matcher->getNearMatchResultSet( 'Test Link' );
		$this->assertSame( 1, $result->numRows() );

		$result = $matcher->getNearMatchResultSet( 'Test Link Wrong' );
		$this->assertSame( 0, $result->numRows() );
	}

	protected function tearDown(): void {
		Title::clearCaches();
		parent::tearDown();
	}
}
