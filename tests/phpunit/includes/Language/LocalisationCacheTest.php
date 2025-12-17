<?php

namespace MediaWiki\Tests\Language;

use FileDependency;
use LCStoreStaticArray;
use MediaWiki\Language\LocalisationCache;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Mocks\Language\MockLocalisationCacheTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWikiIntegrationTestCase;
use UnexpectedValueException;
use Wikimedia\Leximorph\Provider\PluralRules as LeximorphPluralRulesProvider;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Language
 * @group Database
 * @covers \MediaWiki\Language\LocalisationCache
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

	public function testReadPluralFilesAndRegisterDepsRespectsUseLeximorph(): void {
		$this->overrideConfigValue( MainConfigNames::UseLeximorph, false );
		$lc = $this->getMockLocalisationCache( [], [
			MainConfigNames::UseLeximorph => false,
		] );
		$lc->recache( 'en' );

		$deps = TestingAccessWrapper::newFromObject( $lc )->data['en']['deps'];
		$this->assertContainsFileDependency(
			MW_INSTALL_PATH . '/languages/data/plurals.xml',
			$deps
		);
		$this->assertContainsFileDependency(
			MW_INSTALL_PATH . '/languages/data/plurals-mediawiki.xml',
			$deps
		);

		$this->overrideConfigValue( MainConfigNames::UseLeximorph, true );
		$leximorphLc = $this->getMockLocalisationCache( [], [
			MainConfigNames::UseLeximorph => true,
		] );
		$leximorphLc->recache( 'en' );

		$leximorphDeps = TestingAccessWrapper::newFromObject( $leximorphLc )->data['en']['deps'];
		foreach ( LeximorphPluralRulesProvider::PLURAL_FILES as $fileName ) {
			$this->assertContainsFileDependency( $fileName, $leximorphDeps );
		}
	}

	private function assertContainsFileDependency( string $fileName, array $deps ): void {
		$fileName = realpath( $fileName ) ?: $fileName;
		foreach ( $deps as $dep ) {
			if ( !$dep instanceof FileDependency ) {
				continue;
			}
			$depFileName = TestingAccessWrapper::newFromObject( $dep )->filename;
			$depFileName = realpath( $depFileName ) ?: $depFileName;
			if ( $depFileName === $fileName ) {
				$this->addToAssertionCount( 1 );
				return;
			}
		}

		$this->fail( "Deps should contain FileDependency for $fileName" );
	}

	public function testGetExpiredReasonForForcedRebuild(): void {
		$lc = $this->getMockLocalisationCache( [], [
			'forceRecache' => true,
		] );
		$w = TestingAccessWrapper::newFromObject( $lc );
		$dir = $this->getNewTempDirectory();
		$w->store = new LCStoreStaticArray( [ 'directory' => $dir ] );

		// First, create a valid cache
		$lc->recache( 'en' );

		// Clear the recachedLangs tracking so forceRecache triggers again
		$w->recachedLangs = [];

		// Now check expiry - should report forced rebuild
		$expired = $lc->isExpired( 'en' );

		$this->assertTrue( $expired );
		$this->assertSame( 'Forced rebuild requested', $lc->getExpiredReason( 'en' ) );
	}

	public function testGetExpiredReasonForMissingCache(): void {
		$lc = $this->getMockLocalisationCache();

		// Create a fake language code that hasn't been cached yet
		$expired = $lc->isExpired( 'fake-lang-xyz' );

		$this->assertTrue( $expired );
		$reason = $lc->getExpiredReason( 'fake-lang-xyz' );
		$this->assertSame( 'No existing cache', $reason );
	}

	public function testGetExpiredReasonForNonExpiredLanguage(): void {
		$lc = $this->getMockLocalisationCache();

		// Recache to create initial cache
		$lc->recache( 'en' );

		// Now check if it's expired - should be false for a fresh cache
		$expired = $lc->isExpired( 'en' );

		$this->assertFalse( $expired );
		// getExpiredReason should return null if language hasn't expired
		$this->assertNull( $lc->getExpiredReason( 'en' ) );
	}

	public function testIsExpiredInvalidDependencyReason(): void {
		$lc = $this->getMockLocalisationCache();
		$w = TestingAccessWrapper::newFromObject( $lc );
		$dir = $this->getNewTempDirectory();
		$w->store = new LCStoreStaticArray( [ 'directory' => $dir ] );
		$store = $w->store;

		$code = 'xx';
		$store->startWrite( $code );
		$store->set( 'deps', [ new \stdClass() ] );
		$store->set( 'list', [] );
		$store->set( 'preload', [] );
		$store->finishWrite();

		$this->assertTrue( $lc->isExpired( $code ) );
		$this->assertSame( 'stdClass is not a subtype of CacheDependency', $lc->getExpiredReason( $code ) );
	}

	public function testIsExpiredDependencyExpiredReason(): void {
		$lc = $this->getMockLocalisationCache();
		$w = TestingAccessWrapper::newFromObject( $lc );
		$dir = $this->getNewTempDirectory();
		$w->store = new LCStoreStaticArray( [ 'directory' => $dir ] );
		$store = $w->store;

		$code = 'yy';
		$tmpFile = tempnam( $this->getNewTempDirectory(), 'lct' );
		file_put_contents( $tmpFile, 'x' );
		$oldTs = max( 0, filemtime( $tmpFile ) - 100 );
		$dep = new FileDependency( $tmpFile, $oldTs );

		$store->startWrite( $code );
		$store->set( 'deps', [ $dep ] );
		$store->set( 'list', [] );
		$store->set( 'preload', [] );
		$store->finishWrite();

		$this->assertTrue( $lc->isExpired( $code ) );
		$reason = $lc->getExpiredReason( $code );
		$this->assertIsString( $reason );
		$this->assertStringContainsString( 'mtime changed', $reason );
		$this->assertStringContainsString( basename( $tmpFile ), $reason );
	}
}
