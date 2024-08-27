<?php
/**
 * Makes several 'set', 'incr' and 'get' requests on every memcached
 * server and shows a report.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\MainConfigNames;
use Wikimedia\ObjectCache\BagOStuff;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that  makes several 'set', 'incr' and 'get' requests
 * on every memcached server and shows a report.
 *
 * @ingroup Maintenance
 */
class McTest extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			"Makes several operation requests on every cache server and shows a report.\n" .
			"This tests both per-key and batched *Multi() methods as well as WRITE_BACKGROUND.\n" .
			"\"IB\" means \"immediate blocking\" and \"DB\" means \"deferred blocking.\""
		);
		$this->addOption( 'cache', 'Use servers from this $wgObjectCaches store', true, true );
		$this->addOption( 'class', 'Override the store "class" parameter', false, true );
		$this->addOption( 'i', 'Number of iterations', false, true );
		$this->addArg( 'server[:port]', 'Cache server to test, with optional port', false );
	}

	public function execute() {
		$config = $this->getConfig();
		$objectCaches = $config->get( MainConfigNames::ObjectCaches );

		$cacheType = $this->getOption( 'cache', $config->get( MainConfigNames::MainCacheType ) );
		$iterations = $this->getOption( 'i', 100 );
		$classOverride = $this->getOption( 'class' );
		$server = $this->getArg( 0 );

		if ( !isset( $objectCaches[$cacheType] ) ) {
			$this->fatalError( "No configured '$cacheType' cache" );
		}

		if ( $classOverride !== null ) {
			if ( !is_subclass_of( $classOverride, BagOStuff::class ) ) {
				$this->fatalError( "Invalid class '$classOverride' for cache" );
			}
			$class = $classOverride;
		} else {
			$class = $objectCaches[$cacheType]['class'];
		}

		if ( $server !== null ) {
			$servers = [ $server ];
		} else {
			// Note that some caches, like apcu, do not have a server list
			$servers = $objectCaches[$cacheType]['servers'] ?? [ null ];
		}

		// Use longest server string for output alignment
		$maxSrvLen = max( array_map( 'strlen', $servers ) );

		$this->output( "Warming up connections to cache servers..." );
		/** @var BagOStuff[] $cacheByServer */
		$cacheByServer = [];
		foreach ( $servers as $server ) {
			$conf = $objectCaches[$cacheType];
			if ( $server !== null ) {
				$conf['servers'] = [ $server ];
				$host = $server;
			} else {
				$host = 'localhost';
			}
			$cacheByServer[$host] = new $class( $conf );
			$cacheByServer[$host]->get( 'key' );
		}
		$this->output( "done\n" );
		$this->output( "Single and batched operation profiling/test results:\n" );

		$valueByKey = [];
		for ( $i = 1; $i <= $iterations; $i++ ) {
			$valueByKey["test$i"] = 'S' . str_pad( (string)$i, 2048 );
		}

		foreach ( $cacheByServer as $host => $mcc ) {
			$this->output( str_pad( $host, $maxSrvLen ) . "\n" );
			$this->benchmarkSingleKeyOps( $mcc, $valueByKey );
			$this->benchmarkMultiKeyOpsImmediateBlocking( $mcc, $valueByKey );
			$this->benchmarkMultiKeyOpsDeferredBlocking( $mcc, $valueByKey );
		}
	}

	/**
	 * @param BagOStuff $mcc
	 * @param array $valueByKey
	 */
	private function benchmarkSingleKeyOps( BagOStuff $mcc, array $valueByKey ) {
		$add = 0;
		$set = 0;
		$incr = 0;
		$get = 0;
		$delete = 0;

		$keys = array_keys( $valueByKey );
		$count = count( $valueByKey );

		// Clear out any old values
		$mcc->deleteMulti( $keys );

		$time_start = microtime( true );
		foreach ( $valueByKey as $key => $value ) {
			if ( $mcc->add( $key, $value ) ) {
				$add++;
			}
		}
		$addMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		foreach ( $valueByKey as $key => $value ) {
			if ( $mcc->set( $key, $value ) ) {
				$set++;
			}
		}
		$setMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		foreach ( $valueByKey as $key => $value ) {
			if ( $mcc->get( $key ) === $value ) {
				$get++;
			}
		}
		$getMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		foreach ( $keys as $key ) {
			if ( $mcc->delete( $key ) ) {
				$delete++;
			}
		}
		$delMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		foreach ( $keys as $index => $key ) {
			if ( $mcc->incrWithInit( $key, $mcc::TTL_INDEFINITE, $index ) === $index ) {
				$incr++;
			}
		}
		$incrMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$this->output(
			" add: $add/$count {$addMs}ms   " .
			"set: $set/$count {$setMs}ms   " .
			"get: $get/$count ({$getMs}ms)   " .
			"delete: $delete/$count ({$delMs}ms)	" .
			"incr: $incr/$count ({$incrMs}ms)\n"
		);
	}

	/**
	 * @param BagOStuff $mcc
	 * @param array $valueByKey
	 */
	private function benchmarkMultiKeyOpsImmediateBlocking( BagOStuff $mcc, array $valueByKey ) {
		$keys = array_keys( $valueByKey );
		$iterations = count( $valueByKey );

		$time_start = microtime( true );
		$mSetOk = $mcc->setMulti( $valueByKey ) ? '✓' : '✗';
		$mSetMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		$found = $mcc->getMulti( $keys );
		$mGetMs = intval( 1e3 * ( microtime( true ) - $time_start ) );
		$mGetOk = 0;
		foreach ( $found as $key => $value ) {
			$mGetOk += ( $value === $valueByKey[$key] );
		}

		$time_start = microtime( true );
		$mChangeTTLOk = $mcc->changeTTLMulti( $keys, 3600 ) ? '✓' : '✗';
		$mChangeTTTMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		$mDelOk = $mcc->deleteMulti( $keys ) ? '✓' : '✗';
		$mDelMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$this->output(
			" setMulti (IB): $mSetOk {$mSetMs}ms   " .
			"getMulti (IB): $mGetOk/$iterations {$mGetMs}ms   " .
			"changeTTLMulti (IB): $mChangeTTLOk {$mChangeTTTMs}ms   " .
			"deleteMulti (IB): $mDelOk {$mDelMs}ms\n"
		);
	}

	/**
	 * @param BagOStuff $mcc
	 * @param array $valueByKey
	 */
	private function benchmarkMultiKeyOpsDeferredBlocking( BagOStuff $mcc, array $valueByKey ) {
		$keys = array_keys( $valueByKey );
		$iterations = count( $valueByKey );
		$flags = $mcc::WRITE_BACKGROUND;

		$time_start = microtime( true );
		$mSetOk = $mcc->setMulti( $valueByKey, 0, $flags ) ? '✓' : '✗';
		$mSetMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		$found = $mcc->getMulti( $keys );
		$mGetMs = intval( 1e3 * ( microtime( true ) - $time_start ) );
		$mGetOk = 0;
		foreach ( $found as $key => $value ) {
			$mGetOk += ( $value === $valueByKey[$key] );
		}

		$time_start = microtime( true );
		$mChangeTTLOk = $mcc->changeTTLMulti( $keys, 3600, $flags ) ? '✓' : '✗';
		$mChangeTTTMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		$mDelOk = $mcc->deleteMulti( $keys, $flags ) ? '✓' : '✗';
		$mDelMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$this->output(
			" setMulti (DB): $mSetOk {$mSetMs}ms   " .
			"getMulti (DB): $mGetOk/$iterations {$mGetMs}ms   " .
			"changeTTLMulti (DB): $mChangeTTLOk {$mChangeTTTMs}ms   " .
			"deleteMulti (DB): $mDelOk {$mDelMs}ms\n"
		);
	}
}

// @codeCoverageIgnoreStart
$maintClass = McTest::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
