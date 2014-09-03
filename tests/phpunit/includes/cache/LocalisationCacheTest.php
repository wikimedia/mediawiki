<?php
/**
 * @group Database
 * @group Cache
 * @covers LocalisationCache
 * @author Niklas Laxström
 */
class LocalisationCacheTest extends MediaWikiTestCase {
	protected function setUp() {
		global $IP;

		parent::setUp();
		$this->setMwGlobals( array(
			'wgMessagesDirs' => array( "$IP/tests/phpunit/data/localisationcache" ),
			'wgExtensionMessagesFiles' => array(),
			'wgHooks' => array(),
		) );
	}

	public function testPuralRulesFallback() {
		$cache = new LocalisationCache( array( 'store' => 'detect' ) );

		$this->assertEquals(
			$cache->getItem( 'ar', 'pluralRules' ),
			$cache->getItem( 'arz', 'pluralRules' ),
			'arz plural rules (undefined) fallback to ar (defined)'
		);

		$this->assertEquals(
			$cache->getItem( 'ar', 'compiledPluralRules' ),
			$cache->getItem( 'arz', 'compiledPluralRules' ),
			'arz compiled plural rules (undefined) fallback to ar (defined)'
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

	public function testRecacheFallbacks() {
		$lc = new LocalisationCache( array( 'store' => 'detect' ) );
		$lc->recache( 'uk' );
		$this->assertEquals(
			array(
				'present-uk' => 'uk',
				'present-ru' => 'ru',
				'present-en' => 'en',
			),
			$lc->getItem( 'uk', 'messages' ),
			'Fallbacks are only used to fill missing data'
		);
	}

	public function testRecacheFallbacksWithHooks() {
		global $wgHooks;

		// Use hook to provide updates for messages. This is what the
		// LocalisationUpdate extension does. See bug 68781.
		$wgHooks['LocalisationCacheRecacheFallback'][] = function (
			LocalisationCache $lc,
			$code,
			array &$cache
		) {
			if ( $code === 'ru' ) {
				$cache['messages']['present-uk'] = 'ru-override';
				$cache['messages']['present-ru'] = 'ru-override';
				$cache['messages']['present-en'] = 'ru-override';
			}
		};

		$lc = new LocalisationCache( array( 'store' => 'detect' ) );
		$lc->recache( 'uk' );
		$this->assertEquals(
			array(
				'present-uk' => 'uk',
				'present-ru' => 'ru-override',
				'present-en' => 'ru-override',
			),
			$lc->getItem( 'uk', 'messages' ),
			'Updates provided by hooks follow the normal fallback order.'
		);
	}
}
