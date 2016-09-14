<?php

use Psr\Log\LoggerInterface;

class LoadMonitorNull implements LoadMonitor {
	public function __construct( ILoadBalancer $lb, BagOStuff $sCache, BagOStuff $cCache ) {

	}

	public function setLogger( LoggerInterface $logger ) {
	}

	public function scaleLoads( &$loads, $group = false, $wiki = false ) {

	}

	public function getLagTimes( $serverIndexes, $wiki ) {
		return array_fill_keys( $serverIndexes, 0 );
	}

	public function clearCaches() {

	}
}
