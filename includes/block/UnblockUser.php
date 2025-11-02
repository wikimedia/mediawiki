<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\Authority;
use MediaWiki\Status\Status;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use RevisionDeleteUser;

/**
 * Backend class for unblocking users
 *
 * @since 1.36
 */
class UnblockUser {
	private BlockPermissionChecker $blockPermissionChecker;
	private DatabaseBlockStore $blockStore;
	private BlockTargetFactory $blockTargetFactory;
	private UserFactory $userFactory;
	private HookRunner $hookRunner;

	/** @var BlockTarget|null */
	private $target;

	private ?DatabaseBlock $block;

	private ?DatabaseBlock $blockToRemove = null;

	/** @var Authority */
	private $performer;

	/** @var string */
	private $reason;

	/** @var array */
	private $tags = [];

	/**
	 * @param BlockPermissionCheckerFactory $blockPermissionCheckerFactory
	 * @param DatabaseBlockStore $blockStore
	 * @param BlockTargetFactory $blockTargetFactory
	 * @param UserFactory $userFactory
	 * @param HookContainer $hookContainer
	 * @param DatabaseBlock|null $blockToRemove
	 * @param UserIdentity|string|null $target
	 * @param Authority $performer
	 * @param string $reason
	 * @param string[] $tags
	 */
	public function __construct(
		BlockPermissionCheckerFactory $blockPermissionCheckerFactory,
		DatabaseBlockStore $blockStore,
		BlockTargetFactory $blockTargetFactory,
		UserFactory $userFactory,
		HookContainer $hookContainer,
		?DatabaseBlock $blockToRemove,
		$target,
		Authority $performer,
		string $reason,
		array $tags = []
	) {
		// Process dependencies
		$this->blockStore = $blockStore;
		$this->blockTargetFactory = $blockTargetFactory;
		$this->userFactory = $userFactory;
		$this->hookRunner = new HookRunner( $hookContainer );

		// Process params
		if ( $blockToRemove !== null ) {
			$this->blockToRemove = $blockToRemove;
			$this->target = $blockToRemove->getTarget();
		} elseif ( $target instanceof BlockTarget ) {
			$this->target = $target;
		} else {
			// TODO: deprecate (T382106)
			$this->target = $this->blockTargetFactory->newFromLegacyUnion( $target );
		}

		$this->blockPermissionChecker = $blockPermissionCheckerFactory
			->newChecker(
				$performer
			);

		$this->performer = $performer;
		$this->reason = $reason;
		$this->tags = $tags;
	}

	/**
	 * Unblock user
	 */
	public function unblock(): Status {
		$status = Status::newGood();

		$this->block = $this->getBlockToRemove( $status );
		if ( !$status->isOK() ) {
			return $status;
		}

		$blockPermissionCheckResult = $this->blockPermissionChecker->checkBlockPermissions( $this->target );
		if ( $blockPermissionCheckResult !== true ) {
			return $status->fatal( $blockPermissionCheckResult );
		}

		$basePermissionCheckResult = $this->blockPermissionChecker->checkBasePermissions(
			$this->block instanceof DatabaseBlock && $this->block->getHideName()
		);

		if ( $basePermissionCheckResult !== true ) {
			return $status->fatal( $basePermissionCheckResult );
		}

		if ( count( $this->tags ) !== 0 ) {
			$status->merge(
				ChangeTags::canAddTagsAccompanyingChange(
					$this->tags,
					$this->performer
				)
			);
		}

		if ( !$status->isOK() ) {
			return $status;
		}

		return $this->unblockUnsafe();
	}

	/**
	 * Unblock user without any sort of permission checks
	 *
	 * @internal This is public to be called in a maintenance script.
	 * @return Status
	 */
	public function unblockUnsafe(): Status {
		$status = Status::newGood();

		$this->block ??= $this->getBlockToRemove( $status );
		if ( !$status->isOK() ) {
			return $status;
		}

		if ( $this->block === null ) {
			return $status->fatal( 'ipb_cant_unblock', $this->target->toString() );
		}

		if (
			$this->block->getType() === AbstractBlock::TYPE_RANGE &&
			$this->target->getType() === AbstractBlock::TYPE_IP
		) {
			return $status->fatal( 'ipb_blocked_as_range', $this->target->toString(), $this->block->getTargetName() );
		}

		$denyReason = [ 'hookaborted' ];
		$legacyUser = $this->userFactory->newFromAuthority( $this->performer );
		if ( !$this->hookRunner->onUnblockUser( $this->block, $legacyUser, $denyReason ) ) {
			foreach ( $denyReason as $key ) {
				$status->fatal( $key );
			}
			return $status;
		}

		$deleteBlockStatus = $this->blockStore->deleteBlock( $this->block );
		if ( !$deleteBlockStatus ) {
			return $status->fatal( 'ipb_cant_unblock', $this->block->getTargetName() );
		}

		$this->hookRunner->onUnblockUserComplete( $this->block, $legacyUser );

		// Unset _deleted fields as needed
		$user = $this->block->getTargetUserIdentity();
		if ( $this->block->getHideName() && $user ) {
			$id = $user->getId();
			RevisionDeleteUser::unsuppressUserName( $user->getName(), $id );
		}

		$this->log();

		$status->setResult( $status->isOK(), $this->block );
		return $status;
	}

	/**
	 * Log the unblock to Special:Log/block
	 */
	private function log() {
		$page = $this->block->getRedactedTarget()->getLogPage();
		$logEntry = new ManualLogEntry( 'block', 'unblock' );

		$logEntry->setTarget( $page );
		$logEntry->setComment( $this->reason );
		$logEntry->setPerformer( $this->performer->getUser() );
		$logEntry->addTags( $this->tags );
		$logEntry->setRelations( [ 'ipb_id' => $this->block->getId() ] );
		// Save the ID to log_params, since MW 1.44
		$logEntry->addParameter( 'blockId', $this->block->getId() );
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );
	}

	private function getBlockToRemove( Status $status ): ?DatabaseBlock {
		if ( $this->blockToRemove !== null ) {
			return $this->blockToRemove;
		}

		$activeBlocks = $this->blockStore->newListFromTarget(
			$this->target, null, false, DatabaseBlockStore::AUTO_SPECIFIED );
		if ( !$activeBlocks ) {
			$status->fatal( 'ipb_cant_unblock', $this->target->toString() );
			return null;
		}

		if ( count( $activeBlocks ) > 1 ) {
			$status->fatal( 'ipb_cant_unblock_multiple_blocks',
				count( $activeBlocks ), Message::listParam(
					// @phan-suppress-next-line PhanTypeMismatchArgument
					array_map(
						static function ( $block ) {
							return $block->getId();
						}, $activeBlocks )
				)
			);
		}

		return $activeBlocks[0];
	}
}
