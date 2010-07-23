<?php
/**
 * @file 
 * @ingroup Maintenance
 * @author Aryeh Gregor (Simetrical)
 */

#$optionsWithArgs = array( 'begin', 'max-slave-lag' );

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class UpdateCollation extends Maintenance {
	const BATCH_SIZE = 1000;

	public function __construct() {
		parent::__construct();

		global $wgCollationVersion;
		$this->mDescription = <<<TEXT
This script will find all rows in the categorylinks table whose collation is 
out-of-date (cl_collation < $wgCollationVersion) and repopulate cl_sortkey 
using cl_raw_sortkey.  If everything's collation is up-to-date, it will do 
nothing.
TEXT;

		#$this->addOption( 'force', 'Run on all rows, even if the collation is supposed to be up-to-date.' );
	}
	
	public function execute() {
		global $wgCollationVersion, $wgContLang;

		$dbw = wfGetDB( DB_MASTER );
		$count = $dbw->estimateRowCount(
			'categorylinks',
			array( 'cl_from', 'cl_to', 'cl_raw_sortkey' ),
			'cl_collation < ' . $dbw->addQuotes( $wgCollationVersion ),
			__METHOD__
		);

		$this->output( "Fixing around $count rows (estimate might be wrong).\n" );

		$count = 0;
		do {
			$res = $dbw->select(
				'categorylinks',
				array( 'cl_from', 'cl_to', 'cl_raw_sortkey' ),
				'cl_collation < ' . $dbw->addQuotes( $wgCollationVersion ),
				__METHOD__,
				array( 'LIMIT' => self::BATCH_SIZE )
			);

			$dbw->begin();
			foreach ( $res as $row ) {
				# TODO: Handle the case where cl_raw_sortkey is null.
				$dbw->update(
					'categorylinks',
					array(
						'cl_sortkey' => $wgContLang->convertToSortkey( $row->cl_raw_sortkey ),
						'cl_collation' => $wgCollationVersion
					),
					array( 'cl_from' => $row->cl_from, 'cl_to' => $row->cl_to ),
					__METHOD__
				);
			}
			$dbw->commit();

			$count += self::BATCH_SIZE;
			$this->output( "$count done.\n" );
		} while ( $res->numRows() >= self::BATCH_SIZE );
	}
}

$maintClass = "UpdateCollation";
require_once( DO_MAINTENANCE );
