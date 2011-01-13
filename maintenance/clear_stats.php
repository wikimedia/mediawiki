<?php
/**
 * This script remove all statistics tracking from the cache
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

class clear_stats extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Remove all statistics tracking from the cache";
	}

	public function execute() {
		global $wgLocalDatabases, $wgMemc;
		foreach ( $wgLocalDatabases as $db ) {
			$wgMemc->delete( "$db:stats:request_with_session" );
			$wgMemc->delete( "$db:stats:request_without_session" );
			$wgMemc->delete( "$db:stats:pcache_hit" );
			$wgMemc->delete( "$db:stats:pcache_miss_invalid" );
			$wgMemc->delete( "$db:stats:pcache_miss_expired" );
			$wgMemc->delete( "$db:stats:pcache_miss_absent" );
			$wgMemc->delete( "$db:stats:pcache_miss_stub" );
			$wgMemc->delete( "$db:stats:image_cache_hit" );
			$wgMemc->delete( "$db:stats:image_cache_miss" );
			$wgMemc->delete( "$db:stats:image_cache_update" );
			$wgMemc->delete( "$db:stats:diff_cache_hit" );
			$wgMemc->delete( "$db:stats:diff_cache_miss" );
			$wgMemc->delete( "$db:stats:diff_uncacheable" );
		}
	}
}

$maintClass = "clear_stats";
require_once( RUN_MAINTENANCE_IF_MAIN );
