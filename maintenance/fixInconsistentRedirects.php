<?php
/**
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
 */

use MediaWiki\Maintenance\LoggedUpdateMaintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Fix redirect pages with missing or incomplete row in the redirect table.
 *
 * @ingroup Maintenance
 * @since 1.41
 */
class FixInconsistentRedirects extends LoggedUpdateMaintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Fix redirect pages with missing or incomplete row in the redirect table' );
		$this->setBatchSize( 100 );
	}

	/** @inheritDoc */
	protected function getUpdateKey() {
		return __CLASS__;
	}

	/** @inheritDoc */
	protected function doDBUpdates() {
		$dbr = $this->getReplicaDB();

		$builder = $dbr->newSelectQueryBuilder()
			->caller( __METHOD__ )
			->from( 'page' )
			->where( [ 'page_is_redirect' => 1 ] );

		$this->output( "Fixing inconsistent redirects ...\n" );

		$estimateCount = $builder->estimateRowCount();
		$this->output( "Estimated redirect page count: $estimateCount\n" );

		$builder
			->limit( $this->getBatchSize() )
			->leftJoin( 'redirect', null, 'page_id=rd_from' )
			->select( [ 'rd_from', 'rd_interwiki', 'rd_fragment' ] );

		// Using the page_redirect_namespace_len index to skip non-redirects
		$index = [ 'page_is_redirect', 'page_namespace', 'page_len', 'page_id' ];
		$builder->select( $index )->orderBy( $index );
		$prevRow = [];

		$total = 0;
		$updated = 0;
		do {
			$res = ( clone $builder )
				->where( $prevRow ? [ $dbr->buildComparison( '>', $prevRow ) ] : [] )
				->caller( __METHOD__ )->fetchResultSet();

			foreach ( $res as $row ) {
				// Only attempt write queries if the row or rd_interwiki/rd_fragment fields are missing
				// (we don't include this condition in the query to avoid slow queries and bad estimates)
				if ( $row->rd_from === null || $row->rd_interwiki === null || $row->rd_fragment === null ) {
					RefreshLinks::fixRedirect( $this, $row->page_id );
					$updated++;
				}
			}
			if ( isset( $row ) ) {
				// Update the conditions to select the next batch
				foreach ( $index as $field ) {
					$prevRow[ $field ] = $row->$field;
				}
			}

			$this->waitForReplication();
			$total += $res->numRows();
			$this->output( "$updated/$total\n" );

		} while ( $res->numRows() == $this->getBatchSize() );

		$this->output( "Done, updated $updated of $total rows.\n" );
		return true;
	}
}

// @codeCoverageIgnoreStart
$maintClass = FixInconsistentRedirects::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
