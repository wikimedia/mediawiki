<?php

use MediaWiki\Maintenance\Maintenance;
use Wikimedia\IPUtils;

require_once __DIR__ . '/Maintenance.php';

class CleanupBlocks extends Maintenance {
	private bool $dryRun = false;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Fix referential integrity issues in block and block_target tables' );
		$this->addOption( 'dry-run', 'Just report, don\'t fix anything' );
	}

	public function execute() {
		$this->dryRun = $this->hasOption( 'dry-run' );

		$this->deleteOrphanBlockTargets();
		$this->deleteTargetlessBlocks();
		$this->normalizeAddresses();
		$this->mergeDuplicateBlockTargets();
		$this->fixTargetCounts();
	}

	/**
	 * Delete any block_target rows that have no corresponding blocks
	 */
	private function deleteOrphanBlockTargets() {
		$dbr = $this->getReplicaDB();
		$badIds = $dbr->newSelectQueryBuilder()
			->select( 'bt_id' )
			->from( 'block_target' )
			->leftJoin( 'block', null, 'bt_id=bl_target' )
			->where( [ 'bl_target' => null ] )
			->caller( __METHOD__ )
			->fetchFieldValues();

		foreach ( $badIds as $id ) {
			$this->deleteOrphanBlockTarget( (int)$id );
		}
	}

	/**
	 * Verify and delete an orphan block_target row
	 * @param int $id
	 */
	private function deleteOrphanBlockTarget( int $id ) {
		$this->output( "Deleting orphan bt_id=$id: " );
		if ( $this->dryRun ) {
			$this->output( "dry run\n" );
			return;
		}
		$dbw = $this->getPrimaryDB();
		$dbw->startAtomic( __METHOD__ );
		$lockingUsage = $dbw->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'block' )
			->where( [ 'bl_target' => $id ] )
			->forUpdate()
			->caller( __METHOD__ )
			->fetchField();
		if ( $lockingUsage ) {
			$dbw->endAtomic( __METHOD__ );
			$this->output( "primary usage count is non-zero\n" );
			return;
		}
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'block_target' )
			->where( [ 'bt_id' => $id ] )
			->caller( __METHOD__ )
			->execute();
		$affected = $dbw->affectedRows();
		$dbw->endAtomic( __METHOD__ );
		$this->output( $affected ? "OK\n" : "no rows affected\n" );
	}

	/**
	 * Delete blocks which have a bl_target pointing to a non-existent bt_id
	 */
	private function deleteTargetlessBlocks() {
		$dbr = $this->getReplicaDB();
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'bl_id', 'bl_target' ] )
			->from( 'block' )
			->leftJoin( 'block_target', null, 'bt_id=bl_target' )
			->where( [ 'bt_id' => null ] )
			->caller( __METHOD__ )
			->fetchResultSet();
		foreach ( $res as $row ) {
			$this->deleteTargetlessBlock( (int)$row->bl_id, (int)$row->bl_target );
		}
	}

	/**
	 * Verify and delete a block with no target
	 *
	 * @param int $blockId
	 * @param int $targetId
	 */
	private function deleteTargetlessBlock( int $blockId, int $targetId ) {
		$this->output( "Deleting block $blockId on non-existent target $targetId: " );
		if ( $this->dryRun ) {
			$this->output( "dry run\n" );
			return;
		}
		$dbw = $this->getPrimaryDB();
		$dbw->startAtomic( __METHOD__ );
		$lockingTargetCount = $dbw->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'block_target' )
			->where( [ 'bt_id' => $targetId ] )
			->forUpdate()
			->caller( __METHOD__ )
			->fetchField();
		if ( $lockingTargetCount ) {
			$this->output( "target exists in primary\n" );
			$dbw->endAtomic( __METHOD__ );
			return;
		}
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'block' )
			->where( [ 'bl_id' => $blockId, 'bl_target' => $targetId ] )
			->caller( __METHOD__ )
			->execute();
		$affected = $dbw->affectedRows();
		$dbw->endAtomic( __METHOD__ );
		$this->output( $affected ? "OK\n" : "no rows affected\n" );
	}

	/**
	 * Fix IP address normalization issues:
	 *   - Leading zeroes like 1.1.1.001
	 *   - Lower-case IPv6 addresses like 200e::
	 *   - Non-zero range suffixes like 1.1.1.111/24
	 */
	private function normalizeAddresses() {
		$dbr = $this->getReplicaDB();
		$dbType = $dbr->getType();
		if ( $dbType !== 'mysql' ) {
			$this->output( "Skipping IP address normalization: not implemented on $dbType\n" );
			return;
		}
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'bt_id', 'bt_address' ] )
			->from( 'block_target' )
			->where( [ 'bt_user' => null ] )
			->andWhere( 'bt_range_start IS NOT NULL OR ' .
				'bt_address RLIKE \'(^|[.:])0[0-9]|[a-f]|::\'' )
			->caller( __METHOD__ )
			->fetchResultSet();
		$writeDone = false;
		foreach ( $res as $row ) {
			$addr = $row->bt_address;
			if ( IPUtils::isValid( $addr ) ) {
				$norm = IPUtils::sanitizeIP( $addr );
			} elseif ( IPUtils::isValidRange( $addr ) ) {
				$norm = IPUtils::sanitizeRange( $addr );
			} else {
				continue;
			}
			if ( $addr !== $norm && is_string( $norm ) ) {
				$this->normalizeAddress( (int)$row->bt_id, $addr, $norm );
				$writeDone = true;
			}
		}
		if ( $writeDone ) {
			// Ensure that mergeDuplicateBlockTargets() sees our changes
			$this->waitForReplication();
		}
	}

	/**
	 * Normalize the IP address in a single block_target row
	 *
	 * @param int $targetId
	 * @param string $address
	 * @param string $normalizedAddress
	 */
	private function normalizeAddress( int $targetId, string $address, string $normalizedAddress ) {
		$this->output( "Normalizing bt_id=$targetId $address -> $normalizedAddress: " );
		if ( $this->dryRun ) {
			$this->output( "dry run\n" );
			return;
		}
		$dbw = $this->getPrimaryDB();
		$dbw->startAtomic( __METHOD__ );
		$primaryAddr = $dbw->newSelectQueryBuilder()
			->select( 'bt_address' )
			->from( 'block_target' )
			->where( [ 'bt_id' => $targetId ] )
			->forUpdate()
			->caller( __METHOD__ )
			->fetchField();
		if ( $primaryAddr === false ) {
			$this->output( "missing in primary\n" );
			return;
		}
		if ( $primaryAddr !== $address ) {
			$this->output( "changed in primary\n" );
			return;
		}
		$dbw->newUpdateQueryBuilder()
			->update( 'block_target' )
			->set( [ 'bt_address' => $normalizedAddress ] )
			->where( [ 'bt_id' => $targetId ] )
			->caller( __METHOD__ )
			->execute();
		$dbw->endAtomic( __METHOD__ );
		$this->output( "done\n" );
	}

	/**
	 * Merge block_target rows referring to the same user, IP address or range
	 */
	private function mergeDuplicateBlockTargets() {
		$dbr = $this->getReplicaDB();
		$rawGroups = $this->getReplicaDB()->newSelectQueryBuilder()
			->select( 'GROUP_CONCAT(bt_id)' )
			->from( 'block_target' )
			->where( $dbr->expr( 'bt_user', '!=', null ) )
			->groupBy( 'bt_user' )
			->having( 'COUNT(*) > 1' )
			->caller( __METHOD__ )
			->fetchFieldValues();
		$this->processIdGroups( $rawGroups );

		$rawGroups = $this->getReplicaDB()->newSelectQueryBuilder()
			->select( 'GROUP_CONCAT(bt_id)' )
			->from( 'block_target' )
			->where( $dbr->expr( 'bt_address', '!=', null ) )
			->groupBy( [ 'bt_auto', 'bt_address' ] )
			->having( 'COUNT(*) > 1' )
			->caller( __METHOD__ )
			->fetchFieldValues();
		$this->processIdGroups( $rawGroups );
	}

	/**
	 * Process a set of duplicate targets
	 * @param string[] $rawGroups the ID groups, delimited by commas
	 */
	private function processIdGroups( $rawGroups ) {
		foreach ( $rawGroups as $blob ) {
			$group = array_map( 'intval', explode( ',', $blob ) );
			sort( $group );
			$main = array_shift( $group );
			$this->mergeGroup( $main, $group );
		}
	}

	/**
	 * Merge a group of duplicate targets
	 * @param int $mainId The ID to merge into
	 * @param int[] $badIds The IDs to delete
	 */
	private function mergeGroup( int $mainId, array $badIds ) {
		$this->output( 'Merging bt_id ' . implode( ',', $badIds ) . " into $mainId: " );
		if ( $this->dryRun ) {
			$this->output( "dry run\n" );
			return;
		}

		$dbw = $this->getPrimaryDB();
		$dbw->startAtomic( __METHOD__ );

		// Check that the targets are identical in the primary
		$fieldsToTest = [ 'bt_address', 'bt_user', 'bt_user_text', 'bt_auto' ];
		$mainRow = $dbw->newSelectQueryBuilder()
			->select( $fieldsToTest )
			->from( 'block_target' )
			->where( [ 'bt_id' => $mainId ] )
			->forUpdate()
			->caller( __METHOD__ )
			->fetchRow();
		$badRows = $dbw->newSelectQueryBuilder()
			->select( $fieldsToTest )
			->select( 'bt_id' )
			->from( 'block_target' )
			->where( [ 'bt_id' => $badIds ] )
			->forUpdate()
			->caller( __METHOD__ )
			->fetchResultSet();

		if ( $badRows->numRows() !== count( $badIds ) ) {
			$this->output( "some IDs are not present in the primary\n" );
			$dbw->endAtomic( __METHOD__ );
			return;
		}

		foreach ( $badRows as $badRow ) {
			foreach ( $fieldsToTest as $field ) {
				if ( $mainRow->$field !== $badRow->$field ) {
					$this->output( "mismatch in $field for bt_id={$badRow->bt_id}\n" );
					$dbw->endAtomic( __METHOD__ );
					return;
				}
			}
		}

		// Update the block rows for the targets to be deleted
		$dbw->newUpdateQueryBuilder()
			->update( 'block' )
			->set( [ 'bl_target' => $mainId ] )
			->where( [ 'bl_target' => $badIds ] )
			->caller( __METHOD__ )
			->execute();
		$blockCount = $dbw->affectedRows();

		// Delete the bad targets
		$dbw->newDeleteQueryBuilder()
			->delete( 'block_target' )
			->where( [ 'bt_id' => $badIds ] )
			->caller( __METHOD__ )
			->execute();

		// Update bt_count for the remaining target
		$dbw->newUpdateQueryBuilder()
			->update( 'block_target' )
			->set( 'bt_count=bt_count + ' . $blockCount )
			->where( [ 'bt_id' => $mainId ] )
			->caller( __METHOD__ )
			->execute();

		$dbw->endAtomic( __METHOD__ );
		$this->output( "done\n" );
	}

	/**
	 * Find and fix incorrect bt_count values
	 */
	private function fixTargetCounts() {
		$dbr = $this->getReplicaDB();
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'bt_id', 'bt_count', 'real_count' => 'COUNT(*)' ] )
			->from( 'block' )
			->join( 'block_target', null, 'bt_id=bl_target' )
			->groupBy( [ 'bt_id', 'bt_count' ] )
			->having( 'COUNT(*) != bt_count' )
			->caller( __METHOD__ )
			->fetchResultSet();

		foreach ( $res as $row ) {
			$this->fixTargetCount( (int)$row->bt_id, (int)$row->bt_count, (int)$row->real_count );
		}
	}

	/**
	 * Fix an incorrect target count
	 *
	 * @param int $targetId The bt_id value
	 * @param int $badCount The bt_count value, from the replica
	 * @param int $replicaCount The number of associated block rows, from the replica
	 */
	private function fixTargetCount( int $targetId, int $badCount, int $replicaCount ) {
		$this->output( "Fixing bt_id=$targetId count $badCount -> $replicaCount: " );
		if ( $this->dryRun ) {
			$this->output( "dry run\n" );
			return;
		}

		$dbw = $this->getPrimaryDB();
		$dbw->startAtomic( __METHOD__ );
		$primaryCount = (int)$dbw->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'block' )
			->where( [ 'bl_target' => $targetId ] )
			->forUpdate()
			->caller( __METHOD__ )
			->fetchField();

		if ( $primaryCount !== $replicaCount ) {
			$dbw->endAtomic( __METHOD__ );
			$this->output( "changed in primary, skipping\n" );
			return;
		}

		$dbw->newUpdateQueryBuilder()
			->update( 'block_target' )
			->set( [ 'bt_count' => $primaryCount ] )
			->where( [
				'bt_id' => $targetId,
				'bt_count' => $badCount
			] )
			->caller( __METHOD__ )
			->execute();
		$affected = $dbw->affectedRows();
		$dbw->endAtomic( __METHOD__ );
		$this->output( $affected ? "OK\n" : "no rows affected\n" );
	}
}

$maintClass = CleanupBlocks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
