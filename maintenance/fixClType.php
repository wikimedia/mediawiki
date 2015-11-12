<?php
/**
 * Fix incorrect cl_type values without having to run updateCollation.php.
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
 * Maintenance script to fix incorrect cl_type values without having to run
 * updateCollation.php.
 *
 * @since 1.27
 * @ingroup Maintenance
 */
class FixClType extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Fix incorrect cl_type values without having to run ' .
			'updateCollation.php';
		$this->addOption( 'start', 'First page ID to check', false, true );
		$this->addOption( 'end', 'Last page ID to check', false, true );
		$this->addOption( 'chunk-size', 'Maximum number of existent IDs to check per query, ' .
			'default 100000', false, true );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$dbr = $this->getDB( DB_SLAVE );
		$start = (int)$this->getOption( 'start' ) ?: null;
		$end = (int)$this->getOption( 'end' ) ?: null;
		$chunkSize = (int)$this->getOption( 'chunk-size', 100000 );
		do {
			// Find the start of the next chunk.
			$nextStart = $dbr->selectField(
				'page',
				'page_id',
				self::intervalCond( $dbr, 'page_id', $start, $end ),
				__METHOD__,
				array( 'ORDER BY' => 'page_id', 'OFFSET' => $chunkSize )
			);

			if ( $nextStart !== false ) {
				// To find the end of the current chunk, subtract one.
				$chunkEnd = $nextStart - 1;
			} else {
				// This is the last chunk. Check all page_ids up to $end.
				$chunkEnd = $end;
			}

			$fmtStart = $start !== null ? "[$start" : '(-INF';
			$fmtChunkEnd = $chunkEnd !== null ? "$chunkEnd]" : 'INF)';
			$this->output( "Checking interval $fmtStart, $fmtChunkEnd\n" );
			$this->checkInterval( $start, $chunkEnd );

			$start = $nextStart;

		} while ( $nextStart !== false );
	}

	/**
	 * Check a range of page IDs in the categorylinks table.
	 *
	 * @param string|int|null $start Page_id to start from
	 * @param string|int|null $end Page_id to stop at
	 */
	private function checkInterval( $start, $end ) {
		$dbr = $this->getDB( DB_SLAVE );
		do {
			$res = $dbr->select(
				array( 'page', 'categorylinks' ),
				array( 'page_id' ),
				array(
					self::intervalCond( $dbr, 'page_id', $start, $end ),
					"cl_type <> (CASE page_namespace " .
						"WHEN " . NS_CATEGORY . " THEN 'subcat' " .
						"WHEN " . NS_FILE . " THEN 'file' " .
						"ELSE 'page' " .
					"END)",
				),
				__METHOD__,
				array( 'GROUP BY' => 'page_id', 'LIMIT' => $this->mBatchSize ),
				array( 'categorylinks' => array( 'INNER JOIN', 'page_id = cl_from' ) )
			);

			foreach ( $res as $row ) {
				$pageID = $row->page_id;
				$this->output( "  Fixing page #$pageID\n" );
				$this->fixPage( $pageID );
				$start = $pageID + 1;
			}

			$numRows = $res->numRows();
			if ( $numRows ) {
				wfWaitForSlaves();
			}

		} while ( $numRows >= $this->mBatchSize && ( $end === null || $start <= $end ) );
	}

	/**
	 * Fix cl_type for all category links from a page.
	 *
	 * @param string|int $id page_id
	 */
	private function fixPage( $id ) {
		$dbw = $this->getDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		$row = $dbw->selectRow(
			'page',
			array( 'page_namespace' ),
			array( 'page_id' => $id ),
			__METHOD__,
			array( 'LOCK IN SHARE MODE' )
		);

		if ( $row ) {
			if ( $row->page_namespace == NS_CATEGORY ) {
				$type = 'subcat';
			} elseif ( $row->page_namespace == NS_FILE ) {
				$type = 'file';
			} else {
				$type = 'page';
			}

			$dbw->update(
				'categorylinks',
				array(
					'cl_type' => $type,
					'cl_timestamp = cl_timestamp',
				),
				array( 'cl_from' => $id ),
				__METHOD__
			);
		}

		$dbw->endAtomic( __METHOD__ );
	}

	/**
	 * Build a SQL expression for a closed interval (i.e. BETWEEN).
	 *
	 * By specifying a null $start or $end, it is also possible to create
	 * half-bounded or unbounded intervals using this function.
	 *
	 * @param IDatabase $db Database connection
	 * @param string $var Field name
	 * @param mixed $start First value to include or null
	 * @param mixed $end Last value to include or null
	 */
	private static function intervalCond( IDatabase $db, $var, $start, $end ) {
		if ( $start === null && $end === null ) {
			return "$var IS NOT NULL";
		} elseif ( $end === null ) {
			return "$var >= {$db->addQuotes( $start )}";
		} elseif ( $start === null ) {
			return "$var <= {$db->addQuotes( $end )}";
		} else {
			return "$var BETWEEN {$db->addQuotes( $start )} AND {$db->addQuotes( $end )}";
		}
	}
}

$maintClass = 'FixClType';
require_once RUN_MAINTENANCE_IF_MAIN;
