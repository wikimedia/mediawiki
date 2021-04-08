<?php
/**
 * Class for DatabaseBlock objects to interact with the database
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
 */

namespace MediaWiki\Block;

use ActorMigration;
use AutoCommitUpdate;
use CommentStore;
use DeferredUpdates;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MWException;
use Psr\Log\LoggerInterface;
use ReadOnlyMode;
use User;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @since 1.36
 *
 * @author DannyS712
 */
class DatabaseBlockStore {

	/** @var ServiceOptions */
	private $options;

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'PutIPinRC',
		'BlockDisablesLogin',
	];

	/** @var LoggerInterface */
	private $logger;

	/** @var ActorMigration */
	private $actorMigration;

	/** @var BlockRestrictionStore */
	private $blockRestrictionStore;

	/** @var CommentStore */
	private $commentStore;

	/** @var HookRunner */
	private $hookRunner;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/**
	 * @param ServiceOptions $options
	 * @param LoggerInterface $logger
	 * @param ActorMigration $actorMigration
	 * @param BlockRestrictionStore $blockRestrictionStore
	 * @param CommentStore $commentStore
	 * @param HookContainer $hookContainer
	 * @param ILoadBalancer $loadBalancer
	 * @param ReadOnlyMode $readOnlyMode
	 */
	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
		ActorMigration $actorMigration,
		BlockRestrictionStore $blockRestrictionStore,
		CommentStore $commentStore,
		HookContainer $hookContainer,
		ILoadBalancer $loadBalancer,
		ReadOnlyMode $readOnlyMode
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->logger = $logger;
		$this->actorMigration = $actorMigration;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->commentStore = $commentStore;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->loadBalancer = $loadBalancer;
		$this->readOnlyMode = $readOnlyMode;
	}

	/**
	 * Delete expired blocks from the ipblocks table
	 *
	 * @internal only public for use in DatabaseBlock
	 */
	public function purgeExpiredBlocks() {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return;
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );
		$blockRestrictionStore = $this->blockRestrictionStore;

		DeferredUpdates::addUpdate( new AutoCommitUpdate(
			$dbw,
			__METHOD__,
			static function ( IDatabase $dbw, $fname ) use ( $blockRestrictionStore ) {
				$ids = $dbw->selectFieldValues(
					'ipblocks',
					'ipb_id',
					[ 'ipb_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ],
					$fname
				);
				if ( $ids ) {
					$blockRestrictionStore->deleteByBlockId( $ids );
					$dbw->delete( 'ipblocks', [ 'ipb_id' => $ids ], $fname );
				}
			}
		) );
	}

	/**
	 * Insert a block into the block table. Will fail if there is a conflicting
	 * block (same name and options) already in the database.
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase|null $database Database to use if not the same as the one in the load balancer
	 * @return bool|array False on failure, assoc array on success:
	 *      ('id' => block ID, 'autoIds' => array of autoblock IDs)
	 * @throws MWException
	 */
	public function insertBlock(
		DatabaseBlock $block,
		IDatabase $database = null
	) {
		if ( !$block->getBlocker() || $block->getBlocker()->getName() === '' ) {
			throw new MWException( 'Cannot insert a block without a blocker set' );
		}

		$this->logger->debug( 'Inserting block; timestamp ' . $block->getTimestamp() );

		// TODO T258866 - consider passing the database
		$this->purgeExpiredBlocks();

		$dbw = $database ?: $this->loadBalancer->getConnectionRef( DB_MASTER );
		$row = $this->getArrayForDatabaseBlock( $block, $dbw );

		$dbw->insert( 'ipblocks', $row, __METHOD__, [ 'IGNORE' ] );
		$affected = $dbw->affectedRows();

		if ( $affected ) {
			$block->setId( $dbw->insertId() );
			if ( $block->getRawRestrictions() ) {
				$this->blockRestrictionStore->insert( $block->getRawRestrictions() );
			}
		}

		// Don't collide with expired blocks.
		// Do this after trying to insert to avoid locking.
		if ( !$affected ) {
			// T96428: The ipb_address index uses a prefix on a field, so
			// use a standard SELECT + DELETE to avoid annoying gap locks.
			$ids = $dbw->selectFieldValues(
				'ipblocks',
				'ipb_id',
				[
					'ipb_address' => $row['ipb_address'],
					'ipb_user' => $row['ipb_user'],
					'ipb_expiry < ' . $dbw->addQuotes( $dbw->timestamp() )
				],
				__METHOD__
			);
			if ( $ids ) {
				$dbw->delete( 'ipblocks', [ 'ipb_id' => $ids ], __METHOD__ );
				$this->blockRestrictionStore->deleteByBlockId( $ids );
				$dbw->insert( 'ipblocks', $row, __METHOD__, [ 'IGNORE' ] );
				$affected = $dbw->affectedRows();
				$block->setId( $dbw->insertId() );
				if ( $block->getRawRestrictions() ) {
					$this->blockRestrictionStore->insert( $block->getRawRestrictions() );
				}
			}
		}

		if ( $affected ) {
			$autoBlockIds = $this->doRetroactiveAutoblock( $block );

			if ( $this->options->get( 'BlockDisablesLogin' ) ) {
				$target = $block->getTarget();
				if ( $target instanceof User ) {
					// Change user login token to force them to be logged out.
					$target->setToken();
					$target->saveSettings();
				}
			}

			return [ 'id' => $block->getId(), 'autoIds' => $autoBlockIds ];
		}

		return false;
	}

	/**
	 * Update a block in the DB with new parameters.
	 * The ID field needs to be loaded first.
	 *
	 * @param DatabaseBlock $block
	 * @return bool|array False on failure, array on success:
	 *   ('id' => block ID, 'autoIds' => array of autoblock IDs)
	 */
	public function updateBlock( DatabaseBlock $block ) {
		$this->logger->debug( 'Updating block; timestamp ' . $block->getTimestamp() );

		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );
		$row = $this->getArrayForDatabaseBlock( $block, $dbw );
		$dbw->startAtomic( __METHOD__ );

		$result = $dbw->update(
			'ipblocks',
			$row,
			[ 'ipb_id' => $block->getId() ],
			__METHOD__
		);

		// Only update the restrictions if they have been modified.
		$restrictions = $block->getRawRestrictions();
		if ( $restrictions !== null ) {
			// An empty array should remove all of the restrictions.
			if ( empty( $restrictions ) ) {
				$success = $this->blockRestrictionStore->deleteByBlockId( $block->getId() );
			} else {
				$success = $this->blockRestrictionStore->update( $restrictions );
			}
			// Update the result. The first false is the result, otherwise, true.
			$result = $result && $success;
		}

		if ( $block->isAutoblocking() ) {
			// update corresponding autoblock(s) (T50813)
			$dbw->update(
				'ipblocks',
				$this->getArrayForAutoblockUpdate( $block ),
				[ 'ipb_parent_block_id' => $block->getId() ],
				__METHOD__
			);

			// Only update the restrictions if they have been modified.
			if ( $restrictions !== null ) {
				$this->blockRestrictionStore->updateByParentBlockId(
					$block->getId(),
					$restrictions
				);
			}
		} else {
			// autoblock no longer required, delete corresponding autoblock(s)
			$this->blockRestrictionStore->deleteByParentBlockId( $block->getId() );
			$dbw->delete(
				'ipblocks',
				[ 'ipb_parent_block_id' => $block->getId() ],
				__METHOD__
			);
		}

		$dbw->endAtomic( __METHOD__ );

		if ( $result ) {
			$autoBlockIds = $this->doRetroactiveAutoblock( $block );
			return [ 'id' => $block->getId(), 'autoIds' => $autoBlockIds ];
		}

		return false;
	}

	/**
	 * Delete a DatabaseBlock from the database
	 *
	 * @param DatabaseBlock $block
	 * @return bool whether it was deleted
	 * @throws MWException
	 */
	public function deleteBlock( DatabaseBlock $block ) : bool {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}

		$blockId = $block->getId();

		if ( !$blockId ) {
			throw new MWException(
				__METHOD__ . " requires that a block id be set\n"
			);
		}
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );

		$this->blockRestrictionStore->deleteByParentBlockId( $blockId );
		$dbw->delete(
			'ipblocks',
			[ 'ipb_parent_block_id' => $blockId ],
			__METHOD__
		);

		$this->blockRestrictionStore->deleteByBlockId( $blockId );
		$dbw->delete(
			'ipblocks',
			[ 'ipb_id' => $blockId ],
			__METHOD__
		);

		return $dbw->affectedRows() > 0;
	}

	/**
	 * Get an array suitable for passing to $dbw->insert() or $dbw->update()
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase $dbw
	 * @return array
	 */
	private function getArrayForDatabaseBlock(
		DatabaseBlock $block,
		IDatabase $dbw
	) : array {
		$expiry = $dbw->encodeExpiry( $block->getExpiry() );

		$target = $block->getTarget();
		$forcedTargetId = $block->getForcedTargetId();
		if ( $forcedTargetId ) {
			$userId = $forcedTargetId;
		} elseif ( $target instanceof User ) {
			$userId = $target->getId();
		} else {
			$userId = 0;
		}

		$blockArray = [
			'ipb_address'          => (string)$target,
			'ipb_user'             => $userId,
			'ipb_timestamp'        => $dbw->timestamp( $block->getTimestamp() ),
			'ipb_auto'             => $block->getType() === AbstractBlock::TYPE_AUTO,
			'ipb_anon_only'        => !$block->isHardblock(),
			'ipb_create_account'   => $block->isCreateAccountBlocked(),
			'ipb_enable_autoblock' => $block->isAutoblocking(),
			'ipb_expiry'           => $expiry,
			'ipb_range_start'      => $block->getRangeStart(),
			'ipb_range_end'        => $block->getRangeEnd(),
			'ipb_deleted'          => intval( $block->getHideName() ), // typecast required for SQLite
			'ipb_block_email'      => $block->isEmailBlocked(),
			'ipb_allow_usertalk'   => $block->isUsertalkEditAllowed(),
			'ipb_parent_block_id'  => $block->getParentBlockId(),
			'ipb_sitewide'         => $block->isSitewide(),
		];
		$commentArray = $this->commentStore->insert(
			$dbw,
			'ipb_reason',
			$block->getReasonComment()
		);
		$actorArray = $this->actorMigration->getInsertValues(
			$dbw,
			'ipb_by',
			$block->getBlocker()
		);

		$combinedArray = $blockArray + $commentArray + $actorArray;
		return $combinedArray;
	}

	/**
	 * Get an array suitable for autoblock updates
	 *
	 * @param DatabaseBlock $block
	 * @return array
	 */
	private function getArrayForAutoblockUpdate( DatabaseBlock $block ) : array {
		$blockArray = [
			'ipb_create_account' => $block->isCreateAccountBlocked(),
			'ipb_deleted'        => (int)$block->getHideName(), // typecast required for SQLite
			'ipb_allow_usertalk' => $block->isUsertalkEditAllowed(),
			'ipb_sitewide'       => $block->isSitewide(),
		];

		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );
		$commentArray = $this->commentStore->insert(
			$dbw,
			'ipb_reason',
			$block->getReasonComment()
		);
		$actorArray = $this->actorMigration->getInsertValues(
			$dbw,
			'ipb_by',
			$block->getBlocker()
		);

		$combinedArray = $blockArray + $commentArray + $actorArray;
		return $combinedArray;
	}

	/**
	 * Handles retroactively autoblocking the last IP used by the user (if it is a user)
	 * blocked by an auto block.
	 *
	 * @param DatabaseBlock $block
	 * @return array IDs of retroactive autoblocks made
	 */
	private function doRetroactiveAutoblock( DatabaseBlock $block ) : array {
		$autoBlockIds = [];
		// If autoblock is enabled, autoblock the LAST IP(s) used
		if ( $block->isAutoblocking() && $block->getType() == AbstractBlock::TYPE_USER ) {
			$this->logger->debug(
				'Doing retroactive autoblocks for ' . $block->getTarget()
			);

			$hookAutoBlocked = [];
			$continue = $this->hookRunner->onPerformRetroactiveAutoblock(
				$block,
				$hookAutoBlocked
			);

			if ( $continue ) {
				$coreAutoBlocked = $this->performRetroactiveAutoblock( $block );
				$autoBlockIds = array_merge( $hookAutoBlocked, $coreAutoBlocked );
			} else {
				$autoBlockIds = $hookAutoBlocked;
			}
		}
		return $autoBlockIds;
	}

	/**
	 * Actually retroactively autoblocks the last IP used by the user (if it is a user)
	 * blocked by this block. This will use the recentchanges table.
	 *
	 * @param DatabaseBlock $block
	 * @return array
	 */
	private function performRetroactiveAutoblock( DatabaseBlock $block ) : array {
		if ( !$this->options->get( 'PutIPinRC' ) ) {
			// No IPs in the recent changes table to autoblock
			return [];
		}

		list( $target, $type ) = $block->getTargetAndType();
		if ( $type !== AbstractBlock::TYPE_USER ) {
			// Autoblocks only apply to users
			return [];
		}

		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$rcQuery = $this->actorMigration->getWhere( $dbr, 'rc_user', $target, false );

		$options = [
			'ORDER BY' => 'rc_timestamp DESC',
			'LIMIT' => 1,
		];

		$res = $dbr->select(
			[ 'recentchanges' ] + $rcQuery['tables'],
			[ 'rc_ip' ],
			$rcQuery['conds'],
			__METHOD__,
			$options,
			$rcQuery['joins']
		);

		if ( !$res->numRows() ) {
			$this->logger->debug( 'No IP found to retroactively autoblock' );
			return [];
		}

		$blockIds = [];
		foreach ( $res as $row ) {
			if ( $row->rc_ip ) {
				$id = $block->doAutoblock( $row->rc_ip );
				if ( $id ) {
					$blockIds[] = $id;
				}
			}
		}
		return $blockIds;
	}

}
