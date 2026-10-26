<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Benchmark
 */

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\LCStoreStaticArray;
use MediaWiki\Language\LocalisationCache;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Benchmarker;
use MediaWiki\Settings\SettingsBuilder;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../includes/Benchmarker.php';
// @codeCoverageIgnoreEnd

/**
 * @see BenchmarkRebuildLocalisationCache
 * @ingroup Benchmark
 */
class BenchmarkLCStore extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmarks LCStoreStaticArray reads.' );
		$this->addOption( 'lang', 'Comma-separated language codes. Default: en,de,zh-hant.', false, true );
		$this->addOption( 'prep', 'Create or update missing LCStore files.', false, false );
	}

	public function finalSetup( SettingsBuilder $settingsBuilder ) {
		parent::finalSetup( $settingsBuilder );
		$settingsBuilder->putConfigValue( MainConfigNames::LocalisationCacheConf, [
			'class' => LocalisationCache::class,
			'storeClass' => LCStoreStaticArray::class,
			'storeDirectory' => false,
			// Like a web request on a Wikimedia Foundation server
			'manualRecache' => true,
			'forceRecache' => false,
		] );
	}

	public function execute() {
		$services = $this->getServiceContainer();
		$codes = explode( ',', $this->getOption( 'lang', 'en,de,zh-hant' ) );
		if ( !ini_get( 'opcache.enable_cli' ) ) {
			$this->output( "Warning: opcache is disabled, use php -d opcache.enable_cli=1\n" );
		}
		$lc = $this->newLocalisationCache();
		// Given LCStoreStaticArray::lateFallback is true, build fallbacks too
		// or else we'd be testing nulls instead.
		$resolved = array_unique( array_merge( $codes, ...array_map(
			$services->getLanguageFallback()->getAll( ... ),
			$codes
		) ) );
		if ( $this->hasOption( 'prep' ) ) {
			foreach ( $resolved as $code ) {
				$lc->recache( $code );
				$this->output( "... recached $code.\n" );
			}
			$this->output( "Preparation done. Rerun without --prep.\n" );
			return;
		}
		foreach ( $resolved as $code ) {
			if ( $lc->isExpired( $code ) ) {
				$this->fatalError( 'One or more l10n cache files is missing or expired. Rerun with --prep.' );
			}
		}

		// Pick random but reproducible messages
		$allKeys = $lc->getSubitemList( 'en', 'messages' );
		mt_srand( 42 );
		$keys = (array)array_rand( array_flip( $allKeys ), 1000 );
		$n = count( $keys );

		$benches = [];
		foreach ( $codes as $code ) {
			$benches["read_n$n($code)"] = [
				'function' => function () use ( $code, $keys ) {
					// Start from an empty in-process cache, as in a new request
					$lc = $this->newLocalisationCache();
					$lc->getItem( $code, 'namespaceNames' );
					$lc->getItem( $code, 'specialPageAliases' );
					$lc->getItem( $code, 'magicWords' );
					// Simulate the main LC part of Message::plain(),
					// normally called via MessageCache::getMessageForLang()
					foreach ( $keys as $key ) {
						$lc->getSubitem( $code, 'messages', $key );
					}
				},
			];
		}
		$this->bench( $benches );
	}

	private function newLocalisationCache(): LocalisationCache {
		$services = $this->getServiceContainer();
		$conf = $services->getMainConfig()->get( MainConfigNames::LocalisationCacheConf );
		$dir = $this->getConfig()->get( MainConfigNames::CacheDirectory );
		if ( !$dir ) {
			$this->fatalError( 'Must set a writable $wgCacheDirectory in LocalSettings.php' );
		}
		return new $conf['class'](
			new ServiceOptions( LocalisationCache::CONSTRUCTOR_OPTIONS, $conf, $services->getMainConfig() ),
			LocalisationCache::getStoreFromConf( $conf, $dir ),
			LoggerFactory::getInstance( 'localisation' ),
			[],
			$services->getLanguageNameUtils(),
			$services->getHookContainer()
		);
	}
}

// @codeCoverageIgnoreStart
$maintClass = BenchmarkLCStore::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
