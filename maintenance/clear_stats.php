<?php
/**
 * This script remove all statistics tracking from memcached
 * 
 * @file
 * @ingroup Maintenance
 */

require_once( 'Maintenance.php' );

class clear_stats extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Remove all statistics tracking from memcached";
	}

	public function execute() {
		global $wgLocalDatabases, $wgMemc;
		foreach ( $wgLocalDatabases as $db ) {
			$wgMemc->delete("$db:stats:request_with_session");
			$wgMemc->delete("$db:stats:request_without_session");
			$wgMemc->delete("$db:stats:pcache_hit");
			$wgMemc->delete("$db:stats:pcache_miss_invalid");
			$wgMemc->delete("$db:stats:pcache_miss_expired");
			$wgMemc->delete("$db:stats:pcache_miss_absent");
			$wgMemc->delete("$db:stats:pcache_miss_stub");
			$wgMemc->delete("$db:stats:image_cache_hit");
			$wgMemc->delete("$db:stats:image_cache_miss");
			$wgMemc->delete("$db:stats:image_cache_update");
			$wgMemc->delete("$db:stats:diff_cache_hit");
			$wgMemc->delete("$db:stats:diff_cache_miss");
			$wgMemc->delete("$db:stats:diff_uncacheable");
		}
	}
}

$maintClass = "clear_stats";
require_once( DO_MAINTENANCE );
