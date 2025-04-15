<?php

use MediaWiki\Maintenance\LoggedUpdateMaintenance;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IMaintainableDatabase;

// @codeCoverageIgnoreStart
require_once __DIR__ . "/Maintenance.php";
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that migrates rows from ipblocks to block and block_target.
 * The data is normalized to match the new schema. Any corrupt data that is
 * encountered may be skipped, but will be logged.
 *
 * The old ipblocks table is left touched.
 *
 * @ingroup Maintenance
 * @since 1.42
 */
class MigrateBlocks extends LoggedUpdateMaintenance {
	private IMaintainableDatabase $dbw;

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Copy data from the ipblocks table into the new block and block_target tables'
		);
		$this->addOption(
			'sleep',
			'Sleep time (in seconds) between every batch. Default: 0',
			false,
			true
		);
		// Batch size is typically 1000, but we'll do 500 since there are 2 writes for each ipblock.
		$this->setBatchSize( 500 );
	}

	/** @inheritDoc */
	protected function getUpdateKey() {
		return __CLASS__;
	}

	/** @inheritDoc */
	protected function doDBUpdates() {
		$this->dbw = $this->getDB( DB_PRIMARY );
		if (
			!$this->dbw->tableExists( 'block', __METHOD__ ) ||
			!$this->dbw->tableExists( 'block_target', __METHOD__ )
		) {
			$this->fatalError( "Run update.php to create the block and block_target tables." );
		}
		if ( !$this->dbw->tableExists( 'ipblocks', __METHOD__ ) ) {
			$this->output( "No ipblocks table, skipping migration to block_target.\n" );
			return true;
		}

		$this->output( "Populating the block and block_target tables\n" );
		$migratedCount = 0;

		$id = 0;
		while ( $id !== null ) {
			$this->output( "Migrating ipblocks with ID > $id...\n" );
			[ $numBlocks, $id ] = $this->handleBatch( $id );
			$migratedCount += $numBlocks;
		}

		$this->output( "Completed migration of $migratedCount ipblocks to block and block_target.\n" );

		return true;
	}

	/**
	 * Handle up to $this->getBatchSize() pairs of INSERTs,
	 * one for block and one for block_target.
	 *
	 * @param int $lowId
	 * @return array [ number of blocks migrated, last ipb_id or null ]
	 */
	private function handleBatch( int $lowId ): array {
		$migratedCount = 0;
		$res = $this->dbw->newSelectQueryBuilder()
			->select( '*' )
			->from( 'ipblocks' )
			->leftJoin( 'block', null, 'bl_id=ipb_id' )
			->where( [
				$this->dbw->expr( 'ipb_id', '>', $lowId ),
				'bl_id' => null
			] )
			->orderBy( 'ipb_id' )
			->limit( $this->getBatchSize() )
			->caller( __METHOD__ )
			->fetchResultSet();

		if ( !$res->numRows() ) {
			return [ $migratedCount, null ];
		}

		$highestId = $lowId;
		foreach ( $res as $row ) {
			$highestId = $row->ipb_id;
			$isIP = IPUtils::isValid( $row->ipb_address );
			$isRange = IPUtils::isValidRange( $row->ipb_address );
			$isIPOrRange = $isIP || $isRange;
			$ipHex = null;
			if ( $isIP ) {
				$ipHex = IPUtils::toHex( $row->ipb_address );
			} elseif ( $isRange ) {
				$ipHex = $row->ipb_range_start;
			} elseif ( (int)$row->ipb_user === 0 ) {
				// There was data corruption circa 2006 and 2011 where some accounts were
				// blocked as if they were logged out users. Here we'll prune the erroneous
				// data by simply not copying it to the new schema.
				$this->output( "ipblock with ID $row->ipb_id: account block with ipb_user=0, skipping…\n" );
				continue;
			}

			// Insert into block_target
			$blockTarget = [
				'bt_address'     => $isIPOrRange ? $row->ipb_address : null,
				'bt_user'        => $isIPOrRange ? null : $row->ipb_user,
				'bt_user_text'   => $isIPOrRange ? null : $row->ipb_address,
				'bt_auto'        => $row->ipb_auto,
				'bt_range_start' => $isRange ? $row->ipb_range_start : null,
				'bt_range_end'   => $isRange ? $row->ipb_range_end : null,
				'bt_ip_hex'      => $ipHex,
				'bt_count'       => 1
			];
			$this->dbw->newInsertQueryBuilder()
				->insertInto( 'block_target' )
				->row( $blockTarget )
				->caller( __METHOD__ )
				->execute();
			$insertId = $this->dbw->insertId();
			if ( !$insertId ) {
				$this->fatalError(
					"ipblock with ID $row->ipb_id: Failed to create block_target. Insert ID is falsy!"
				);
			}

			// Insert into block
			$block = [
				'bl_id'               => $row->ipb_id,
				'bl_target'           => $insertId,
				'bl_by_actor'         => $row->ipb_by_actor,
				'bl_reason_id'        => $row->ipb_reason_id,
				'bl_timestamp'        => $row->ipb_timestamp,
				'bl_anon_only'        => $row->ipb_anon_only,
				'bl_create_account'   => $row->ipb_create_account,
				'bl_enable_autoblock' => $row->ipb_enable_autoblock,
				'bl_expiry'           => $row->ipb_expiry,
				'bl_deleted'          => $row->ipb_deleted,
				'bl_block_email'      => $row->ipb_block_email,
				'bl_allow_usertalk'   => $row->ipb_allow_usertalk,
				// See T282890
				'bl_parent_block_id'  => (int)$row->ipb_parent_block_id === 0 ? null : $row->ipb_parent_block_id,
				'bl_sitewide'         => $row->ipb_sitewide,
			];
			$this->dbw->newInsertQueryBuilder()
				->insertInto( 'block' )
				->ignore()
				->row( $block )
				->caller( __METHOD__ )
				->execute();
			if ( $this->dbw->affectedRows() ) {
				$migratedCount++;
			}
		}

		$this->output( "Migrated $migratedCount blocks\n" );

		// Sleep between batches for replication to catch up
		$this->waitForReplication();
		$sleep = (int)$this->getOption( 'sleep', 0 );
		if ( $sleep > 0 ) {
			sleep( $sleep );
		}

		return [ $migratedCount, $highestId ];
	}
}

// @codeCoverageIgnoreStart
$maintClass = MigrateBlocks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
