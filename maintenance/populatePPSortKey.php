<?php
/**
 * Populate the pp_sortkey fields in the page_props table
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

/**
 * Usage:
 *  populatePPSortKey.php
 */
class PopulatePPSortKey extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populate the pp_sortkey field' );
		$this->setBatchSize( 100 );
	}

	protected function doDBUpdates() {
		$dbw = $this->getPrimaryDB();

		$lastProp = null;
		$lastPageValue = 0;

		$lastRowCount = 0;
		$editedRowCount = 0;

		$this->output( "Populating page_props.pp_sortkey...\n" );
		while ( true ) {
			$queryBuilder = $dbw->newSelectQueryBuilder()
				->select( [ 'pp_propname', 'pp_page', 'pp_sortkey', 'pp_value' ] )
				->from( 'page_props' )
				->where( [ 'pp_sortkey' => null ] )
				->orderBy( [ 'pp_page', 'pp_propname' ] )
				->limit( $this->getBatchSize() );
			if ( $lastPageValue !== 0 ) {
				$queryBuilder->andWhere( $dbw->buildComparison( '>', [
					'pp_page' => $lastPageValue,
					'pp_propname' => $lastProp,
				] ) );
			}

			$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

			if ( $res->numRows() === 0 ) {
				break;
			}

			$this->beginTransaction( $dbw, __METHOD__ );

			foreach ( $res as $row ) {
				if ( !is_numeric( $row->pp_value ) ) {
					continue;
				}
				$dbw->update(
					'page_props',
					[ 'pp_sortkey' => $row->pp_value ],
					[
						'pp_page' => $row->pp_page,
						'pp_propname' => $row->pp_propname
					],
					__METHOD__
				);
				$editedRowCount++;
			}

			if ( $editedRowCount !== $lastRowCount ) {
				$this->output( "Updated " . $editedRowCount . " rows\n" );
				$lastRowCount = $editedRowCount;
			}

			$this->commitTransaction( $dbw, __METHOD__ );

			// We need to get the last element's page ID
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable rows contains at least one item
			$lastPageValue = $row->pp_page;
			// And the propname...
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable rows contains at least one item
			$lastProp = $row->pp_propname;
		}

		$this->output( "Populating page_props.pp_sortkey complete.\n" );
		$this->output( "Updated a total of $editedRowCount rows\n" );
		return true;
	}

	protected function getUpdateKey() {
		return 'populate pp_sortkey';
	}
}

$maintClass = PopulatePPSortKey::class;
require_once RUN_MAINTENANCE_IF_MAIN;
