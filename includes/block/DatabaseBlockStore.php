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
use DeferredUpdates;
use InvalidArgumentException;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\UserFactory;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @since 1.36
 *
 * @author DannyS712
 */
class DatabaseBlockStore {
	/** @var string|false */
	private $wikiId;

	/** @var ServiceOptions */
	private $options;

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::PutIPinRC,
		MainConfigNames::BlockDisablesLogin,
		MainConfigNames::UpdateRowsPerQuery,
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
	 * @param string|false $wikiId
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
		UserFactory $userFactory,
		$wikiId = DatabaseBlock::LOCAL
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->wikiId = $wikiId;

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
		if ( $this->readOnlyMode->isReadOnly( $this->wikiId ) ) {
			return;
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY, [], $this->wikiId );
		$store = $this->blockRestrictionStore;
		$limit = $this->options->get( MainConfigNames::UpdateRowsPerQuery );

		DeferredUpdates::addUpdate( new AutoCommitUpdate(
			$dbw,
			__METHOD__,
			static function ( IDatabase $dbw, $fname ) use ( $store, $limit ) {
				$ids = $dbw->newSelectQueryBuilder()
					->select( 'ipb_id' )
					->from( 'ipblocks' )
					->where( $dbw->buildComparison( '<', [ 'ipb_expiry' => $dbw->timestamp() ] ) )
					// Set a limit to avoid causing replication lag (T301742)
					->limit( $limit )
					->caller( $fname )->fetchFieldValues();
				if ( $ids ) {
					$ids = array_map( 'intval', $ids );
					$store->deleteByBlockId( $ids );
					$dbw->newDeleteQueryBuilder()
						->deleteFrom( 'ipblocks' )
						->where( [ 'ipb_id' => $ids ] )
						->caller( $fname )->execute();
				}
			}
		) );
	}

	/**
	 * Throw an exception if the given database connection does not match the
	 * given wiki ID.
	 *
	 * @param string|false $expectedWiki
	 * @param ?IDatabase $db
	 */
	private function checkDatabaseDomain( $expectedWiki, ?IDatabase $db = null ) {
		if ( $db ) {
			$dbDomain = $db->getDomainID();
			$storeDomain = $this->loadBalancer->resolveDomainID( $expectedWiki );
			if ( $dbDomain !== $storeDomain ) {
				throw new InvalidArgumentException(
					"DB connection domain '$dbDomain' does not match '$storeDomain'"
				);
			}
		} else {
			if ( $expectedWiki !== $this->wikiId ) {
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
	 * @param IDatabase|null $database Database to use if not the same as the one in the load
	 *   balancer. Must connect to the wiki identified by $block->getBlocker->getWikiId().
	 *   Deprecated since 1.41, should be null. Use DatabaseBlockStoreFactory to fetch a
	 *   DatabaseBlockStore with a database injected.
	 * @return bool|array False on failure, assoc array on success:
	 *      ('id' => block ID, 'autoIds' => array of autoblock IDs)
	 */
	public function insertBlock(
		DatabaseBlock $block,
		IDatabase $database = null
	) {
		$blocker = $block->getBlocker();
		if ( !$blocker || $blocker->getName() === '' ) {
			throw new InvalidArgumentException( 'Cannot insert a block without a blocker set' );
		}

		if ( $database !== null ) {
			wfDeprecatedMsg(
				'Old method signature: Passing a $database is no longer supported',
				'1.41'
			);
		}

		$this->checkDatabaseDomain( $block->getWikiId(), $database );

		$this->logger->debug( 'Inserting block; timestamp ' . $block->getTimestamp() );

		$this->purgeExpiredBlocks();

		$dbw = $database ?: $this->loadBalancer->getConnectionRef( DB_PRIMARY, [], $this->wikiId );
		$row = $this->getArrayForDatabaseBlock( $block, $dbw );

		$dbw->newInsertQueryBuilder()
			->insertInto( 'ipblocks' )
			->ignore()
			->row( $row )
			->caller( __METHOD__ )->execute();
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
			$ids = $dbw->newSelectQueryBuilder()
				->select( 'ipb_id' )
				->from( 'ipblocks' )
				->where( [ 'ipb_address' => $row['ipb_address'], 'ipb_user' => $row['ipb_user'] ] )
				->andWhere( $dbw->buildComparison( '<', [ 'ipb_expiry' => $dbw->timestamp() ] ) )
				->caller( __METHOD__ )->fetchFieldValues();
			if ( $ids ) {
				$ids = array_map( 'intval', $ids );
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'ipblocks' )
					->where( [ 'ipb_id' => $ids ] )
					->caller( __METHOD__ )->execute();
				$this->blockRestrictionStore->deleteByBlockId( $ids );
				$dbw->newInsertQueryBuilder()
					->insertInto( 'ipblocks' )
					->ignore()
					->row( $row )
					->caller( __METHOD__ )->execute();
				$affected = $dbw->affectedRows();
				if ( $affected ) {
					$block->setId( $dbw->insertId() );
					$restrictions = $block->getRawRestrictions();
					if ( $restrictions ) {
						$this->blockRestrictionStore->insert( $restrictions );
					}
				}
			}
		}

		if ( $affected ) {
			$autoBlockIds = $this->doRetroactiveAutoblock( $block );

			if ( $this->options->get( MainConfigNames::BlockDisablesLogin ) ) {
				$targetUserIdentity = $block->getTargetUserIdentity();
				if ( $targetUserIdentity ) {
					$targetUser = $this->userFactory->newFromUserIdentity( $targetUserIdentity );
					// TODO: respect the wiki the block belongs to here
					// Change user login token to force them to be logged out.
					$targetUser->setToken();
					$targetUser->saveSettings();
				}
			}

			return [ 'id' => $block->getId( $this->wikiId ), 'autoIds' => $autoBlockIds ];
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

		$this->checkDatabaseDomain( $block->getWikiId() );

		$blockId = $block->getId( $this->wikiId );
		if ( !$blockId ) {
			throw new InvalidArgumentException(
				__METHOD__ . " requires that a block id be set\n"
			);
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY, [], $this->wikiId );

		$row = $this->getArrayForDatabaseBlock( $block, $dbw );
		$dbw->startAtomic( __METHOD__ );

		$result = true;

		$dbw->newUpdateQueryBuilder()
			->update( 'ipblocks' )
			->set( $row )
			->where( [ 'ipb_id' => $blockId ] )
			->caller( __METHOD__ )->execute();

		// Only update the restrictions if they have been modified.
		$restrictions = $block->getRawRestrictions();
		if ( $restrictions !== null ) {
			// An empty array should remove all of the restrictions.
			if ( $restrictions === [] ) {
				$result = $this->blockRestrictionStore->deleteByBlockId( $blockId );
			} else {
				$result = $this->blockRestrictionStore->update( $restrictions );
			}
		}

		if ( $block->isAutoblocking() ) {
			// Update corresponding autoblock(s) (T50813)
			$dbw->newUpdateQueryBuilder()
				->update( 'ipblocks' )
				->set( $this->getArrayForAutoblockUpdate( $block ) )
				->where( [ 'ipb_parent_block_id' => $blockId ] )
				->caller( __METHOD__ )->execute();

			// Only update the restrictions if they have been modified.
			if ( $restrictions !== null ) {
				$this->blockRestrictionStore->updateByParentBlockId(
					$blockId,
					$restrictions
				);
			}
		} else {
			// Autoblock no longer required, delete corresponding autoblock(s)
			$ids = $dbw->newSelectQueryBuilder()
				->select( 'ipb_id' )
				->from( 'ipblocks' )
				->where( [ 'ipb_parent_block_id' => $blockId ] )
				->caller( __METHOD__ )->fetchFieldValues();
			if ( $ids ) {
				$ids = array_map( 'intval', $ids );
				$this->blockRestrictionStore->deleteByBlockId( $ids );
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'ipblocks' )
					->where( [ 'ipb_id' => $ids ] )
					->caller( __METHOD__ )->execute();
			}
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
	 */
	public function deleteBlock( DatabaseBlock $block ): bool {
		if ( $this->readOnlyMode->isReadOnly( $this->wikiId ) ) {
			return false;
		}

		$this->checkDatabaseDomain( $block->getWikiId() );

		$blockId = $block->getId( $this->wikiId );

		if ( !$blockId ) {
			throw new InvalidArgumentException(
				__METHOD__ . " requires that a block id be set\n"
			);
		}
		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY, [], $this->wikiId );
		$ids = $dbw->newSelectQueryBuilder()
			->select( 'ipb_id' )
			->from( 'ipblocks' )
			->where( [ 'ipb_parent_block_id' => $blockId ] )
			->caller( __METHOD__ )->fetchFieldValues();
		$ids = array_map( 'intval', $ids );
		$ids[] = $blockId;

		$this->blockRestrictionStore->deleteByBlockId( $ids );
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'ipblocks' )
			->where( [ 'ipb_id' => $ids ] )
			->caller( __METHOD__ )->execute();

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
			$userId = $block->getTargetUserIdentity()->getId( $this->wikiId );
		} else {
			$userId = 0;
		}
		$blocker = $block->getBlocker();
		if ( !$blocker ) {
			throw new \RuntimeException( __METHOD__ . ': this block does not have a blocker' );
		}
		// DatabaseBlockStore supports inserting cross-wiki blocks by passing
		// non-local IDatabase and blocker.
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
		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY, [], $this->wikiId );
		$blockerActor = $this->actorStoreFactory
			->getActorNormalization( $this->wikiId )
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
	 * Handle retroactively autoblocking the last IP used by the user (if it is a user)
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
		if ( !$this->options->get( MainConfigNames::PutIPinRC ) ) {
			// No IPs in the recent changes table to autoblock
			return [];
		}

		$type = $block->getType();
		if ( $type !== AbstractBlock::TYPE_USER ) {
			// Autoblocks only apply to users
			return [];
		}

		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA, [], $this->wikiId );

		$targetUser = $block->getTargetUserIdentity();
		$actor = $targetUser ? $this->actorStoreFactory
			->getActorNormalization( $this->wikiId )
			->findActorId( $targetUser, $dbr ) : null;

		if ( !$actor ) {
			$this->logger->debug( 'No actor found to retroactively autoblock' );
			return [];
		}

		$rcIp = $dbr->newSelectQueryBuilder()
			->select( 'rc_ip' )
			->from( 'recentchanges' )
			->where( [ 'rc_actor' => $actor ] )
			->orderBy( 'rc_timestamp', SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )->fetchField();

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
