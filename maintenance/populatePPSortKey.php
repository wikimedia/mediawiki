<?php
/**
 * Populate the pp_sortkey fields in the Page_Props table
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

use Wikimedia\Rdbms\IDatabase;

/**
 * Usage:
 *  PopulatePPSortKey.php
 */
class PopulateSortKey extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populate the pp_sortkey field' );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$dbw = $this->getDB( DB_MASTER );

		$lastProp = NULL;
		$lastPageValue = 0;

		while ( true ) {
			$conditions = [ 'pp_sortkey IS NULL' ];
			if ( $lastPageValue !== 0 ) {
				$conditions[] = 'pp_page > ' . $dbw->addQuotes( $lastPageValue ) . 'OR' .
								'( pp_page = $lastPage AND pp_propname > $lastProp )';
			}

			$res = $dbw->select(
				'page_props',
				[ 'pp_propname', 'pp_page', 'pp_sortkey', 'pp_value' ],
				$conditions,
				__METHOD__,
				[ 'ORDER BY' => 'pp_page, pp_propname' ],
				[ 'LIMIT' => $this->mBatchSize ]
			);

			if ( $res->numRows() === 0 ) {
				break;
			}

			foreach ( $res as $row ) {
				if ( is_numeric( $row->pp_value ) ) {
					$dbw->update(
						'page_props',
						[ 'pp_sortkey' => $row->pp_value ],
						[
							'pp_page' = $row->pp_page,
							'pp_propname' = $row->pp_propname
						],
						__METHOD__
					);
				} else {
					continue;
				}
			}

			$this->output( "Updated " . $res->numRows() . " rows \n" );

			wfWaitForSlaves();

			// We need to get the last element's page ID
			$lastPageValue = $row->pp_value;
			// And the propname...
			$lastProp = $row->pp_propname;
		}

		$this->output( "Done!\n" );
	}
}

$maintClass = 'PopulateSortKey';
require_once RUN_MAINTENANCE_IF_MAIN;
