<?php
/**
 * Squid purge benchmark script
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

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class BenchmarkPurge extends Maintenance {
	
	public function __construct() {
		parent::__construct();
		$this->addOption( "count", "How many URLs to feed to Squid for purging", false, true );
		$this->mDescription = "Benchmark the Squid purge functions.";
	}
	
	public function execute() {
		global $wgUseSquid, $wgSquidServers;
		if ( !$wgUseSquid ) {
			$this->error( "Squid purge benchmark doesn't do much without squid support on." . true );
		} else {
			$this->output( "There are " . count( $wgSquidServers ) . " defined squid servers:\n" );
			if ( $this->hasOption( 'count' ) ) {
				$lengths = array( intval( $this->getOption( 'count' ) ) );
			} else {
				$lengths = array( 1, 10, 100 );
			}
			foreach ( $lengths as $length ) {
				$urls = $this->randomUrlList( $length );
				$trial = $this->benchSquid( $urls );
				$this->output( $trial . "\n" );
			}
		}
	}
	
	/** 
	 * Run a bunch of URLs through SquidUpdate::purge()
	 * to benchmark Squid response times.
	 * @param $urls array A bunch of URLs to purge
	 * @param $trials int How many times to run the test?
	 */
	private function benchSquid( $urls, $trials = 1 ) {
		$start = wfTime();
		for ( $i = 0; $i < $trials; $i++ ) {
			SquidUpdate::purge( $urls );
		}
		$delta = wfTime() - $start;
		$pertrial = $delta / $trials;
		$pertitle = $pertrial / count( $urls );
		return sprintf( "%4d titles in %6.2fms (%6.2fms each)",
			count( $urls ), $pertrial * 1000.0, $pertitle * 1000.0 );
	}
	
	/** 
	 * Get an array of randomUrl()'s.
	 * @param $length int How many urls to add to the array
	 */
	private function randomUrlList( $length ) {
		$list = array();
		for ( $i = 0; $i < $length; $i++ ) {
			$list[] = $this->randomUrl();
		}
		return $list;
	}
	
	/** 
	 * Return a random URL of the wiki. Not necessarily an actual title in the
	 * database, but at least a URL that looks like one. 
	 */
	private function randomUrl() {
		global $wgServer, $wgArticlePath;
		return $wgServer . str_replace( '$1', $this->randomTitle(), $wgArticlePath );
	}
	
	/** 
	 * Create a random title string (not necessarily a Title object). 
	 * For use with randomUrl().
	 */
	private function randomTitle() {
		$str = '';
		$length = mt_rand( 1, 20 );
		for ( $i = 0; $i < $length; $i++ ) {
			$str .= chr( mt_rand( ord( 'a' ), ord( 'z' ) ) );
		}
		return ucfirst( $str );
	}
}

$maintClass = "BenchmarkPurge";
require_once( DO_MAINTENANCE );
