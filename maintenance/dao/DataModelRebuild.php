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
	 * @return string
	 */
	public function getClass() {
		return $this->getOption( 'model' );
	}

	/**
	 * Execute the script
	 */
	public function execute() {
		$class = $this->getClass();
		$shards = array( null );

		$this->output( "Rebuilding $class data.\n" );

		// gather list of all conditions
		$conditions = array();
		foreach ( $class::$lists as $list => $properties ) {
			$conds = isset( $properties['conditions'] ) ? (array) $properties['conditions'] : array();
			$conditions = array_merge( $conditions, $conds );
		}
		if ( $conditions ) {
			$conditions = array_combine( $conditions, array_fill( 0, count( $conditions ), false ) );
		}

		// get all entries from DB
		// @todo: this does not scale ;)
		$rows = $class::getBackend()->get( null, null );

		foreach ( $rows as $i => $row ) {
			if ( !in_array( $row->{$class::getShardColumn()}, $shards ) ) {
				$shards[] = $row->{$class::getShardColumn()};
			}

			$entry = $class::loadFromRow( $row );

			// purge existing conditions
			$class::getBackend()->updateConditions( $entry, $conditions );

			$entry
				// this will overwrite existing cache for this entry by what we just fetched from DB
				->cache()
				// completely rebuild conditions (& sorts)
				->updateLists()
				// executing update will rebuild caches, lists, counts etc.
				->update();

			$this->completeCount++;

			wfWaitForSlaves();

			if ( $i % 50 == 0 ) {
				$this->output( "--rebuilt to entry #".$entry->{$class::getIdColumn()}."\n" );
			}
		}

		// clear counts (these are otherwise not automatically purged)
		global $wgMemc;
		foreach ( $class::$lists as $list => $properties ) {
			foreach ( $shards as $shard ) {
				$key = wfMemcKey( $class, 'getCount', $list, $shard );
				$wgMemc->delete( $key );
			}
		}

		$this->output( "Done. Rebuilt data for $this->completeCount $class entries.\n" );
	}
}

$maintClass = "DataModelRebuild";
require_once( RUN_MAINTENANCE_IF_MAIN );
