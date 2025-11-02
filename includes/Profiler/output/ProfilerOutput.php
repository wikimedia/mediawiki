<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Base class for profiling output.
 *
 * @ingroup Profiler
 * @since 1.25
 */
abstract class ProfilerOutput {
	/** @var Profiler */
	protected $collector;
	/** @var LoggerInterface */
	protected $logger;
	/** @var array Configuration of $wgProfiler */
	protected $params;

	/**
	 * @param Profiler $collector The actual profiler
	 * @param array $params Configuration array, passed down from $wgProfiler
	 */
	public function __construct( Profiler $collector, array $params ) {
		$this->collector = $collector;
		$this->params = $params;
		$this->logger = LoggerFactory::getInstance( 'profiler' );
	}

	/**
	 * Can this output type be used?
	 * @return bool
	 */
	public function canUse() {
		return true;
	}

	/**
	 * May the log() try to write to standard output?
	 * @return bool
	 * @since 1.33
	 */
	public function logsToOutput() {
		return false;
	}

	/**
	 * Log MediaWiki-style profiling data.
	 *
	 * For classes that enable logsToOutput(), this must not
	 * be called unless Profiler::setAllowOutput is enabled.
	 *
	 * @param array $stats Result of Profiler::getFunctionStats()
	 */
	abstract public function log( array $stats );
}
