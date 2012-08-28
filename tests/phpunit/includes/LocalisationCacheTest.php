<?php

class LocalisationCacheTest extends MediaWikiTestCase {
	public function testPuralRulesFallback() {
		$cache = Language::getLocalisationCache();

		$this->assertEquals(
			$cache->getItem( 'ru', 'pluralRules' ),
			$cache->getItem( 'os', 'pluralRules' ),
			'os plural rules (undefined) fallback to ru (defined)'
		);

		$this->assertEquals(
			$cache->getItem( 'ru', 'compiledPluralRules' ),
			$cache->getItem( 'os', 'compiledPluralRules' ),
			'os compiled plural rules (undefined) fallback to ru (defined)'
		);

		$this->assertNotEquals(
			$cache->getItem( 'ksh', 'pluralRules' ),
			$cache->getItem( 'de', 'pluralRules' ),
			'ksh plural rules (defined) dont fallback to de (defined)'
		);

		$this->assertNotEquals(
			$cache->getItem( 'ksh', 'compiledPluralRules' ),
			$cache->getItem( 'de', 'compiledPluralRules' ),
			'ksh compiled plural rules (defined) dont fallback to de (defined)'
		);
	}
}
