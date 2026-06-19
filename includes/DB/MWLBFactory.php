<?php
/**
 * Generator of database load balancing objects.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Database
 */

namespace MediaWiki\DB;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Debug\MWDebug;
use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Profiler\Profiler;
use MediaWiki\Profiler\ProfilerStub;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\ConfiguredReadOnlyMode;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LBFactoryMulti;
use Wikimedia\Rdbms\LBFactorySimple;
use Wikimedia\Rdbms\LBFactorySingle;
use Wikimedia\RequestTimeout\CriticalSectionProvider;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Telemetry\TracerInterface;

/**
 * MediaWiki-specific class for generating database load balancers
 *
 * @internal For use by core ServiceWiring only.
 * @ingroup Database
 */
class MWLBFactory {

	/** Cache of already-logged deprecation messages */
	private static array $loggedDeprecations = [];

	private ServiceOptions $options;
	private ConfiguredReadOnlyMode $readOnlyMode;
	private ChronologyProtector $chronologyProtector;
	private BagOStuff $srvCache;
	private WANObjectCache $wanCache;
	private CriticalSectionProvider $csProvider;
	private StatsFactory $statsFactory;
	private TracerInterface $tracer;
	private ?string $uniqueIdentifier;

	/**
	 * @param ConfiguredReadOnlyMode $readOnlyMode
	 * @param ChronologyProtector $chronologyProtector
	 * @param BagOStuff $srvCache
	 * @param WANObjectCache $wanCache
	 * @param CriticalSectionProvider $csProvider
	 * @param StatsFactory $statsFactory
	 * @param TracerInterface $tracer
	 * @param ?string $uniqueIdentifier
	 */
	public function __construct(
		ConfiguredReadOnlyMode $readOnlyMode,
		ChronologyProtector $chronologyProtector,
		BagOStuff $srvCache,
		WANObjectCache $wanCache,
		CriticalSectionProvider $csProvider,
		StatsFactory $statsFactory,
		TracerInterface $tracer,
		?string $uniqueIdentifier = null
	) {
		$this->readOnlyMode = $readOnlyMode;
		$this->chronologyProtector = $chronologyProtector;
		$this->srvCache = $srvCache;
		$this->wanCache = $wanCache;
		$this->csProvider = $csProvider;
		$this->statsFactory = $statsFactory;
		$this->tracer = $tracer;
		$this->uniqueIdentifier = $uniqueIdentifier;
	}

	/**
	 * @param array $lbConf Config for LBFactory::__construct()
	 * @return array
	 * @internal For use with service wiring
	 */
	public function applyServices( array $lbConf ): array {
		if ( Profiler::instance() instanceof ProfilerStub ) {
			$profilerCallback = null;
		} else {
			$profilerCallback = static function ( $section ) {
				return Profiler::instance()->scopedProfileIn( $section );
			};
		}

		$lbConf += [
			'profiler' => $profilerCallback,
			'trxProfiler' => Profiler::instance()->getTransactionProfiler(),
			'logger' => LoggerFactory::getInstance( 'rdbms' ),
			'errorLogger' => MWExceptionHandler::logException( ... ),
			'deprecationLogger' => static::logDeprecation( ... ),
			'statsFactory' => $this->statsFactory,
			'cliMode' => MW_ENTRY_POINT === 'cli',
			'readOnlyReason' => $this->readOnlyMode->getReason(),
			'criticalSectionProvider' => $this->csProvider,
			'uniqueIdentifier' => $this->uniqueIdentifier,
		];
		$lbConf['chronologyProtector'] = $this->chronologyProtector;
		$lbConf['srvCache'] = $this->srvCache;
		$lbConf['wanCache'] = $this->wanCache;
		$lbConf['tracer'] = $this->tracer;

		return $lbConf;
	}

	/**
	 * Decide which LBFactory class to use.
	 *
	 * @internal For use by ServiceWiring
	 * @param array $config (e.g. $wgLBFactoryConf)
	 * @return class-string<LBFactory>
	 */
	public function getLBFactoryClass( array $config ): string {
		$compat = [
			// For LocalSettings.php compat after removing underscores (since 1.23).
			'LBFactory_Single' => LBFactorySingle::class,
			'LBFactory_Simple' => LBFactorySimple::class,
			'LBFactory_Multi' => LBFactoryMulti::class,
			// For LocalSettings.php compat after moving classes to namespaces (since 1.29).
			'LBFactorySingle' => LBFactorySingle::class,
			'LBFactorySimple' => LBFactorySimple::class,
			'LBFactoryMulti' => LBFactoryMulti::class
		];

		$class = $config['class'];
		return $compat[$class] ?? $class;
	}

	public function setDomainAliases( ILBFactory $lbFactory ): void {
		$domain = DatabaseDomain::newFromId( $lbFactory->getLocalDomainID() );
		// For compatibility with hyphenated $wgDBname values on older wikis, handle callers
		// that assume corresponding database domain IDs and wiki IDs have identical values
		$rawLocalDomain = strlen( $domain->getTablePrefix() )
			? "{$domain->getDatabase()}-{$domain->getTablePrefix()}"
			: (string)$domain->getDatabase();

		$lbFactory->setDomainAliases( [ $rawLocalDomain => $domain ] );
	}

	/**
	 * Log a database deprecation warning
	 *
	 * @param string $msg Deprecation message
	 */
	public static function logDeprecation( string $msg ): void {
		if ( isset( self::$loggedDeprecations[$msg] ) ) {
			return;
		}
		self::$loggedDeprecations[$msg] = true;
		MWDebug::sendRawDeprecated( $msg, true, wfGetCaller() );
	}
}

/** @deprecated class alias since 1.46 */
class_alias( MWLBFactory::class, 'MWLBFactory' );
