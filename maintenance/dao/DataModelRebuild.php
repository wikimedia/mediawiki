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
	 * Execute the script
	 */
	public function execute() {
		$class = $this->getOption( 'model' );

		$this->output( "Rebuilding $class data.\n" );

		$backend = $class::getBackend();

		// delete all list entries from DB
		foreach ( $class::$lists as $list => $properties ) {
			// get existing entries from DB
			$sorts = array_keys( $properties['sort'] );
			$rows = $backend->getList( $list, null, 0, PHP_INT_MAX, $sorts[0], 'ASC');

			foreach ( $rows as $row ) {
				// make stub entry containing the details that ->updateListing() will need to delete the row
				$entry = new $class;
				$entry->{$class::getIdColumn()} = $row->{$class::getIdColumn()};
				$entry->{$class::getShardColumn()} = $row->{$class::getShardColumn()};

				$backend->updateListing( $entry, $list, array() );

				wfWaitForSlaves();
			}
		}

		// get all entries from DB
		$rows = $backend::get( null, null );

		foreach ( $rows as $row ) {
			$class::loadFromRow( $row )
				// this will overwrite existing cache for this entry by what we just fetched from DB
				->cache()
				// executing update will rebuild caches, lists, counts etc.
				->update();

			$this->completeCount++;

			wfWaitForSlaves();
		}

		$this->output( "Done. Rebuilt data for " . $this->completeCount . " $class entries.\n" );
	}
}

$maintClass = "DataModelRebuild";
require_once( RUN_MAINTENANCE_IF_MAIN );
