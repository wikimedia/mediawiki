<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Benchmark
 */

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\LanguageNameUtils;
use MediaWiki\Language\LCStoreStaticArray;
use MediaWiki\Language\LocalisationCache;
use MediaWiki\Language\LocalisationCacheBulkLoad;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Benchmarker;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../includes/Benchmarker.php';
// @codeCoverageIgnoreEnd

/**
 * @ingroup Benchmark
 */
class BenchmarkLCStoreStaticArray extends Benchmarker {
	/** @inheritDoc */
	protected $defaultCount = 5;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmarks RebuildLocalisationCache with LCStoreStaticArray (runtime and filesize).' );
		$this->addOption( 'lang', 'Comma-separated language codes, or "all". Default: all', false, true );
		$this->addOption( 'outdir', 'Directory to write files to. Default: a temporary directory, removed afterwards',
			false, true );
	}

	public function execute() {
		$services = $this->getServiceContainer();
		$lang = $this->getOption( 'lang', 'all' );
		$outdir = $this->getOption( 'outdir' );
		$dir = $outdir ?? wfTempDir() . '/lcstore-bench-' . getmypid();

		$conf = $this->getConfig()->get( MainConfigNames::LocalisationCacheConf );
		$conf['storeClass'] = LCStoreStaticArray::class;
		$conf['storeDirectory'] = $dir;
		$conf['manualRecache'] = false;
		$conf['forceRecache'] = true;

		$lc = new LocalisationCacheBulkLoad(
			new ServiceOptions( LocalisationCache::CONSTRUCTOR_OPTIONS, $conf, $services->getMainConfig() ),
			LocalisationCache::getStoreFromConf( $conf, false ),
			LoggerFactory::getInstance( 'localisation' ),
			[],
			$services->getLanguageNameUtils(),
			$services->getHookContainer()
		);

		$benches = [];
		if ( $lang === 'all' ) {
			$allCodes = array_keys(
				$services->getLanguageNameUtils()
					->getLanguageNames( LanguageNameUtils::AUTONYMS, LanguageNameUtils::SUPPORTED )
			);
			$benches["recache(all)"] = [
				'function' => static function () use ( $lc, $allCodes ) {
					foreach ( $allCodes as $code ) {
						$lc->recache( $code );
					}
				},
			];
		} else {
			foreach ( explode( ',', $lang ) as $code ) {
				$benches["recache($code)"] = [
					'function' => static function () use ( $lc, $code ) {
						$lc->recache( $code );
					},
				];
			}
		}
		try {
			$this->bench( $benches );

			$total = 0;
			$count = 0;
			$minSize = INF;
			$minFile = '';
			$maxSize = -INF;
			$maxFile = '';
			foreach ( glob( "$dir/*.l10n.php" ) as $file ) {
				$size = filesize( $file );
				$total += $size;
				$count++;
				if ( $size < $minSize ) {
					$minSize = $size;
					$minFile = basename( $file );
				}
				if ( $size > $maxSize ) {
					$maxSize = $size;
					$maxFile = basename( $file );
				}
			}
			$this->output( "File sizes in $dir:\n" );
			$this->output( sprintf( "%' 10s: %s\n",
				'total',
				$this->formatSize( $total )
			) );
			$this->output( sprintf( "%' 10s: %s\n",
				'avg',
				$this->formatSize( $total / $count )
			) );
			$this->output( sprintf( "%' 10s: %s (%s)\n",
				'min',
				$this->formatSize( $minSize ),
				$minFile
			) );
			$this->output( sprintf( "%' 10s: %s (%s)\n",
				'max',
				$this->formatSize( $maxSize ),
				$maxFile
			) );
		} finally {
			if ( $outdir === null ) {
				wfRecursiveRemoveDir( $dir );
			}
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = BenchmarkLCStoreStaticArray::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
