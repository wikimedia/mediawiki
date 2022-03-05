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

use AutoCommitUpdate;
use CommentStore;
use DeferredUpdates;
use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\UserFactory;
use MWException;
use Psr\Log\LoggerInterface;
use ReadOnlyMode;
use Wikimedia\Assert\Assert;
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
		'UpdateRowsPerQuery',
	];

	/** @var LoggerInterface */
	private $logger;

	/** @var ActorStoreFactory */
	private $actorStoreFactory;

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

	/** @var UserFactory */
	private $userFactory;

	/**
	 * @param ServiceOptions $options
	 * @param LoggerInterface $logger
	 * @param ActorStoreFactory $actorStoreFactory
	 * @param BlockRestrictionStore $blockRestrictionStore
	 * @param CommentStore $commentStore
	 * @param HookContainer $hookContainer
	 * @param ILoadBalancer $loadBalancer
	 * @param ReadOnlyMode $readOnlyMode
	 * @param UserFactory $userFactory
	 */
	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
		ActorStoreFactory $actorStoreFactory,
		BlockRestrictionStore $blockRestrictionStore,
		CommentStore $commentStore,
		HookContainer $hookContainer,
		ILoadBalancer $loadBalancer,
		ReadOnlyMode $readOnlyMode,
		UserFactory $userFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->logger = $logger;
		$this->actorStoreFactory = $actorStoreFactory;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->commentStore = $commentStore;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->loadBalancer = $loadBalancer;
		$this->readOnlyMode = $readOnlyMode;
		$this->userFactory = $userFactory;
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

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );
		$store = $this->blockRestrictionStore;
		$limit = $this->options->get( 'UpdateRowsPerQuery' );

		DeferredUpdates::addUpdate( new AutoCommitUpdate(
			$dbw,
			__METHOD__,
			static function ( IDatabase $dbw, $fname ) use ( $store, $limit ) {
				$ids = $dbw->selectFieldValues(
					'ipblocks',
					'ipb_id',
					[ 'ipb_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ],
					$fname,
					// Set a limit to avoid causing read-only mode (T301742)
					[ 'LIMIT' => $limit ]
				);
				if ( $ids ) {
					$store->deleteByBlockId( $ids );
					$dbw->delete( 'ipblocks', [ 'ipb_id' => $ids ], $fname );
				}
			}
		) );
	}

	/**
	 * Throws an exception if the given database connection does not match the
	 * given wiki ID.
	 *
	 * @param ?IDatabase $db
	 * @param string|false $expectedWiki
	 */
	private function checkDatabaseDomain( ?IDatabase $db, $expectedWiki ) {
		if ( $db ) {
			$dbDomain = $db->getDomainID();
			$storeDomain = $this->loadBalancer->resolveDomainID( $expectedWiki );
			if ( $dbDomain !== $storeDomain ) {
				throw new InvalidArgumentException(
					"DB connection domain '$dbDomain' does not match '$storeDomain'"
				);
			}
		} else {
			if ( $expectedWiki !== Block::LOCAL ) {
				throw new InvalidArgumentException(
					"Must provide a database connection for wiki '$expectedWiki'."
				);
			}
		}
	}

	/**
	 * Insert a block into the block table. Will fail if there is a conflicting
	 * block (same name and options) already in the database.
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase|null $database Database to use if not the same as the one in the load balancer.
	 *                       Must connect to the wiki identified by $block->getBlocker->getWikiId().
	 * @return bool|array False on failure, assoc array on success:
	 *      ('id' => block ID, 'autoIds' => array of autoblock IDs)
	 * @throws MWException
	 */
	public function insertBlock(
		DatabaseBlock $block,
		IDatabase $database = null
	) {
		$blocker = $block->getBlocker();
		if ( !$blocker || $blocker->getName() === '' ) {
			throw new MWException( 'Cannot insert a block without a blocker set' );
		}

		$this->checkDatabaseDomain( $database, $block->getWikiId() );

		$this->logger->debug( 'Inserting block; timestamp ' . $block->getTimestamp() );

		// TODO T258866 - consider passing the database
		$this->purgeExpiredBlocks();

		$dbw = $database ?: $this->loadBalancer->getConnectionRef( DB_PRIMARY );
		$row = $this->getArrayForDatabaseBlock( $block, $dbw );

		$dbw->insert( 'ipblocks', $row, __METHOD__, [ 'IGNORE' ] );
		$affected = $dbw->affectedRows();

		if ( $affected ) {
			$block->setId( $dbw->insertId() );
			$restrictions = $block->getRawRestrictions();
			if ( $restrictions ) {
				$this->blockRestrictionStore->insert( $restrictions );
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
				$restrictions = $block->getRawRestrictions();
				if ( $restrictions ) {
					$this->blockRestrictionStore->insert( $restrictions );
				}
			}
		}

		if ( $affected ) {
			$autoBlockIds = $this->doRetroactiveAutoblock( $block );

			if ( $this->options->get( 'BlockDisablesLogin' ) ) {
				$targetUserIdentity = $block->getTargetUserIdentity();
				if ( $targetUserIdentity ) {
					$targetUser = $this->userFactory->newFromUserIdentity( $targetUserIdentity );
					// Change user login token to force them to be logged out.
					$targetUser->setToken();
					$targetUser->saveSettings();
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

		// We could allow cross-wiki updates here, just like we do in insertBlock().
		Assert::parameter(
			$block->getWikiId() === Block::LOCAL,
			'$block->getWikiId()',
			'must belong to the local wiki.'
		);
		$blockId = $block->getId();
		if ( !$blockId ) {
			throw new MWException(
				__METHOD__ . " requires that a block id be set\n"
			);
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );
		$row = $this->getArrayForDatabaseBlock( $block, $dbw );
		$dbw->startAtomic( __METHOD__ );

		$result = $dbw->update(
			'ipblocks',
			$row,
			[ 'ipb_id' => $blockId ],
			__METHOD__
		);

		// Only update the restrictions if they have been modified.
		$restrictions = $block->getRawRestrictions();
		if ( $restrictions !== null ) {
			// An empty array should remove all of the restrictions.
			if ( empty( $restrictions ) ) {
				$success = $this->blockRestrictionStore->deleteByBlockId( $blockId );
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
				[ 'ipb_parent_block_id' => $blockId ],
				__METHOD__
			);

			// Only update the restrictions if they have been modified.
			if ( $restrictions !== null ) {
				$this->blockRestrictionStore->updateByParentBlockId(
					$blockId,
					$restrictions
				);
			}
		} else {
			// autoblock no longer required, delete corresponding autoblock(s)
			$this->blockRestrictionStore->deleteByParentBlockId( $blockId );
			$dbw->delete(
				'ipblocks',
				[ 'ipb_parent_block_id' => $blockId ],
				__METHOD__
			);
		}

		$dbw->endAtomic( __METHOD__ );

		if ( $result ) {
			$autoBlockIds = $this->doRetroactiveAutoblock( $block );
			return [ 'id' => $blockId, 'autoIds' => $autoBlockIds ];
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
	public function deleteBlock( DatabaseBlock $block ): bool {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}

		$blockId = $block->getId();

		if ( !$blockId ) {
			throw new MWException(
				__METHOD__ . " requires that a block id be set\n"
			);
		}
		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );

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
	 * @param IDatabase $dbw Database to use if not the same as the one in the load balancer.
	 *                       Must connect to the wiki identified by $block->getBlocker->getWikiId().
	 * @return array
	 */
	private function getArrayForDatabaseBlock(
		DatabaseBlock $block,
		IDatabase $dbw
	): array {
		$expiry = $dbw->encodeExpiry( $block->getExpiry() );

		if ( $block->getTargetUserIdentity() ) {
			$userId = $block->getTargetUserIdentity()->getId( $block->getWikiId() );
		} else {
			$userId = 0;
		}
		$blocker = $block->getBlocker();
		if ( !$blocker ) {
			throw new \RuntimeException( __METHOD__ . ': this block does not have a blocker' );
		}
		// DatabaseBlockStore supports inserting cross-wiki blocks by passing non-local IDatabase and blocker.
		$blockerActor = $this->actorStoreFactory
			->getActorStore( $dbw->getDomainID() )
			->acquireActorId( $blocker, $dbw );

		$blockArray = [
			'ipb_address'          => $block->getTargetName(),
			'ipb_user'             => $userId,
			'ipb_by_actor'         => $blockerActor,
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

		$combinedArray = $blockArray + $commentArray;
		return $combinedArray;
	}

	/**
	 * Get an array suitable for autoblock updates
	 *
	 * @param DatabaseBlock $block
	 * @return array
	 */
	private function getArrayForAutoblockUpdate( DatabaseBlock $block ): array {
		$blocker = $block->getBlocker();
		if ( !$blocker ) {
			throw new \RuntimeException( __METHOD__ . ': this block does not have a blocker' );
		}
		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );
		$blockerActor = $this->actorStoreFactory
			->getActorNormalization()
			->acquireActorId( $blocker, $dbw );
		$blockArray = [
			'ipb_by_actor'       => $blockerActor,
			'ipb_create_account' => $block->isCreateAccountBlocked(),
			'ipb_deleted'        => (int)$block->getHideName(), // typecast required for SQLite
			'ipb_allow_usertalk' => $block->isUsertalkEditAllowed(),
			'ipb_sitewide'       => $block->isSitewide(),
		];

		$commentArray = $this->commentStore->insert(
			$dbw,
			'ipb_reason',
			$block->getReasonComment()
		);

		$combinedArray = $blockArray + $commentArray;
		return $combinedArray;
	}

	/**
	 * Handles retroactively autoblocking the last IP used by the user (if it is a user)
	 * blocked by an auto block.
	 *
	 * @param DatabaseBlock $block
	 * @return array IDs of retroactive autoblocks made
	 */
	private function doRetroactiveAutoblock( DatabaseBlock $block ): array {
		$autoBlockIds = [];
		// If autoblock is enabled, autoblock the LAST IP(s) used
		if ( $block->isAutoblocking() && $block->getType() == AbstractBlock::TYPE_USER ) {
			$this->logger->debug(
				'Doing retroactive autoblocks for ' . $block->getTargetName()
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
	private function performRetroactiveAutoblock( DatabaseBlock $block ): array {
		if ( !$this->options->get( 'PutIPinRC' ) ) {
			// No IPs in the recent changes table to autoblock
			return [];
		}

		$type = $block->getType();
		if ( $type !== AbstractBlock::TYPE_USER ) {
			// Autoblocks only apply to users
			return [];
		}

		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );

		$targetUser = $block->getTargetUserIdentity();
		$actor = $targetUser ? $this->actorStoreFactory
			->getActorNormalization( $block->getWikiId() )
			->findActorId( $targetUser, $dbr ) : null;

		if ( !$actor ) {
			$this->logger->debug( 'No actor found to retroactively autoblock' );
			return [];
		}

		$rcIp = $dbr->selectField(
			[ 'recentchanges' ],
			'rc_ip',
			[ 'rc_actor' => $actor ],
			__METHOD__,
			[ 'ORDER BY' => 'rc_timestamp DESC' ]
		);

		if ( !$rcIp ) {
			$this->logger->debug( 'No IP found to retroactively autoblock' );
			return [];
		}

		$id = $block->doAutoblock( $rcIp );
		if ( !$id ) {
			return [];
		}
		return [ $id ];
	}

}
