<?php
/**
 * Check syntax of all PHP files in MediaWiki
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
 * @author Matthias Mullie <mmullie@wikimedia.org>
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

/**
 * This will rebuild all DataModel data from the raw source data stored
 * in DB. This will:
 * - remove cached object data ('get' key)
 * - remove lists from DB & re-evaluate all objects to lists
 * - remove all cached lists data ('getList' key)
 * - ...
 *
 * This script should be run when adding a new list to the code (so that
 * the list picks up existing entries) or if some error has occurred that
 * results in some of the cached/generated data being invalid.
 *
 * @ingroup Maintenance
 */
class DataModelRebuild extends Maintenance {
	/**
	 * Batch size
	 *
	 * @var int
	 */
	private $limit = 50;

	/**
	 * The number of entries completed
	 *
	 * @var int
	 */
	private $completeCount = 0;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->addOption( 'model', 'Classname of the model to rebuild data for', true, true );
		$this->mDescription = 'Rebuild all DataModel lists by re-evaluating all entries to list conditions.';
	}

	/**
	 * Execute the script
	 */
	public function execute() {
		$class = $this->getOption( 'model' );

		global $wgMemc;

		$this->output( "Rebuilding $class data.\n" );

		// build array of list names in DB
		$keys = array();
		foreach ( $class::$lists as $list => $options ) {
			$keys[] = $class::getListDBName( $list );
		}

		// delete all lists from DB
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( 'datamodel_lists' );
		$partitions = $store->getMultiplePartitions( 'datamodel_lists', 'list', $keys );
		foreach ( $partitions as $partition ) {
			$keys = $partition['values'];
			$partition['partition']->delete(
				array( 'list' => $keys ),
				__METHOD__
			);
		}

		/*
		 * all lists ('getList' key) will be deleted from cache by datamodel itself;
		 * because we cleared them from our database, when the data is re-saved, it'll
		 * notice that saving list data to DB affects rows & will purge all list caches
		 */

		// delete auto-increment id from cache
		$key = wfMemcKey( $class, 'getId' );
		$wgMemc->delete( $key );

		// loop all partitions, we want to rebuild _all_ entries
		$storeGroup = RDBStoreGroup::singleton();
		$store = $storeGroup->getForTable( $class::getTable() );
		$partitions = $store->getAllPartitions( $class::getTable(), $class::getShardColumn() );
		foreach ( $partitions as $partition ) {
			$continue = 0;

			// fetch in batches
			while ( $continue !== null ) {
				$conds = array( $class::getIdColumn() . " > $continue" );
				$options = array( 'LIMIT' => $this->limit );
				$rows = $class::getFromDB( $partition, $conds, $options );

				$continue = null;

				foreach ( $rows as $row ) {
					$id = $row->{$class::getIdColumn()};
					$shard = $row->{$class::getShardColumn()};

					// delete all cached data ('get' key)
					$key = wfMemcKey( $class, 'get', $id, $shard );
					$wgMemc->delete( $key );

					// since we cleared all caches, this will fetch the item from db
					$entry = $class::get( $id, $shard );

					/*
					 * delete counts ('getCount' key)
					 * This will attempts to delete far more stuff than is actually in cache
					 * (e.g. multiple entries will have the same shard values, which will
					 * result in multiple times the same key combination being deleted). Though
					 * it's a little 'over-the-top' to do multiple, unnecessary deletes, it's
					 * the easiest way to get rid of all data (the other solution would require
					 * a separate query to all partitions to fetch all $shard values)
					 */
					foreach ( array( $shard, null ) as $shard ) {
						foreach ( array_keys( $class::$lists ) as $list ) {
							$key = wfMemcKey( $class, 'getCount', $list, $shard );
							$wgMemc->delete( $key );
						}
					}

					// executing update will rebuild lists etc.
					$entry->update();

					$continue = $id;
					$this->completeCount++;
				}

				wfWaitForSlaves();
			}
		}

		$this->output( "Done. Rebuilt data for " . $this->completeCount . " $class entries.\n" );
	}
}

$maintClass = "DataModelRebuild";
require_once( RUN_MAINTENANCE_IF_MAIN );
