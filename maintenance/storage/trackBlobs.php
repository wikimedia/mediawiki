<?php
/**
 * Adds blobs from a given external storage cluster to the blob_tracking table.
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
 * @see wfWaitForSlaves()
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\DBConnectionError;

require_once __DIR__ . '/../Maintenance.php';

class TrackBlobs extends Maintenance {
	public $clusters, $textClause;
	public $doBlobOrphans;
	public $trackedBlobs = [];

	public $batchSize = 1000;
	public $reportingInterval = 10;

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Adds blobs from a given external storage cluster to the blob_tracking table'
		);
		$this->addArg( 'cluster', 'ES cluster to process', true );
		$this->addArg( 'cluster...', 'Additional ES clusters to process', false );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$this->clusters = $this->mArgs;

		if ( extension_loaded( 'gmp' ) ) {
			$this->doBlobOrphans = true;
			foreach ( $this->clusters as $cluster ) {
				$this->trackedBlobs[$cluster] = gmp_init( 0 );
			}
		} else {
			$this->output( "Warning: the gmp extension is needed to find orphan blobs\n" );
		}

		$this->checkIntegrity();
		$this->initTrackingTable();
		$this->trackRevisions();
		$this->trackOrphanText();
		if ( $this->doBlobOrphans ) {
			$this->findOrphanBlobs();
		}
	}

	private function checkIntegrity() {
		$this->output( "Doing integrity check...\n" );
		$dbr = wfGetDB( DB_REPLICA );

		// Scan for objects in the text table

		$exists = $dbr->selectField(
			'text',
			'1',
			[ 'old_flags' . $dbr->buildLike( $dbr->anyString(), 'object', $dbr->anyString() ) ],
			__METHOD__
		);

		if ( $exists ) {
			$this->fatalError( "Integrity check failed: found objects in your text table." .
				" Run migrateHistoryBlobs.php to fix this." );
		}

		$this->output( "Integrity check OK\n" );
	}

	private function initTrackingTable() {
		$dbw = wfGetDB( DB_MASTER );
		if ( $dbw->tableExists( 'blob_tracking' ) ) {
			$dbw->query( 'DROP TABLE ' . $dbw->tableName( 'blob_tracking' ) );
			$dbw->query( 'DROP TABLE ' . $dbw->tableName( 'blob_orphans' ) );
		}
		$dbw->sourceFile( __DIR__ . '/blob_tracking.sql' );
	}

	private function getTextClause() {
		if ( !$this->textClause ) {
			$dbr = wfGetDB( DB_REPLICA );
			$this->textClause = '';
			foreach ( $this->clusters as $cluster ) {
				if ( $this->textClause != '' ) {
					$this->textClause .= ' OR ';
				}
				$this->textClause .= 'old_text' . $dbr->buildLike( "DB://$cluster/", $dbr->anyString() );
			}
		}

		return $this->textClause;
	}

	private function interpretPointer( $text ) {
		if ( !preg_match( '!^DB://(\w+)/(\d+)(?:/([0-9a-fA-F]+)|)$!', $text, $m ) ) {
			return false;
		}

		return [
			'cluster' => $m[1],
			'id' => intval( $m[2] ),
			'hash' => isset( $m[3] ) ? $m[3] : null
		];
	}

	/**
	 *  Scan the revision table for rows stored in the specified clusters
	 */
	private function trackRevisions() {
		$dbw = wfGetDB( DB_MASTER );
		$dbr = wfGetDB( DB_REPLICA );

		$textClause = $this->getTextClause();
		$startId = 0;
		$endId = $dbr->selectField( 'revision', 'MAX(rev_id)', '', __METHOD__ );
		$batchesDone = 0;
		$rowsInserted = 0;

		$this->output( "Finding revisions...\n" );

		while ( true ) {
			$res = $dbr->select( [ 'revision', 'text' ],
				[ 'rev_id', 'rev_page', 'old_id', 'old_flags', 'old_text' ],
				[
					'rev_id > ' . $dbr->addQuotes( $startId ),
					'rev_text_id=old_id',
					$textClause,
					'old_flags ' . $dbr->buildLike( $dbr->anyString(), 'external', $dbr->anyString() ),
				],
				__METHOD__,
				[
					'ORDER BY' => 'rev_id',
					'LIMIT' => $this->batchSize
				]
			);
			if ( !$res->numRows() ) {
				break;
			}

			$insertBatch = [];
			foreach ( $res as $row ) {
				$startId = $row->rev_id;
				$info = $this->interpretPointer( $row->old_text );
				if ( !$info ) {
					$this->output( "Invalid DB:// URL in rev_id {$row->rev_id}\n" );
					continue;
				}
				if ( !in_array( $info['cluster'], $this->clusters ) ) {
					$this->output( "Invalid cluster returned in SQL query: {$info['cluster']}\n" );
					continue;
				}
				$insertBatch[] = [
					'bt_page' => $row->rev_page,
					'bt_rev_id' => $row->rev_id,
					'bt_text_id' => $row->old_id,
					'bt_cluster' => $info['cluster'],
					'bt_blob_id' => $info['id'],
					'bt_cgz_hash' => $info['hash']
				];
				if ( $this->doBlobOrphans ) {
					gmp_setbit( $this->trackedBlobs[$info['cluster']], $info['id'] );
				}
			}
			$dbw->insert( 'blob_tracking', $insertBatch, __METHOD__ );
			$rowsInserted += count( $insertBatch );

			++$batchesDone;
			if ( $batchesDone >= $this->reportingInterval ) {
				$batchesDone = 0;
				$this->output( "$startId / $endId\n" );
				wfWaitForSlaves();
			}
		}
		$this->output( "Found $rowsInserted revisions\n" );
	}

	/**
	 * Scan the text table for orphan text
	 * Orphan text here does not imply DB corruption -- deleted text tracked by the
	 * archive table counts as orphan for our purposes.
	 */
	function trackOrphanText() {
		# Wait until the blob_tracking table is available in the replica DB
		$dbw = wfGetDB( DB_MASTER );
		$dbr = wfGetDB( DB_REPLICA );
		$pos = $dbw->getMasterPos();
		if ( $pos !== false ) {
			$dbr->masterPosWait( $pos, 100000 );
		}

		$textClause = $this->getTextClause( $this->clusters );
		$startId = 0;
		$endId = $dbr->selectField( 'text', 'MAX(old_id)', '', __METHOD__ );
		$rowsInserted = 0;
		$batchesDone = 0;

		$this->output( "Finding orphan text...\n" );

		# Scan the text table for orphan text
		while ( true ) {
			$res = $dbr->select( [ 'text', 'blob_tracking' ],
				[ 'old_id', 'old_flags', 'old_text' ],
				[
					'old_id>' . $dbr->addQuotes( $startId ),
					$textClause,
					'old_flags ' . $dbr->buildLike( $dbr->anyString(), 'external', $dbr->anyString() ),
					'bt_text_id IS NULL'
				],
				__METHOD__,
				[
					'ORDER BY' => 'old_id',
					'LIMIT' => $this->batchSize
				],
				[ 'blob_tracking' => [ 'LEFT JOIN', 'bt_text_id=old_id' ] ]
			);
			$ids = [];
			foreach ( $res as $row ) {
				$ids[] = $row->old_id;
			}

			if ( !$res->numRows() ) {
				break;
			}

			$insertBatch = [];
			foreach ( $res as $row ) {
				$startId = $row->old_id;
				$info = $this->interpretPointer( $row->old_text );
				if ( !$info ) {
					$this->error( "Invalid DB:// URL in old_id {$row->old_id}\n" );
					continue;
				}
				if ( !in_array( $info['cluster'], $this->clusters ) ) {
					$this->error( "Invalid cluster returned in SQL query\n" );
					continue;
				}

				$insertBatch[] = [
					'bt_page' => 0,
					'bt_rev_id' => 0,
					'bt_text_id' => $row->old_id,
					'bt_cluster' => $info['cluster'],
					'bt_blob_id' => $info['id'],
					'bt_cgz_hash' => $info['hash']
				];
				if ( $this->doBlobOrphans ) {
					gmp_setbit( $this->trackedBlobs[$info['cluster']], $info['id'] );
				}
			}
			$dbw->insert( 'blob_tracking', $insertBatch, __METHOD__ );

			$rowsInserted += count( $insertBatch );
			++$batchesDone;
			if ( $batchesDone >= $this->reportingInterval ) {
				$batchesDone = 0;
				$this->output( "$startId / $endId\n" );
				wfWaitForSlaves();
			}
		}
		$this->output( "Found $rowsInserted orphan text rows\n" );
	}

	/**
	 * Scan the blobs table for rows not registered in blob_tracking (and thus not
	 * registered in the text table).
	 *
	 * Orphan blobs are indicative of DB corruption. They are inaccessible and
	 * should probably be deleted.
	 */
	function findOrphanBlobs() {
		if ( !extension_loaded( 'gmp' ) ) {
			$this->error( "Can't find orphan blobs, need bitfield support provided by GMP.\n" );

			return;
		}

		$dbw = wfGetDB( DB_MASTER );

		foreach ( $this->clusters as $cluster ) {
			$this->output( "Searching for orphan blobs in $cluster...\n" );
			$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
			$lb = $lbFactory->getExternalLB( $cluster );
			try {
				$extDB = $lb->getConnection( DB_REPLICA );
			} catch ( DBConnectionError $e ) {
				if ( strpos( $e->error, 'Unknown database' ) !== false ) {
					$this->error( "No database on $cluster\n" );
				} else {
					$this->error( "Error on $cluster: " . $e->getMessage() . "\n" );
				}
				continue;
			}
			$table = $extDB->getLBInfo( 'blobs table' );
			if ( is_null( $table ) ) {
				$table = 'blobs';
			}
			if ( !$extDB->tableExists( $table ) ) {
				$this->error( "No blobs table on cluster $cluster\n" );
				continue;
			}
			$startId = 0;
			$batchesDone = 0;
			$actualBlobs = gmp_init( 0 );
			$endId = $extDB->selectField( $table, 'MAX(blob_id)', '', __METHOD__ );

			// Build a bitmap of actual blob rows
			while ( true ) {
				$res = $extDB->select( $table,
					[ 'blob_id' ],
					[ 'blob_id > ' . $extDB->addQuotes( $startId ) ],
					__METHOD__,
					[ 'LIMIT' => $this->batchSize, 'ORDER BY' => 'blob_id' ]
				);

				if ( !$res->numRows() ) {
					break;
				}

				foreach ( $res as $row ) {
					gmp_setbit( $actualBlobs, $row->blob_id );
				}
				$startId = $row->blob_id;

				++$batchesDone;
				if ( $batchesDone >= $this->reportingInterval ) {
					$batchesDone = 0;
					$this->output( "$startId / $endId\n" );
				}
			}

			// Find actual blobs that weren't tracked by the previous passes
			// This is a set-theoretic difference A \ B, or in bitwise terms, A & ~B
			$orphans = gmp_and( $actualBlobs, gmp_com( $this->trackedBlobs[$cluster] ) );

			// Traverse the orphan list
			$insertBatch = [];
			$id = 0;
			$numOrphans = 0;
			while ( true ) {
				$id = gmp_scan1( $orphans, $id );
				if ( $id == -1 ) {
					break;
				}
				$insertBatch[] = [
					'bo_cluster' => $cluster,
					'bo_blob_id' => $id
				];
				if ( count( $insertBatch ) > $this->batchSize ) {
					$dbw->insert( 'blob_orphans', $insertBatch, __METHOD__ );
					$insertBatch = [];
				}

				++$id;
				++$numOrphans;
			}
			if ( $insertBatch ) {
				$dbw->insert( 'blob_orphans', $insertBatch, __METHOD__ );
			}
			$this->output( "Found $numOrphans orphan(s) in $cluster\n" );
		}
	}
}

$maintClass = 'TrackBlobs';
require_once RUN_MAINTENANCE_IF_MAIN;
