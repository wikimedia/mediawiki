<?php
/**
 * This script delete image information from memcached.
 *
 * Usage example:
 * php deleteImageMemcached.php --until "2005-09-05 00:00:00" --sleep 0
 *
 * @file
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class DeleteImageCache extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Delete image information from memcached";
		$this->addParam( 'sleep', 'How many seconds to sleep between deletions', true, true );
		$this->addParam( 'until', 'Timestamp to delete all entries prior to', true, true );
	}

	public function execute() {
		global $wgMemc;

		$until = preg_replace( "/[^\d]/", '', $this->getOption('until') );
		$sleep = (int)$this->getOption('sleep') * 1000; // milliseconds

		ini_set( 'display_errors', false );

		$dbr = wfGetDB( DB_SLAVE );

		$res = $dbr->select( 'image',
			array( 'img_name' ),
			array( "img_timestamp < {$until}" ),
			__METHOD__
		);

		$i = 0;
		$total = $this->getImageCount();

		while ( $row = $dbr->fetchObject( $res ) ) {
			if ($i % $this->report == 0)
				$this->output( sprintf("%s: %13s done (%s)\n", wfWikiID(), "$i/$total", wfPercent( $i / $total * 100 ) ) );
			$md5 = md5( $row->img_name );
			$wgMemc->delete( wfMemcKey( 'Image', $md5 ) );

			if ($sleep != 0)
				usleep( $sleep );

			++$i;
		}
	}

	private function getImageCount() {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'image', 'COUNT(*)', array(), __METHOD__ );
	}
}

$maintClass = "DeleteImageCache";
require_once( DO_MAINTENANCE );
