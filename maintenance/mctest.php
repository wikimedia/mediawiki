<?php
/**
 * This script makes several 'set', 'incr' and 'get' requests on every
 * memcached server and shows a report.
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class mcTest extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Makes several 'set', 'incr' and 'get' requests on every"
							  . " memcached server and shows a report";
		$this->addOption( 'i', 'Number of iterations', false, true );
		$this->addArg( 'server', 'Memcached server to test', false );
	}

	public function execute() {
		global $wgMemCachedServers;

		$iterations = $this->getOption( 'i', 100 );
		if ( $this->hasArg() )
			$wgMemCachedServers = array( $this->getArg() );

		foreach ( $wgMemCachedServers as $server ) {
			$this->output( $server . " ", $server );
			$mcc = new MemCachedClientforWiki( array( 'persistant' => true ) );
			$mcc->set_servers( array( $server ) );
			$set = 0;
			$incr = 0;
			$get = 0;
			$time_start = $this->microtime_float();
			for ( $i = 1; $i <= $iterations; $i++ ) {
				if ( !is_null( $mcc->set( "test$i", $i ) ) ) {
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
			$exectime = $this->microtime_float() - $time_start;

			$this->output( "set: $set   incr: $incr   get: $get time: $exectime", $server );
		}
	}

	/**
	 * Return microtime() as a float
	 * @return float
	 */
	private function microtime_float() {
		list( $usec, $sec ) = explode( " ", microtime() );
		return ( (float)$usec + (float)$sec );
	}
}

$maintClass = "mcTest";
require_once( DO_MAINTENANCE );
