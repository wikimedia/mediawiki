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

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that  makes several 'set', 'incr' and 'get' requests
 * on every memcached server and shows a report.
 *
 * @ingroup Maintenance
 */
class McTest extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( "Makes several 'set', 'incr' and 'get' requests on every"
			. " memcached server and shows a report" );
		$this->addOption( 'i', 'Number of iterations', false, true );
		$this->addOption( 'cache', 'Use servers from this $wgObjectCaches store', false, true );
		$this->addOption( 'driver', 'Either "php" or "pecl"', false, true );
		$this->addArg( 'server[:port]', 'Memcached server to test, with optional port', false );
	}

	public function execute() {
		global $wgMainCacheType, $wgMemCachedTimeout, $wgObjectCaches;

		$memcachedTypes = [ CACHE_MEMCACHED, 'memcached-php', 'memcached-pecl' ];

		$cache = $this->getOption( 'cache' );
		$iterations = $this->getOption( 'i', 100 );
		if ( $cache ) {
			if ( !isset( $wgObjectCaches[$cache] ) ) {
				$this->fatalError( "MediaWiki isn't configured with a cache named '$cache'" );
			}
			$servers = $wgObjectCaches[$cache]['servers'];
		} elseif ( $this->hasArg( 0 ) ) {
			$servers = [ $this->getArg( 0 ) ];
		} elseif ( in_array( $wgMainCacheType, $memcachedTypes, true ) ) {
			global $wgMemCachedServers;
			$servers = $wgMemCachedServers;
		} elseif ( isset( $wgObjectCaches[$wgMainCacheType]['servers'] ) ) {
			$servers = $wgObjectCaches[$wgMainCacheType]['servers'];
		} else {
			$this->fatalError( "MediaWiki isn't configured for Memcached usage" );
		}

		# find out the longest server string to nicely align output later on
		$maxSrvLen = $servers ? max( array_map( 'strlen', $servers ) ) : 0;

		$type = $this->getOption( 'driver', 'php' );
		if ( $type === 'php' ) {
			$class = MemcachedPhpBagOStuff::class;
		} elseif ( $type === 'pecl' ) {
			$class = MemcachedPeclBagOStuff::class;
		} else {
			$this->fatalError( "Invalid driver type '$type'" );
		}

		foreach ( $servers as $server ) {
			$this->output( str_pad( $server, $maxSrvLen ) . "\n" );

			/** @var BagOStuff $mcc */
			$mcc = new $class( [
				'servers' => [ $server ],
				'persistent' => true,
				'timeout' => $wgMemCachedTimeout
			] );

			$this->benchmarkSingleKeyOps( $mcc, $iterations );
			$this->benchmarkMultiKeyOpsImmediateBlocking( $mcc, $iterations );
			$this->benchmarkMultiKeyOpsDeferredBlocking( $mcc, $iterations );
		}
	}

	/**
	 * @param BagOStuff $mcc
	 * @param int $iterations
	 */
	private function benchmarkSingleKeyOps( $mcc, $iterations ) {
		$add = 0;
		$set = 0;
		$incr = 0;
		$get = 0;
		$delete = 0;

		$keys = [];
		for ( $i = 1; $i <= $iterations; $i++ ) {
			$keys[] = "test$i";
		}

		// Clear out any old values
		$mcc->deleteMulti( $keys );

		$time_start = microtime( true );
		foreach ( $keys as $key ) {
			if ( $mcc->add( $key, $i ) ) {
				$add++;
			}
		}
		$addMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		foreach ( $keys as $key ) {
			if ( $mcc->set( $key, $i ) ) {
				$set++;
			}
		}
		$setMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		foreach ( $keys as $key ) {
			if ( !is_null( $mcc->incr( $key, $i ) ) ) {
				$incr++;
			}
		}
		$incrMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		foreach ( $keys as $key ) {
			$value = $mcc->get( $key );
			if ( $value == $i * 2 ) {
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

		$this->output(
			" add: $add/$iterations {$addMs}ms   " .
			"set: $set/$iterations {$setMs}ms   " .
			"incr: $incr/$iterations {$incrMs}ms   " .
			"get: $get/$iterations ({$getMs}ms)   " .
			"delete: $delete/$iterations ({$delMs}ms)\n"
		);
	}

	/**
	 * @param BagOStuff $mcc
	 * @param int $iterations
	 */
	private function benchmarkMultiKeyOpsImmediateBlocking( $mcc, $iterations ) {
		$keysByValue = [];
		for ( $i = 1; $i <= $iterations; $i++ ) {
			$keysByValue["test$i"] = 'S' . str_pad( $i, 2048 );
		}
		$keyList = array_keys( $keysByValue );

		$time_start = microtime( true );
		$mSetOk = $mcc->setMulti( $keysByValue ) ? 'S' : 'F';
		$mSetMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		$found = $mcc->getMulti( $keyList );
		$mGetMs = intval( 1e3 * ( microtime( true ) - $time_start ) );
		$mGetOk = 0;
		foreach ( $found as $key => $value ) {
			$mGetOk += ( $value === $keysByValue[$key] );
		}

		$time_start = microtime( true );
		$mChangeTTLOk = $mcc->changeTTLMulti( $keyList, 3600 ) ? 'S' : 'F';
		$mChangeTTTMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		$mDelOk = $mcc->deleteMulti( $keyList ) ? 'S' : 'F';
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
	 * @param int $iterations
	 */
	private function benchmarkMultiKeyOpsDeferredBlocking( $mcc, $iterations ) {
		$flags = $mcc::WRITE_BACKGROUND;
		$keysByValue = [];
		for ( $i = 1; $i <= $iterations; $i++ ) {
			$keysByValue["test$i"] = 'A' . str_pad( $i, 2048 );
		}
		$keyList = array_keys( $keysByValue );

		$time_start = microtime( true );
		$mSetOk = $mcc->setMulti( $keysByValue, 0, $flags ) ? 'S' : 'F';
		$mSetMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		$found = $mcc->getMulti( $keyList );
		$mGetMs = intval( 1e3 * ( microtime( true ) - $time_start ) );
		$mGetOk = 0;
		foreach ( $found as $key => $value ) {
			$mGetOk += ( $value === $keysByValue[$key] );
		}

		$time_start = microtime( true );
		$mChangeTTLOk = $mcc->changeTTLMulti( $keyList, 3600, $flags ) ? 'S' : 'F';
		$mChangeTTTMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$time_start = microtime( true );
		$mDelOk = $mcc->deleteMulti( $keyList, $flags ) ? 'S' : 'F';
		$mDelMs = intval( 1e3 * ( microtime( true ) - $time_start ) );

		$this->output(
			" setMulti (DB): $mSetOk {$mSetMs}ms   " .
			"getMulti (DB): $mGetOk/$iterations {$mGetMs}ms   " .
			"changeTTLMulti (DB): $mChangeTTLOk {$mChangeTTTMs}ms   " .
			"deleteMulti (DB): $mDelOk {$mDelMs}ms\n"
		);
	}
}

$maintClass = McTest::class;
require_once RUN_MAINTENANCE_IF_MAIN;
