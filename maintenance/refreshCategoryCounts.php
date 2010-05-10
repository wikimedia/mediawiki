<?php
/**
 * This script will refresh the cat_pages, cat_subcats and cat_files fields of
 * the category table, which may be incorrect if the wiki ran the corrupted
 * version of Article::doDeleteArticle (r40912 --> r47326); see explanation at
 * [https://bugzilla.wikimedia.org/show_bug.cgi?id=17155].  It will print out 
 * progress indicators every 1000 categories it updates. You may want to use the
 * throttling options if it's causing too much load; they will not affect 
 * correctness.
 *
 * If the script is stopped and later resumed, you can use the --start option 
 * with the last printed progress indicator to pick up where you left off. 
 * This is safe, because any newly-added categories will  be added at the end of
 * the table.
 *
 * @file 
 * @ingroup Maintenance
 * @author Happy-melon, Max Semenik
 * Based on /maintenance/populateCategory.php by Simetrical.
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class RefreshCategoryCounts extends Maintenance {
	const REPORTING_INTERVAL = 1000;

	public function __construct() {
		$this->mDescription = 'Refreshes category counts';
		$this->addOption( 'start', 'Start from this category ID', false, true );
		$this->addOption( 'maxlag', 'Maximum database slave lag in seconds (5 by default)', false, true );
		$this->addOption( 'throttle', 'Optional delay after every processed category in milliseconds',
			false, true );
	}
	
	public function execute() {
		$start = intval( $this->getOption( 'start', 0 ) );
		$maxlag = intval( $this->getOption( 'maxlag', 5 ) );
		$throttle = intval( $this->getOption( 'throttle', 0 ) );

		$this->doRefresh( $start, $maxlag, $throttle );
	}

	protected function doRefresh( $start, $maxlag, $throttle ) {
		$dbw = wfGetDB( DB_MASTER );

		$maxlag = intval( $maxlag );
		$throttle = intval( $throttle );
		$id = $start;

		$i = 0;
		while ( true ) {
			# Find which category to update
			$row = $dbw->selectRow(
				'category',
				array( 'cat_id', 'cat_title' ),
				'cat_id > ' . $dbw->addQuotes( $id ),
				__METHOD__,
				array( 'ORDER BY' => 'cat_id' )
			);
			if ( !$row ) {
				# Done, hopefully.
				break;
			}
			$id    = $row->cat_id;
			$name  = $row->cat_title;

			# Use the row to update the category count
			$cat = Category::newFromName( $name );
			if ( !is_object( $cat ) ) {
				$this->output( "Invalid category name '$name'\n" );
			} else {
				$cat->refreshCounts();
			}

			$i++;
			if ( !( $i % self::REPORTING_INTERVAL ) ) {
				$this->output( "$id\n" );
				wfWaitForSlaves( $maxlag );
			}
			usleep( $throttle * 1000 );
		}

		/*if ( $dbw->insert(
				'updatelog',
				array( 'ul_key' => 'refresh catgory counts' ),
				__METHOD__,
				'IGNORE'
			)
		) {
			$this->output( "Category count refresh complete.\n" );
			return true;
		} else {
			$this->output( "Could not insert category population row.\n" );
			return false;
		}*/
		$this->output( "Category count refresh complete.\n" );
	}
}

$maintClass = "RefreshCategoryCounts";
require_once( DO_MAINTENANCE );

