<?php
/**
 * Refresh file headers from metadata.
 *
 * Usage: php refreshFileHeaders.php
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
 * Maintenance script to refresh file headers from metadata
 *
 * @ingroup Maintenance
 */
class RefreshFileHeaders extends Maintenance {
	function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to update file HTTP headers' );
		$this->addOption( 'verbose', 'Output information about each file.', false, false, 'v' );
		$this->addOption( 'start', 'Name of file to start with', false, true );
		$this->addOption( 'end', 'Name of file to end with', false, true );
		$this->setBatchSize( 200 );
	}

	public function execute() {
		$repo = RepoGroup::singleton()->getLocalRepo();
		$start = str_replace( ' ', '_', $this->getOption( 'start', '' ) ); // page on img_name
		$end = str_replace( ' ', '_', $this->getOption( 'end', '' ) ); // page on img_name

		$count = 0;
		$dbr = $this->getDB( DB_REPLICA );

		do {
			$conds = [ "img_name > {$dbr->addQuotes( $start )}" ];

			if ( strlen( $end ) ) {
				$conds[] = "img_name <= {$dbr->addQuotes( $end )}";
			}

			$res = $dbr->select( 'image', '*', $conds,
				__METHOD__, [ 'LIMIT' => $this->mBatchSize, 'ORDER BY' => 'img_name ASC' ] );

			if ( $res->numRows() > 0 ) {
				$row1 = $res->current();
				$this->output( "Processing next {$res->numRows()} row(s) starting with {$row1->img_name}.\n" );
				$res->rewind();
			}

			$backendOperations = [];

			foreach ( $res as $row ) {
				$file = $repo->newFileFromRow( $row );
				$headers = $file->getContentHeaders();

				if ( count( $headers ) ) {
					$backendOperations[] = [
						'op' => 'describe', 'src' => $file->getPath(), 'headers' => $headers
					];
				}

				// Do all of the older file versions...
				foreach ( $file->getHistory() as $oldFile ) {
					$headers = $oldFile->getContentHeaders();
					if ( count( $headers ) ) {
						$backendOperations[] = [
							'op' => 'describe', 'src' => $oldFile->getPath(), 'headers' => $headers
						];
					}
				}

				if ( $this->hasOption( 'verbose' ) ) {
					$this->output( "Queued headers update for file '{$row->img_name}'.\n" );
				}

				$start = $row->img_name; // advance
			}

			$backendOperationsCount = count( $backendOperations );
			$count += $backendOperationsCount;

			$this->output( "Updating headers for {$backendOperationsCount} file(s).\n" );
			$this->updateFileHeaders( $repo, $backendOperations );
		} while ( $res->numRows() === $this->mBatchSize );

		$this->output( "Done. Updated headers for $count file(s).\n" );
	}

	protected function updateFileHeaders( $repo, $backendOperations ) {
		$status = $repo->getBackend()->doQuickOperations( $backendOperations );

		if ( !$status->isGood() ) {
			$this->error( "Encountered error: " . print_r( $status, true ) );
		}
	}
}

$maintClass = 'RefreshFileHeaders';
require_once RUN_MAINTENANCE_IF_MAIN;
