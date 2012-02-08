<?php
/**
 * @ingroup Maintenance
 * @file
 */

require( dirname( __FILE__ ) . '/Maintenance.php' );

class PurgeParserCache extends Maintenance {
	var $lastProgress;

	function __construct() {
		parent::__construct();
		$this->addDescription( "Remove old objects from the parser cache. " . 
			"This only works when the parser cache is in an SQL database." );
		$this->addOption( 'expiredate', 'Delete objects expiring before this date.', false, true );
		$this->addOption( 'age', 
			'Delete objects created more than this many seconds ago, assuming $wgParserCacheExpireTime '.
				'has been consistent.',	
			false, true );
	}

	function execute() {
		$inputDate = $this->getOption( 'expiredate' );
		$inputAge = $this->getOption( 'age' );
		if ( $inputDate !== null ) {
			$date = wfTimestamp( TS_MW, strtotime( $inputDate ) );
		} elseif ( $inputAge !== null ) {
			global $wgParserCacheExpireTime;
			$date = wfTimestamp( TS_MW, time() + $wgParserCacheExpireTime - intval( $inputAge ) );
		} else {
			echo "Must specify either --expiredate or --age\n";
			exit( 1 );
		}

		$english = Language::factory( 'en' );
		echo "Deleting objects expiring before " . $english->timeanddate( $date ) . "\n";

		$pc = wfGetParserCacheStorage();
		$success = $pc->deleteObjectsExpiringBefore( $date, array( $this, 'showProgress' ) );
		if ( !$success ) {
			echo "\nCannot purge this kind of parser cache.\n";
			exit( 1 );
		}
		$this->showProgress( 100 );
		echo "\nDone\n";
	}

	function showProgress( $percent ) {
		$percentString = sprintf( "%.2f", $percent );
		if ( $percentString === $this->lastProgress ) {
			return;
		}
		$this->lastProgress = $percentString;

		$stars = floor( $percent / 2 );
		echo '[' . str_repeat( '*', $stars ), str_repeat( '.', 50 - $stars ) . '] ' .
			"$percentString%\r";

	}
}
$maintClass = 'PurgeParserCache';
require_once( RUN_MAINTENANCE_IF_MAIN );
