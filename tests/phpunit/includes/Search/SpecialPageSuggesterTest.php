<?php

namespace MediaWiki\Tests\Search;

use MediaWiki\MainConfigNames;
use MediaWiki\Search\SpecialPageSuggester;
use MediaWiki\Title\Title;
use MediaWikiLangTestCase;

/**
 * Unfortunately Special Pages want the database...
 * @group Database
 * @group Search
 * @covers \MediaWiki\Search\SpecialPageSuggester
 */
class SpecialPageSuggesterTest extends MediaWikiLangTestCase {
	protected function setUp(): void {
		parent::setUp();

		// Avoid special pages from extensions interfering with the tests
		$this->overrideConfigValues( [
			MainConfigNames::SpecialPages => [],
			MainConfigNames::Hooks => [],
		] );
	}

	public static function provideSearch(): \Generator {
		yield 'empty search' => [
			'',
			[
				'Special:ActiveUsers',
				'Special:AllMessages',
				'Special:AllPages',
			],
			'en'
		];
		yield 'simple prefix' => [
			'Un',
			[
				'Special:Unblock',
				'Special:UncategorizedCategories',
				'Special:UncategorizedFiles',
			],
			'en'
		];
		yield 'page name' => [ 'EditWatchlist', [], 'en' ];
		yield 'sub pages' => [
			'EditWatchlist/',
			[ 'Special:EditWatchlist/clear', 'Special:EditWatchlist/raw' ],
			'en'
		];
		yield 'prefix on sub pages' => [
			'EditWatchlist/cl',
			[ 'Special:EditWatchlist/clear' ],
			'en'
		];
		yield 'prefix on canonical page' => [
			'ListGroupRight',
			[ 'Special:ListGroupRights' ],
			'sv'
		];
	}

	/**
	 * @dataProvider provideSearch
	 */
	public function testSearch( string $search, array $expected_results, string $lang ): void {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, $lang );
		$specialPageSuggester = new SpecialPageSuggester(
			$this->getServiceContainer()->getSpecialPageFactory(),
			$this->getServiceContainer()->getContentLanguage(),
		);
		$results = $specialPageSuggester->suggest( $search, 3, 0 );
		$title_strings = array_map( static fn ( Title $title ): string => $title->getPrefixedText(), $results );
		$this->assertEquals( $expected_results, $title_strings );
	}

	public function testSearchWithOffset(): void {
		$specialPageSuggester = new SpecialPageSuggester(
			$this->getServiceContainer()->getSpecialPageFactory(),
			$this->getServiceContainer()->getContentLanguage(),
		);
		$results = $specialPageSuggester->suggest( 'Un', 2, 0 );
		$expected_results = [ $results[1] ];
		$results = $specialPageSuggester->suggest( 'Un', 1, 1 );
		$this->assertEquals( $expected_results, $results );
	}

}
