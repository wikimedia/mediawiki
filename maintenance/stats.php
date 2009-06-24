<?php
/**
 * Show statistics from memcached
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class MemcachedStats extends Maintenance {

	public function __construct() {
		$this->mDescription = "Show statistics from memcached";
	}

	public function execute() {
		global $wgMemc;
		
		// Can't do stats if 
		if( get_class( $wgMemc ) == 'FakeMemCachedClient' ) {
			$this->error( "You are running FakeMemCachedClient, I can not provide any statistics.\n", true );
		}
		$session = intval($wgMemc->get(wfMemcKey('stats','request_with_session')));
		$noSession = intval($wgMemc->get(wfMemcKey('stats','request_without_session')));
		$total = $session + $noSession;
		if ( $total == 0 ) {
			$this->error( "You either have no stats or memcached isn't running. Aborting.\n", true );
		}
		$this->output( "Requests\n" );
		$this->output( sprintf( "with session:      %-10d %6.2f%%\n", $session, $session/$total*100 );
		$this->output( sprintf( "without session:   %-10d %6.2f%%\n", $noSession, $noSession/$total*100 );
		$this->output( sprintf( "total:             %-10d %6.2f%%\n", $total, 100 );
	
	
		$this->output( "\nParser cache\n" );
		$hits = intval($wgMemc->get(wfMemcKey('stats','pcache_hit')));
		$invalid = intval($wgMemc->get(wfMemcKey('stats','pcache_miss_invalid')));
		$expired = intval($wgMemc->get(wfMemcKey('stats','pcache_miss_expired')));
		$absent = intval($wgMemc->get(wfMemcKey('stats','pcache_miss_absent')));
		$stub = intval($wgMemc->get(wfMemcKey('stats','pcache_miss_stub')));
		$total = $hits + $invalid + $expired + $absent + $stub;
		$this->output( sprintf( "hits:              %-10d %6.2f%%\n", $hits, $hits/$total*100 ) );
		$this->output( sprintf( "invalid:           %-10d %6.2f%%\n", $invalid, $invalid/$total*100 ) );
		$this->output( sprintf( "expired:           %-10d %6.2f%%\n", $expired, $expired/$total*100 ) );
		$this->output( sprintf( "absent:            %-10d %6.2f%%\n", $absent, $absent/$total*100 ) );
		$this->output( sprintf( "stub threshold:    %-10d %6.2f%%\n", $stub, $stub/$total*100 ) );
		$this->output( sprintf( "total:             %-10d %6.2f%%\n", $total, 100 ) );
	
		$hits = intval($wgMemc->get(wfMemcKey('stats','image_cache_hit')));
		$misses = intval($wgMemc->get(wfMemcKey('stats','image_cache_miss')));
		$updates = intval($wgMemc->get(wfMemcKey('stats','image_cache_update')));
		$total = $hits + $misses;
		$this->output("\nImage cache\n");
		$this->output( sprintf( "hits:              %-10d %6.2f%%\n", $hits, $hits/$total*100 ) );
		$this->output( sprintf( "misses:            %-10d %6.2f%%\n", $misses, $misses/$total*100 ) );
		$this->output( sprintf( "updates:           %-10d\n", $updates ) );
	
		$hits = intval($wgMemc->get(wfMemcKey('stats','diff_cache_hit')));
		$misses = intval($wgMemc->get(wfMemcKey('stats','diff_cache_miss')));
		$uncacheable = intval($wgMemc->get(wfMemcKey('stats','diff_uncacheable')));
		$total = $hits + $misses + $uncacheable;
		$this->output("\nDiff cache\n");
		$this->output( sprintf( "hits:              %-10d %6.2f%%\n", $hits, $hits/$total*100 );
		$this->output( sprintf( "misses:            %-10d %6.2f%%\n", $misses, $misses/$total*100 );
		$this->output( sprintf( "uncacheable:       %-10d %6.2f%%\n", $uncacheable, $uncacheable/$total*100 );
	}
}

$maintClass = "MemcachedStats";
require_once( DO_MAINTENANCE );




