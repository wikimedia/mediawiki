<?php
/**
 * @file 
 * @ingroup Maintenance
 * @author Simetrical
 */

$optionsWithArgs = array( 'begin', 'max-slave-lag', 'throttle' );

require_once( dirname( __FILE__ ) . '/Maintenance.php' );


class PopulateCategory extends Maintenance {

	const REPORTING_INTERVAL = 1000;

	public function __construct() {
		parent::__construct();
		$this->mDescription = <<<TEXT
This script will populate the category table, added in MediaWiki 1.13.  It will
print out progress indicators every 1000 categories it adds to the table.  The
script is perfectly safe to run on large, live wikis, and running it multiple
times is harmless.  You may want to use the throttling options if it's causing
too much load; they will not affect correctness.

If the script is stopped and later resumed, you can use the --begin option with
the last printed progress indicator to pick up where you left off.  This is
safe, because any newly-added categories before this cutoff will have been
added after the software update and so will be populated anyway.

When the script has finished, it will make a note of this in the database, and
will not run again without the --force option.
TEXT;
# '
		$this->addOption( 'begin', 'Only do categories whose names are alphabetically after the provided name', false, true );
		$this->addOption( 'max-slave-lag', 'If slave lag exceeds this many seconds, wait until it drops before continuing.  Default: 10', false, true );
		$this->addOption( 'throttle', 'Wait this many milliseconds after each category.  Default: 0', false, true );
		$this->addOption( 'force', 'Run regardless of whether the database says it\'s been run already' );
	}
	
	public function execute() {
		$begin = $this->getOption( 'begin', '' );
		$maxSlaveLag = $this->getOption( 'max-slave-lag', 10 );
		$throttle = $this->getOption( 'throttle', 0 );
		$force = $this->getOption( 'force', false );
		$this->doPopulateCategory( $begin, $maxSlaveLag, $throttle, $force );
	}

	private function doPopulateCategory( $begin, $maxlag, $throttle, $force ) {
		$dbw = wfGetDB( DB_MASTER );
	
		if ( !$force ) {
			$row = $dbw->selectRow(
				'updatelog',
				'1',
				array( 'ul_key' => 'populate category' ),
				__METHOD__
			);
			if ( $row ) {
				$this->output( "Category table already populated.  Use php " .
				"maintenance/populateCategory.php\n--force from the command line " .
				"to override.\n" );
				return true;
			}
		}
	
		$maxlag = intval( $maxlag );
		$throttle = intval( $throttle );
		if ( $begin !== '' ) {
			$where = 'cl_to > ' . $dbw->addQuotes( $begin );
		} else {
			$where = null;
		}
		$i = 0;
	
		while ( true ) {
			# Find which category to update
			$row = $dbw->selectRow(
				'categorylinks',
				'cl_to',
				$where,
				__METHOD__,
				array(
					'ORDER BY' => 'cl_to'
				)
			);
			if ( !$row ) {
				# Done, hopefully.
				break;
			}
			$name = $row->cl_to;
			$where = 'cl_to > ' . $dbw->addQuotes( $name );
	
			# Use the row to update the category count
			$cat = Category::newFromName( $name );
			if ( !is_object( $cat ) ) {
				$this->output( "The category named $name is not valid?!\n" );
			} else {
				$cat->refreshCounts();
			}
	
			++$i;
			if ( !( $i % self::REPORTING_INTERVAL ) ) {
				$this->output( "$name\n" );
				wfWaitForSlaves( $maxlag );
			}
			usleep( $throttle * 1000 );
		}
	
		if ( $dbw->insert(
				'updatelog',
				array( 'ul_key' => 'populate category' ),
				__METHOD__,
				'IGNORE'
			)
		) {
			$this->output( "Category population complete.\n" );
			return true;
		} else {
			$this->output( "Could not insert category population row.\n" );
			return false;
		}
	}
}

$maintClass = "PopulateCategory";
require_once( DO_MAINTENANCE );
