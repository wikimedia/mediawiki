<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Stats\StatsFactory;

/**
 * Database load monitoring interface
 *
 * @internal This class should not be called outside of LoadBalancer
 * @ingroup Database
 */
interface ILoadMonitor {
	public const STATE_UP = 'up';
	public const STATE_CONN_COUNT = 'conn_count';
	public const STATE_AS_OF = 'time';

	/**
	 * Construct a new LoadMonitor with a given LoadBalancer parent
	 *
	 * @param ILoadBalancer $lb LoadBalancer this instance serves
	 * @param BagOStuff $sCache Local server memory cache
	 * @param WANObjectCache $wCache Local cluster memory cache
	 * @param LoggerInterface $logger PSR-3 logger instance
	 * @param StatsFactory $statsFactory StatsFactory instance
	 * @param array $options Additional parameters include:
	 *   - maxConnCount: maximum number of connections before circuit breaking applies
	 *      [default: infinity]
	 */
	public function __construct(
		ILoadBalancer $lb,
		BagOStuff $sCache,
		WANObjectCache $wCache,
		LoggerInterface $logger,
		StatsFactory $statsFactory,
		$options
	);

	/**
	 * Perform load ratio adjustment before deciding which server to use
	 *
	 * @param array<int,int|float> &$weightByServer Map of (server index => weight)
	 */
	public function scaleLoads( array &$weightByServer );
}
