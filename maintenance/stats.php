<?php
/**
 * Show statistics from the cache
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

class CacheStats extends Maintenance {

	public function __construct() {
		$this->mDescription = "Show statistics from the cache";
		parent::__construct();
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		global $wgMemc;

		// Can't do stats if
		if ( get_class( $wgMemc ) == 'FakeMemCachedClient' ) {
			$this->error( "You are running FakeMemCachedClient, I can not provide any statistics.", true );
		}
		$session = intval( $wgMemc->get( wfMemcKey( 'stats', 'request_with_session' ) ) );
		$noSession = intval( $wgMemc->get( wfMemcKey( 'stats', 'request_without_session' ) ) );
		$total = $session + $noSession;
		if ( $total == 0 ) {
			$this->error( "You either have no stats or the cache isn't running. Aborting.", true );
		}
		$this->output( "Requests\n" );
		$this->output( sprintf( "with session:      %-10d %6.2f%%\n", $session, $session / $total * 100 ) );
		$this->output( sprintf( "without session:   %-10d %6.2f%%\n", $noSession, $noSession / $total * 100 ) );
		$this->output( sprintf( "total:             %-10d %6.2f%%\n", $total, 100 ) );


		$this->output( "\nParser cache\n" );
		$hits = intval( $wgMemc->get( wfMemcKey( 'stats', 'pcache_hit' ) ) );
		$invalid = intval( $wgMemc->get( wfMemcKey( 'stats', 'pcache_miss_invalid' ) ) );
		$expired = intval( $wgMemc->get( wfMemcKey( 'stats', 'pcache_miss_expired' ) ) );
		$absent = intval( $wgMemc->get( wfMemcKey( 'stats', 'pcache_miss_absent' ) ) );
		$stub = intval( $wgMemc->get( wfMemcKey( 'stats', 'pcache_miss_stub' ) ) );
		$total = $hits + $invalid + $expired + $absent + $stub;
		$this->output( sprintf( "hits:              %-10d %6.2f%%\n", $hits, $hits / $total * 100 ) );
		$this->output( sprintf( "invalid:           %-10d %6.2f%%\n", $invalid, $invalid / $total * 100 ) );
		$this->output( sprintf( "expired:           %-10d %6.2f%%\n", $expired, $expired / $total * 100 ) );
		$this->output( sprintf( "absent:            %-10d %6.2f%%\n", $absent, $absent / $total * 100 ) );
		$this->output( sprintf( "stub threshold:    %-10d %6.2f%%\n", $stub, $stub / $total * 100 ) );
		$this->output( sprintf( "total:             %-10d %6.2f%%\n", $total, 100 ) );

		$hits = intval( $wgMemc->get( wfMemcKey( 'stats', 'image_cache_hit' ) ) );
		$misses = intval( $wgMemc->get( wfMemcKey( 'stats', 'image_cache_miss' ) ) );
		$updates = intval( $wgMemc->get( wfMemcKey( 'stats', 'image_cache_update' ) ) );
		$total = $hits + $misses;
		$this->output( "\nImage cache\n" );
		$this->output( sprintf( "hits:              %-10d %6.2f%%\n", $hits, $hits / $total * 100 ) );
		$this->output( sprintf( "misses:            %-10d %6.2f%%\n", $misses, $misses / $total * 100 ) );
		$this->output( sprintf( "updates:           %-10d\n", $updates ) );

		$hits = intval( $wgMemc->get( wfMemcKey( 'stats', 'diff_cache_hit' ) ) );
		$misses = intval( $wgMemc->get( wfMemcKey( 'stats', 'diff_cache_miss' ) ) );
		$uncacheable = intval( $wgMemc->get( wfMemcKey( 'stats', 'diff_uncacheable' ) ) );
		$total = $hits + $misses + $uncacheable;
		$this->output( "\nDiff cache\n" );
		$this->output( sprintf( "hits:              %-10d %6.2f%%\n", $hits, $hits / $total * 100 ) );
		$this->output( sprintf( "misses:            %-10d %6.2f%%\n", $misses, $misses / $total * 100 ) );
		$this->output( sprintf( "uncacheable:       %-10d %6.2f%%\n", $uncacheable, $uncacheable / $total * 100 ) );
	}
}

$maintClass = "CacheStats";
require_once( RUN_MAINTENANCE_IF_MAIN );
