<?php

/**
 * @covers SearchNearMatcher
 */
class SearchNearMatcherTest extends \PHPUnit\Framework\TestCase {
	public function nearMatchProvider() {
		return [
			'empty request returns nothing' => [ null, 'en', '' ],
			'default behaviour' => [ 'Near Match Test', 'en', 'near match test' ],
			'with a hash returns nothing' => [ null, 'en', '#near match test' ],
		];
	}

	/**
	 * @dataProvider nearMatchProvider
	 */
	public function testNearMatch( $expected, $langCode, $searchterm ) {
		$linkCache = MediaWiki\MediaWikiServices::getInstance()->getLinkCache();
		$linkCache->addGoodLinkObj( 42, Title::newFromText( 'Near Match Test' ) );
		$config = new HashConfig( [
			'EnableSearchContributorsByIP' => false,
		] );
		$lang = Language::factory( $langCode );
		$matcher = new SearchNearMatcher( $config, $lang );

		$title = $matcher->getNearMatch( $searchterm );
		$this->assertEquals( $expected, $title === null ? null : (string)$title );
	}

	function tearDown() {
		Title::clearCaches();
		parent::tearDown();
	}
}
