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
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Revision\SlotRecord;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

class TrackBlobs extends Maintenance {
	/** @var string[] */
	public $clusters;
	/** @var IExpression|null */
	public $textClause;
	/** @var bool */
	public $doBlobOrphans;
	/** @var array */
	public $trackedBlobs = [];

	/** @var int */
	public $batchSize = 1000;
	/** @var int */
	public $reportingInterval = 10;

	public function __construct() {
		parent::__construct();

		$this->addArg( 'cluster', 'cluster(s) to scan', true, true );

		$this->addDescription(
			'Adds blobs from a given ES cluster to the blob_tracking table. ' .
			'Automatically deletes the tracking table and starts from the start again when restarted.'
		);
	}

	public function execute() {
		$this->clusters = $this->parameters->getArgs();
		if ( extension_loaded( 'gmp' ) ) {
			$this->doBlobOrphans = true;
			foreach ( $this->clusters as $cluster ) {
				$this->trackedBlobs[$cluster] = gmp_init( 0 );
			}
		} else {
			echo "Warning: the gmp extension is needed to find orphan blobs\n";
		}

		$this->checkIntegrity();
		$this->initTrackingTable();
		$this->trackRevisions();
		$this->trackOrphanText();
		if ( $this->doBlobOrphans ) {
			$this->findOrphanBlobs();
		}
		$this->output( "All done.\n" );
	}

	private function checkIntegrity() {
		echo "Doing integrity check...\n";
		$dbr = $this->getReplicaDB();

		// Scan for HistoryBlobStub objects in the text table (T22757)

		$exists = (bool)$dbr->newSelectQueryBuilder()
			->select( '1' )
			->from( 'text' )
			->where(
				'old_flags LIKE \'%object%\' AND old_flags NOT LIKE \'%external%\' ' .
				'AND LOWER(CONVERT(LEFT(old_text,22) USING latin1)) = \'o:15:"historyblobstub"\'' )
			->caller( __METHOD__ )->fetchField();

		if ( $exists ) {
			echo "Integrity check failed: found HistoryBlobStub objects in your text table.\n" .
				"This script could destroy these objects if it continued. Run resolveStubs.php\n" .
				"to fix this.\n";
			exit( 1 );
		}

		echo "Integrity check OK\n";
	}

	private function initTrackingTable() {
		$dbw = $this->getDB( DB_PRIMARY );
		if ( $dbw->tableExists( 'blob_tracking', __METHOD__ ) ) {
			$dbw->query( 'DROP TABLE ' . $dbw->tableName( 'blob_tracking' ), __METHOD__ );
			$dbw->query( 'DROP TABLE ' . $dbw->tableName( 'blob_orphans' ), __METHOD__ );
		}
		$dbw->sourceFile( __DIR__ . '/blob_tracking.sql' );
	}

	private function getTextClause(): IExpression {
		if ( !$this->textClause ) {
			$dbr = $this->getReplicaDB();
			$conds = [];
			foreach ( $this->clusters as $cluster ) {
				$conds[] = $dbr->expr(
					'old_text',
					IExpression::LIKE,
					new LikeValue( "DB://$cluster/", $dbr->anyString() )
				);
			}
			$this->textClause = $dbr->orExpr( $conds );
		}

		return $this->textClause;
	}

	/** @return array|false */
	private function interpretPointer( string $text ) {
		if ( !preg_match( '!^DB://(\w+)/(\d+)(?:/([0-9a-fA-F]+)|)$!', $text, $m ) ) {
			return false;
		}

		return [
			'cluster' => $m[1],
			'id' => intval( $m[2] ),
			'hash' => $m[3] ?? null
		];
	}

	/**
	 *  Scan the revision table for rows stored in the specified clusters
	 */
	private function trackRevisions() {
		$dbw = $this->getPrimaryDB();
		$dbr = $this->getReplicaDB();

		$textClause = $this->getTextClause();
		$startId = 0;
		$endId = (int)$dbr->newSelectQueryBuilder()
			->select( 'MAX(rev_id)' )
			->from( 'revision' )
			->caller( __METHOD__ )->fetchField();
		$batchesDone = 0;
		$rowsInserted = 0;

		echo "Finding revisions...\n";

		$conds = [
			$textClause,
			$dbr->expr(
				'old_flags',
				IExpression::LIKE,
				new LikeValue( $dbr->anyString(), 'external', $dbr->anyString() )
			)
		];
		$slotRoleStore = $this->getServiceContainer()->getSlotRoleStore();

		$conds = array_merge( [
			'slot_role_id' => $slotRoleStore->getId( SlotRecord::MAIN ),
			'SUBSTRING(content_address, 1, 3)=' . $dbr->addQuotes( 'tt:' ),
		], $conds );

		while ( true ) {
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'rev_id', 'rev_page', 'old_id', 'old_flags', 'old_text' ] )
				->from( 'revision' )
				->join( 'slots', null, 'rev_id=slot_revision_id' )
				->join( 'content', null, 'content_id=slot_content_id' )
				->join( 'text', null, 'SUBSTRING(content_address, 4)=old_id' )
				->where( $dbr->expr( 'rev_id', '>', $startId ) )
				->andWhere( $conds )
				->orderBy( 'rev_id' )
				->limit( $this->batchSize )
				->caller( __METHOD__ )->fetchResultSet();
			if ( !$res->numRows() ) {
				break;
			}

			$insertBatch = [];
			foreach ( $res as $row ) {
				$startId = (int)$row->rev_id;
				$info = $this->interpretPointer( $row->old_text );
				if ( !$info ) {
					echo "Invalid DB:// URL in rev_id {$row->rev_id}\n";
					continue;
				}
				if ( !in_array( $info['cluster'], $this->clusters ) ) {
					echo "Invalid cluster returned in SQL query: {$info['cluster']}\n";
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
			$dbw->newInsertQueryBuilder()
				->insertInto( 'blob_tracking' )
				->rows( $insertBatch )
				->caller( __METHOD__ )->execute();
			$rowsInserted += count( $insertBatch );

			++$batchesDone;
			if ( $batchesDone >= $this->reportingInterval ) {
				$batchesDone = 0;
				echo "$startId / $endId\n";
				$this->waitForReplication();
			}
		}
		echo "Found $rowsInserted revisions\n";
	}

	/**
	 * Scan the text table for orphan text
	 * Orphan text here does not imply DB corruption -- deleted text tracked by the
	 * archive table counts as orphan for our purposes.
	 */
	private function trackOrphanText() {
		# Wait until the blob_tracking table is available in the replica DB
		$dbw = $this->getPrimaryDB();
		$dbr = $this->getReplicaDB();
		$this->getServiceContainer()->getDBLoadBalancerFactory()->waitForReplication( [ 'timeout' => 100_000 ] );

		$textClause = $this->getTextClause();
		$startId = 0;
		$endId = (int)$dbr->newSelectQueryBuilder()
			->select( 'MAX(old_id)' )
			->from( 'text' )
			->caller( __METHOD__ )->fetchField();
		$rowsInserted = 0;
		$batchesDone = 0;

		echo "Finding orphan text...\n";

		# Scan the text table for orphan text
		while ( true ) {
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'old_id', 'old_flags', 'old_text' ] )
				->from( 'text' )
				->leftJoin( 'blob_tracking', null, 'bt_text_id=old_id' )
				->where( [
					$dbr->expr( 'old_id', '>', $startId ),
					$textClause,
					$dbr->expr(
						'old_flags',
						IExpression::LIKE,
						new LikeValue( $dbr->anyString(), 'external', $dbr->anyString() )
					),
					'bt_text_id' => null,
				] )
				->orderBy( 'old_id' )
				->limit( $this->batchSize )
				->caller( __METHOD__ )->fetchResultSet();

			if ( !$res->numRows() ) {
				break;
			}

			$insertBatch = [];
			foreach ( $res as $row ) {
				$startId = (int)$row->old_id;
				$info = $this->interpretPointer( $row->old_text );
				if ( !$info ) {
					echo "Invalid DB:// URL in old_id {$row->old_id}\n";
					continue;
				}
				if ( !in_array( $info['cluster'], $this->clusters ) ) {
					echo "Invalid cluster returned in SQL query\n";
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
			$dbw->newInsertQueryBuilder()
				->insertInto( 'blob_tracking' )
				->rows( $insertBatch )
				->caller( __METHOD__ )->execute();

			$rowsInserted += count( $insertBatch );
			++$batchesDone;
			if ( $batchesDone >= $this->reportingInterval ) {
				$batchesDone = 0;
				echo "$startId / $endId\n";
				$this->waitForReplication();
			}
		}
		echo "Found $rowsInserted orphan text rows\n";
	}

	/**
	 * Scan the blobs table for rows not registered in blob_tracking (and thus not
	 * registered in the text table).
	 *
	 * Orphan blobs are indicative of DB corruption. They are inaccessible and
	 * should probably be deleted.
	 */
	private function findOrphanBlobs() {
		if ( !extension_loaded( 'gmp' ) ) {
			echo "Can't find orphan blobs, need bitfield support provided by GMP.\n";

			return;
		}

		$dbw = $this->getPrimaryDB();
		$lbFactory = $this->getServiceContainer()->getDBLoadBalancerFactory();
		$dbStore = $this->getServiceContainer()->getExternalStoreFactory()->getStore( 'DB' );
		'@phan-var ExternalStoreDB $dbStore'; /** @var ExternalStoreDB $dbStore */

		foreach ( $this->clusters as $cluster ) {
			echo "Searching for orphan blobs in $cluster...\n";
			$lb = $lbFactory->getExternalLB( $cluster );
			try {
				$extDB = $lb->getMaintenanceConnectionRef( DB_REPLICA );
			} catch ( DBConnectionError $e ) {
				if ( strpos( $e->getMessage(), 'Unknown database' ) !== false ) {
					echo "No database on $cluster\n";
				} else {
					echo "Error on $cluster: " . $e->getMessage() . "\n";
				}
				continue;
			}
			$table = $dbStore->getTable( $cluster );
			if ( !$extDB->tableExists( $table, __METHOD__ ) ) {
				echo "No blobs table on cluster $cluster\n";
				continue;
			}
			$startId = 0;
			$batchesDone = 0;
			$actualBlobs = gmp_init( 0 );
			$endId = (int)$extDB->newSelectQueryBuilder()
				->select( 'MAX(blob_id)' )
				->from( $table )
				->caller( __METHOD__ )->fetchField();

			// Build a bitmap of actual blob rows
			while ( true ) {
				$res = $extDB->newSelectQueryBuilder()
					->select( [ 'blob_id' ] )
					->from( $table )
					->where( $extDB->expr( 'blob_id', '>', $startId ) )
					->orderBy( 'blob_id' )
					->limit( $this->batchSize )
					->caller( __METHOD__ )->fetchResultSet();

				if ( !$res->numRows() ) {
					break;
				}

				foreach ( $res as $row ) {
					gmp_setbit( $actualBlobs, $row->blob_id );
					$startId = (int)$row->blob_id;
				}

				++$batchesDone;
				if ( $batchesDone >= $this->reportingInterval ) {
					$batchesDone = 0;
					echo "$startId / $endId\n";
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
					$dbw->newInsertQueryBuilder()
						->insertInto( 'blob_orphans' )
						->rows( $insertBatch )
						->caller( __METHOD__ )->execute();
					$insertBatch = [];
				}

				++$id;
				++$numOrphans;
			}
			if ( $insertBatch ) {
				$dbw->newInsertQueryBuilder()
					->insertInto( 'blob_orphans' )
					->rows( $insertBatch )
					->caller( __METHOD__ )->execute();
			}
			echo "Found $numOrphans orphan(s) in $cluster\n";
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = TrackBlobs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
