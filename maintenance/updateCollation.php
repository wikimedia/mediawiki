<?php
/**
 * @file
 * @ingroup Maintenance
 * @author Aryeh Gregor (Simetrical)
 */

#$optionsWithArgs = array( 'begin', 'max-slave-lag' );

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class UpdateCollation extends Maintenance {
	const BATCH_SIZE = 50;

	public function __construct() {
		parent::__construct();

		global $wgCategoryCollation;
		$this->mDescription = <<<TEXT
This script will find all rows in the categorylinks table whose collation is
out-of-date (cl_collation != '$wgCategoryCollation') and repopulate cl_sortkey
using the page title and cl_sortkey_prefix.  If everything's collation is
up-to-date, it will do nothing.
TEXT;

		$this->addOption( 'force', 'Run on all rows, even if the collation is ' . 
			'supposed to be up-to-date.' );
	}
	
	public function syncDBs() {
		$lb = wfGetLB();
		// bug 27975 - Don't try to wait for slaves if there are none
		// Prevents permission error when getting master position
		if ( $lb->getServerCount() > 1 ) {
			$dbw = $lb->getConnection( DB_MASTER );
			$pos = $dbw->getMasterPos();
			$lb->waitForAll( $pos );
		}
	}

	public function execute() {
		global $wgCategoryCollation, $wgMiserMode;

		$dbw = wfGetDB( DB_MASTER );
		$force = $this->getOption( 'force' );

		$options = array( 'LIMIT' => self::BATCH_SIZE );

		if ( $force ) {
			$options['ORDER BY'] = 'cl_from, cl_to';
			$collationConds = array();
		} else {
			$collationConds = array( 0 => 
				'cl_collation != ' . $dbw->addQuotes( $wgCategoryCollation ) );

			if ( !$wgMiserMode ) {
				$count = $dbw->selectField(
					'categorylinks',
					'COUNT(*)',
					$collationConds,
					__METHOD__
				);

				if ( $count == 0 ) {
					$this->output( "Collations up-to-date.\n" );
					return;
				}
				$this->output( "Fixing collation for $count rows.\n" );
			}
		}

		$count = 0;
		$row = false;
		$batchConds = array();
		do {
			$this->output( 'Processing next ' . self::BATCH_SIZE . ' rows... ');
			$res = $dbw->select(
				array( 'categorylinks', 'page' ),
				array( 'cl_from', 'cl_to', 'cl_sortkey_prefix', 'cl_collation',
					'cl_sortkey', 'page_namespace', 'page_title'
				),
				array_merge( $collationConds, $batchConds, array( 'cl_from = page_id' ) ),
				__METHOD__,
				$options
			);

			$dbw->begin();
			foreach ( $res as $row ) {
				$title = Title::newFromRow( $row );
				if ( !$row->cl_collation ) {
					# This is an old-style row, so the sortkey needs to be
					# converted.
					if ( $row->cl_sortkey == $title->getText()
					|| $row->cl_sortkey == $title->getPrefixedText() ) {
						$prefix = '';
					} else {
						# Custom sortkey, use it as a prefix
						$prefix = $row->cl_sortkey;
					}
				} else {
					$prefix = $row->cl_sortkey_prefix;
				}
				# cl_type will be wrong for lots of pages if cl_collation is 0,
				# so let's update it while we're here.
				if ( $title->getNamespace() == NS_CATEGORY ) {
					$type = 'subcat';
				} elseif ( $title->getNamespace() == NS_FILE ) {
					$type = 'file';
				} else {
					$type = 'page';
				}
				$dbw->update(
					'categorylinks',
					array(
						'cl_sortkey' => Collation::singleton()->getSortKey(
							$title->getCategorySortkey( $prefix ) ),
						'cl_sortkey_prefix' => $prefix,
						'cl_collation' => $wgCategoryCollation,
						'cl_type' => $type,
						'cl_timestamp = cl_timestamp',
					),
					array( 'cl_from' => $row->cl_from, 'cl_to' => $row->cl_to ),
					__METHOD__
				);
			}
			$dbw->commit();

			if ( $force && $row ) {
				$encFrom = $dbw->addQuotes( $row->cl_from );
				$encTo = $dbw->addQuotes( $row->cl_to );
				$batchConds = array( 
					"(cl_from = $encFrom AND cl_to > $encTo) " .
					" OR cl_from > $encFrom" );
			}

			$count += $res->numRows();
			$this->output( "$count done.\n" );
			
			$this->syncDBs();
		} while ( $res->numRows() == self::BATCH_SIZE );
	}
}

$maintClass = "UpdateCollation";
require_once( RUN_MAINTENANCE_IF_MAIN );
