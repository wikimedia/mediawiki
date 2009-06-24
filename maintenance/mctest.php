<?php
/**
 * This script makes several 'set', 'incr' and 'get' requests on every
 * memcached server and shows a report.
 *
 * $Id$
 * @file
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class mcTest extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Makes several 'set', 'incr' and 'get' requests on every"
							  . " memcached server and shows a report";
		$this->addParam( 'i', 'Number of iterations', false, true );
		$this->addArgs( array( 'server' ) );
	}

	public function execute() {
		global $wgMemCachedServers;

		$iterations = $this->getOption( 'i', 100 );
		if( $this->hasArg() )
			$wgMemCachedServers = array( $this->getArg() );

		foreach ( $wgMemCachedServers as $server ) {
			$this->output( $server . " " );
			$mcc = new MemCachedClientforWiki( array('persistant' => true) );
			$mcc->set_servers( array( $server ) );
			$set = 0;
			$incr = 0;
			$get = 0;
			$time_start = $this->microtime_float();
			for ( $i=1; $i<=$iterations; $i++ ) {
				if ( !is_null( $mcc->set( "test$i", $i ) ) ) {
					$set++;
				}
			}
			for ( $i=1; $i<=$iterations; $i++ ) {
				if ( !is_null( $mcc->incr( "test$i", $i ) ) ) {
					$incr++;
				}
			}
			for ( $i=1; $i<=$iterations; $i++ ) {
				$value = $mcc->get( "test$i" );
				if ( $value == $i*2 ) {
					$get++;
				}
			}
			$exectime = $this->microtime_float() - $time_start;
	
			$this->output( "set: $set   incr: $incr   get: $get time: $exectime\n" );
		}
	}

	/**
	 * Return microtime() as a float
	 * @return float
	 */
	private function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
}

$maintClass = "mcTest";
require_once( DO_MAINTENANCE );
