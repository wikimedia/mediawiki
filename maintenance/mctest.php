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
		$this->addArg( 'server[:port]', 'Memcached server to test, with optional port', false );
	}

	public function execute() {
		global $wgMainCacheType, $wgMemCachedTimeout, $wgObjectCaches;

		$cache = $this->getOption( 'cache' );
		$iterations = $this->getOption( 'i', 100 );
		if ( $cache ) {
			if ( !isset( $wgObjectCaches[$cache] ) ) {
				$this->fatalError( "MediaWiki isn't configured with a cache named '$cache'" );
			}
			$servers = $wgObjectCaches[$cache]['servers'];
		} elseif ( $this->hasArg() ) {
			$servers = [ $this->getArg() ];
		} elseif ( $wgMainCacheType === CACHE_MEMCACHED ) {
			global $wgMemCachedServers;
			$servers = $wgMemCachedServers;
		} elseif ( isset( $wgObjectCaches[$wgMainCacheType]['servers'] ) ) {
			$servers = $wgObjectCaches[$wgMainCacheType]['servers'];
		} else {
			$this->fatalError( "MediaWiki isn't configured for Memcached usage" );
		}

		# find out the longest server string to nicely align output later on
		$maxSrvLen = $servers ? max( array_map( 'strlen', $servers ) ) : 0;

		foreach ( $servers as $server ) {
			$this->output(
				str_pad( $server, $maxSrvLen ),
				$server # output channel
			);

			$mcc = new MemcachedClient( [
				'persistant' => true,
				'timeout' => $wgMemCachedTimeout
			] );
			$mcc->set_servers( [ $server ] );
			$set = 0;
			$incr = 0;
			$get = 0;
			$time_start = microtime( true );
			for ( $i = 1; $i <= $iterations; $i++ ) {
				if ( $mcc->set( "test$i", $i ) ) {
					$set++;
				}
			}
			for ( $i = 1; $i <= $iterations; $i++ ) {
				if ( !is_null( $mcc->incr( "test$i", $i ) ) ) {
					$incr++;
				}
			}
			for ( $i = 1; $i <= $iterations; $i++ ) {
				$value = $mcc->get( "test$i" );
				if ( $value == $i * 2 ) {
					$get++;
				}
			}
			$exectime = microtime( true ) - $time_start;

			$this->output( " set: $set   incr: $incr   get: $get time: $exectime", $server );
		}
	}
}

$maintClass = McTest::class;
require_once RUN_MAINTENANCE_IF_MAIN;
