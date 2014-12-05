<?php
/**
 * @group Database
 * @group Cache
 * @covers LocalisationCache
 * @author Niklas Laxström
 */
class LocalisationCacheTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( array(
			'wgExtensionMessagesFiles' => array(),
			'wgHooks' => array(),
		) );
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|LocalisationCache
	 */
	protected function getMockLocalisationCache() {
		global $IP;
		$lc = $this->getMockBuilder( 'LocalisationCache' )
			->setConstructorArgs( array( array( 'store' => 'detect' ) ) )
			->setMethods( array( 'getMessagesDirs' ) )
			->getMock();
		$lc->expects( $this->any() )->method( 'getMessagesDirs' )
			->will( $this->returnValue(
				array( "$IP/tests/phpunit/data/localisationcache" )
			) );

		return $lc;
	}

	public function testPuralRulesFallback() {
		$cache = $this->getMockLocalisationCache();

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
		$lc = $this->getMockLocalisationCache();
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
		// Use hook to provide updates for messages. This is what the
		// LocalisationUpdate extension does. See bug 68781.
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			'LocalisationCacheRecacheFallback' => array(
				function (
					LocalisationCache $lc,
					$code,
					array &$cache
				) {
					if ( $code === 'ru' ) {
						$cache['messages']['present-uk'] = 'ru-override';
						$cache['messages']['present-ru'] = 'ru-override';
						$cache['messages']['present-en'] = 'ru-override';
					}
				}
			)
		) );

		$lc = $this->getMockLocalisationCache();
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
