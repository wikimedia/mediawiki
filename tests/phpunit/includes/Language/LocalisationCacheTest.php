<?php

namespace MediaWiki\Tests\Language;

use LocalisationCache;
use MediaWiki\Tests\Mocks\Language\MockLocalisationCacheTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWikiIntegrationTestCase;
use UnexpectedValueException;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Language
 * @group Database
 * @covers \LocalisationCache
 * @author Niklas Laxström
 */
class LocalisationCacheTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;
	use MockLocalisationCacheTrait;

	public function testPluralRulesFallback() {
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
		$lc->recache( 'ba' );
		$messages = $lc->getItem( 'ba', 'messages' );

		// Fallbacks are only used to fill missing data
		$this->assertSame( 'ba', $messages['present-ba'] );
		$this->assertSame( 'ru', $messages['present-ru'] );
		$this->assertSame( 'en', $messages['present-en'] );
	}

	public function testRecacheFallbacksWithHooks() {
		// Use hook to provide updates for messages. This is what the
		// LocalisationUpdate extension does. See T70781.

		$lc = $this->getMockLocalisationCache( [
			'LocalisationCacheRecacheFallback' =>
				static function (
					LocalisationCache $lc,
					$code,
					array &$cache
				) {
					if ( $code === 'ru' ) {
						$cache['messages']['present-ba'] = 'ru-override';
						$cache['messages']['present-ru'] = 'ru-override';
						$cache['messages']['present-en'] = 'ru-override';
					}
				}
		] );
		$lc->recache( 'ba' );
		$messages = $lc->getItem( 'ba', 'messages' );

		// Updates provided by hooks follow the normal fallback order.
		$this->assertSame( 'ba', $messages['present-ba'] );
		$this->assertSame( 'ru-override', $messages['present-ru'] );
		$this->assertSame( 'ru-override', $messages['present-en'] );
	}

	public function testRecacheExtensionMessagesFiles(): void {
		global $IP;

		// first, recache the l10n cache and test it
		$lc = $this->getMockLocalisationCache( [], [
			'ExtensionMessagesFiles' => [
				__METHOD__ => "$IP/tests/phpunit/data/localisationcache/ExtensionMessagesFiles.php",
			]
		] );
		$lc->recache( 'de' );
		$this->assertExtensionMessagesFiles( $lc );

		// then, make another l10n cache sharing the first one’s LCStore and test that (T343375)
		$lc = $this->getMockLocalisationCache( [], [
			'ExtensionMessagesFiles' => [
				__METHOD__ => "$IP/tests/phpunit/data/localisationcache/ExtensionMessagesFiles.php",
			]
		] );
		// no recache this time, but load only the core data first by getting the fallbackSequence
		$lc->getItem( 'de', 'fallbackSequence' );
		$this->assertExtensionMessagesFiles( $lc );
	}

	public function testRecacheTranslationAliasesDirs(): void {
		global $IP;

		$lc = $this->getMockLocalisationCache( [], [
			'TranslationAliasesDirs' => [
				__METHOD__ => "$IP/tests/phpunit/data/localisationcache/translation-alias/"
			]
		] );

		$lc->recache( 'nl' );
		$specialPageAliases = $lc->getItem( 'nl', 'specialPageAliases' );
		$this->assertSame(
			[ "Vertalersmeldingen(TEST)", "NotifyTranslators(TEST)" ],
			$specialPageAliases['NotifyTranslators'],
			'specialPageAliases can be set in TranslationAliasesDirs'
		);
		$this->assertSame(
			[ 'ActieveGebruikers(TEST)', 'ActieveGebruikers', 'ActiveUsers' ],
			$specialPageAliases['Activeusers'],
			'specialPageAliases from extension/core files are merged'
		);

		$lc->recache( 'pt' );
		$specialPageAliases = $lc->getItem( 'pt', 'specialPageAliases' );
		$this->assertSame(
			[ 'Utilizadores_activos(TEST)', 'Utilizadores_activos', 'Usuários_ativos', 'ActiveUsers' ],
			$specialPageAliases['Activeusers'],
			'specialPageAliases from extension/core files and fallback languages are merged'
		);

		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessageMatches( '/invalid key:/i' );
		$lc->recache( 'fr' );
	}

	/**
	 * Assert that the given LocalisationCache, which should be configured with
	 * ExtensionMessagesFiles containing the ExtensionMessagesFiles.php test fixture file,
	 * contains the expected data.
	 */
	private function assertExtensionMessagesFiles( LocalisationCache $lc ): void {
		$specialPageAliases = $lc->getItem( 'de', 'specialPageAliases' );
		$this->assertSame(
			[ 'LokalisierungsPufferTest' ],
			$specialPageAliases['LocalisationCacheTest'],
			'specialPageAliases can be set in ExtensionMessagesFiles'
		);
		$this->assertSame(
			[ 'Aktive_Benutzer*innen', 'Aktive_Benutzer', 'ActiveFolx', 'ActiveUsers' ],
			$specialPageAliases['Activeusers'],
			'specialPageAliases from extension/core files and fallback languages are merged'
		);
		$namespaceNames = $lc->getItem( 'de', 'namespaceNames' );
		$this->assertSame(
			'LokalisierungsPufferTest',
			$namespaceNames[98]
		);
		$this->assertFalse(
			$lc->getItem( 'de', 'rtl' ),
			'rtl cannot be set in ExtensionMessagesFiles'
		);
	}

	public function testLoadCoreDataAvoidsInitLanguage(): void {
		$lc = $this->getMockLocalisationCache();

		$lc->getItem( 'de', 'fallback' );
		$lc->getItem( 'de', 'rtl' );
		$lc->getItem( 'de', 'fallbackSequence' );
		$lc->getItem( 'de', 'originalFallbackSequence' );

		$this->assertArrayNotHasKey( 'de',
			TestingAccessWrapper::newFromObject( $lc )->initialisedLangs );
	}

	public function testShallowFallbackForInvalidCode(): void {
		$lc = $this->getMockLocalisationCache();
		$invalidCode = '!invalid!';

		$this->assertSame( false, $lc->getItem( $invalidCode, 'rtl' ) );
		$this->assertSame( 'windows-1252', $lc->getItem( $invalidCode, 'fallback8bitEncoding' ) );
	}
}
