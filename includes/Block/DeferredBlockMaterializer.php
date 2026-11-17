<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use LogicException;
use MediaWiki\Context\RequestContext;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logging\DatabaseLogEntry;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Page\PageReference;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\RevisionDelete\RevisionDeleteUser;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * Finds and materializes deferred blocks.
 *
 * A deferred block is not enforced, and is hidden from everyone except users with the
 * deferredblock right, until the target attempts an action that the block would
 * prevent. At that point the block materializes: it becomes a normal block, and is
 * logged in the block log (or the suppression log, if the block is hidden).
 *
 * @since 1.48
 */
class DeferredBlockMaterializer {

	/** @var DatabaseBlock[][] */
	private array $deferredBlockCache = [];

	public function __construct(
		private readonly DatabaseBlockStore $blockStore,
		private readonly IConnectionProvider $connectionProvider,
		private readonly HookContainer $hookContainer,
		private readonly UserFactory $userFactory,
		private readonly LoggerInterface $logger,
		private readonly ReadOnlyMode $readOnlyMode,
		private readonly PermissionManager $permissionManager,
		private readonly BlockManager $blockManager,
	) {
	}

	/**
	 * Get the deferred blocks that would apply to a user, if they were materialized.
	 * The blocks are loaded from the replica. Given that the block is deferred, so it doesn't
	 * have an immediate effect at the conceptual level, we can afford for a small lag between
	 * the block is placed and starts being looked for.
	 *
	 * The materializing code ensures that the block still exists against the primary DB, so
	 * there's no risk of accidentally triggering a block that was lifted.
	 *
	 * @param UserIdentity $user The user performing the action
	 * @return DatabaseBlock[]
	 */
	public function getDeferredBlocks( UserIdentity $user ): array {
		$cacheKey = $user->getId();
		if ( !isset( $this->deferredBlockCache[$cacheKey] ) ) {
			$blocks = $this->blockStore->newListFromTarget(
				$user,
				null,
				false,
				DatabaseBlockStore::AUTO_NONE,
				true
			);
			$this->deferredBlockCache[$cacheKey] = array_values( array_filter(
				$blocks,
				static fn ( DatabaseBlock $block ) => $block->isDeferred()
			) );
		}
		return $this->deferredBlockCache[$cacheKey];
	}

	/**
	 * Materialize the deferred blocks that prevent the user from performing the action.
	 *
	 * Call this only when the user attempts the action, not when the caller only checks
	 * whether the action is allowed. Nothing happens if the user is not the user of the
	 * current session, because other users cannot attempt actions in this request.
	 *
	 * @internal For use by UserAuthority
	 * @param string $action The action that the user attempts
	 * @param User $user The user that attempts the action
	 * @param LinkTarget|PageReference|null $page
	 * @return bool Whether one or more blocks materialized
	 */
	public function materializeForAction( string $action, User $user, $page ): bool {
		$sessionUser = RequestContext::getMain()->getUser();
		if ( !$sessionUser->isSafeToLoad() || $sessionUser->getName() !== $user->getName() ) {
			return false;
		}

		$materialized = false;
		foreach ( $this->getDeferredBlocks( $user ) as $block ) {
			if ( $this->permissionManager->blockPreventsAction( $block, $action, $user, $page ) &&
				$this->materialize( $block )
			) {
				$materialized = true;
			}
		}
		if ( $materialized ) {
			$this->blockManager->clearUserCache( $user );
		}
		return $materialized;
	}

	/**
	 * Materialize a deferred block: start enforcing it, log it and run the
	 * BlockIpComplete hook.
	 *
	 * The caller is responsible for clearing any block caches.
	 *
	 * @param DatabaseBlock $block
	 * @return bool Whether the block was materialized by this call. This is false
	 *   if the block was not deferred, was concurrently materialized by another
	 *   request, if the wiki is read-only, or if the target is not a registered user.
	 */
	public function materialize( DatabaseBlock $block ): bool {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}
		$blocker = $block->getBlocker();
		if ( !$blocker ) {
			throw new LogicException( 'Cannot materialize a block without a blocker' );
		}
		$blockTarget = $block->getTargetUserIdentity();
		if ( !$blockTarget || !$blockTarget->isRegistered() ) {
			return false;
		}
		// Find the log entry for the placement of the block before materializing it,
		// so that the subsequent write doesn't conflict with a read
		$placementLogEntry = $this->getPlacementLogEntry( $block );
		$materialized = $this->connectionProvider->getPrimaryDatabase()->doAtomicSection(
			__METHOD__,
			function () use ( $block, $blocker, $placementLogEntry, $blockTarget ): bool {
				if ( !$this->blockStore->materializeDeferredBlock( $block ) ) {
					return false;
				}
				$logId = $this->log( $block, $blocker, $placementLogEntry );
				$this->logMaterialization( $block, $blockTarget, $logId, $placementLogEntry );
				if ( $block->getHideName() ) {
					RevisionDeleteUser::suppressUserName( $blockTarget->getName(), $blockTarget->getId() );
				}
				return true;
			}
		);
		// We tried to materialize a block. Regardless of whether it succeeded, clear the cache.
		// On success, the cache is obviously stale now. Failures typically mean that something else changed the state
		// of the block, and the cache is also likely stale now (e.g., concurrent request materialized that block).
		unset( $this->deferredBlockCache[$blockTarget->getId()] );
		if ( !$materialized ) {
			return false;
		}
		$this->logger->info( 'Materialized deferred block {blockId}', [
			'blockId' => $block->getId(),
		] );

		$hookRunner = new HookRunner( $this->hookContainer );
		$legacyBlocker = $this->userFactory->newFromUserIdentity( $blocker );
		DeferredUpdates::addCallableUpdate( static function () use ( $hookRunner, $block, $legacyBlocker ) {
			$hookRunner->onBlockIpComplete( $block, $legacyBlocker, null );
		} );
		return true;
	}

	/**
	 * Find the most recent log entry for placing or updating the deferred block.
	 */
	private function getPlacementLogEntry( DatabaseBlock $block ): ?DatabaseLogEntry {
		$db = $this->connectionProvider->getPrimaryDatabase();
		$row = DatabaseLogEntry::newSelectQueryBuilder( $db )
			->join( 'log_search', null, 'ls_log_id=log_id' )
			->where( [
				'ls_field' => 'ipb_id',
				'ls_value' => (string)$block->getId(),
				'log_type' => [ 'deferredblock', 'suppress' ],
				'log_action' => [ 'block', 'reblock' ],
			] )
			->orderBy( 'log_id', SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )
			->fetchRow();
		return $row ? DatabaseLogEntry::newFromRow( $row ) : null;
	}

	/**
	 * Log the materialized block. This is logged as a new block by the original
	 * blocker, with the same parameters as the placement of the deferred block.
	 *
	 * @return int The ID of the log entry
	 */
	private function log(
		DatabaseBlock $block,
		UserIdentity $blocker,
		?DatabaseLogEntry $placementLogEntry
	): int {
		$expiry = $block->getExpiry();
		$params = $placementLogEntry ? $placementLogEntry->getParameters() : [
			'6::flags' => '',
			'sitewide' => $block->isSitewide(),
		];
		// The block was placed some time ago, so a relative duration like "1 week"
		// would be misleading. Use the absolute expiry time instead.
		$params['5::duration'] = wfIsInfinity( $expiry ) ? 'infinity' : wfTimestamp( TS::ISO_8601, $expiry );
		$params['6::flags'] = implode( ',', array_diff(
			explode( ',', $params['6::flags'] ?? '' ),
			[ 'deferred', '' ]
		) );
		$params['blockId'] = $block->getId();

		// The count in the placement log entry can include deferred blocks, which
		// this log entry must not reveal. Count only the enforced blocks.
		unset( $params['finalTargetCount'] );
		$finalTargetCount = count( $this->blockStore->newListFromTarget(
			$block->getTarget(), null, true, DatabaseBlockStore::AUTO_SPECIFIED
		) );
		if ( $finalTargetCount > 1 ) {
			$params['finalTargetCount'] = $finalTargetCount;
		}

		$logEntry = new ManualLogEntry( $block->getHideBlock() ? 'suppress' : 'block', 'block' );
		$logEntry->setTarget( Title::makeTitle( NS_USER, $block->getTargetName() ) );
		$logEntry->setComment( $block->getReasonComment()->text );
		// The block was placed by the blocker, not by the target triggering it
		$logEntry->setPerformer( $blocker );
		$logEntry->setParameters( $params );
		$logEntry->setRelations( [ 'ipb_id' => $block->getId() ] );
		if ( $placementLogEntry ) {
			$logEntry->setDeleted( $placementLogEntry->getDeleted() );
		}
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );
		return $logId;
	}

	/**
	 * Log the materialization in the log that has the placement of the deferred block.
	 * This log entry links to the public log entry of the block.
	 */
	private function logMaterialization(
		DatabaseBlock $block,
		UserIdentity $performer,
		int $blockLogId,
		?DatabaseLogEntry $placementLogEntry
	): void {
		$logEntry = new ManualLogEntry(
			$block->getHideBlock() ? 'suppress' : 'deferredblock',
			'materialize'
		);
		$logEntry->setTarget( Title::makeTitle( NS_USER, $block->getTargetName() ) );
		$logEntry->setPerformer( $performer );
		$logEntry->setParameters( [
			'5::logid' => $blockLogId,
			'blockId' => $block->getId(),
		] );
		$logEntry->setRelations( [ 'ipb_id' => $block->getId() ] );
		if ( $placementLogEntry ) {
			$logEntry->setDeleted( $placementLogEntry->getDeleted() );
		}
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );
	}
}
