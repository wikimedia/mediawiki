<?php

/**
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

use ChangeTags;
use ManualLogEntry;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Permissions\Authority;
use MediaWiki\Status\Status;
use MediaWiki\Title\TitleValue;
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
	private BlockUtils $blockUtils;
	private UserFactory $userFactory;
	private HookRunner $hookRunner;

	/** @var UserIdentity|string */
	private $target;

	/** @var int */
	private $targetType;

	/** @var DatabaseBlock|null */
	private $block;

	/** @var Authority */
	private $performer;

	/** @var string */
	private $reason;

	/** @var array */
	private $tags = [];

	/**
	 * @param BlockPermissionCheckerFactory $blockPermissionCheckerFactory
	 * @param DatabaseBlockStore $blockStore
	 * @param BlockUtils $blockUtils
	 * @param UserFactory $userFactory
	 * @param HookContainer $hookContainer
	 * @param UserIdentity|string $target
	 * @param Authority $performer
	 * @param string $reason
	 * @param string[] $tags
	 */
	public function __construct(
		BlockPermissionCheckerFactory $blockPermissionCheckerFactory,
		DatabaseBlockStore $blockStore,
		BlockUtils $blockUtils,
		UserFactory $userFactory,
		HookContainer $hookContainer,
		$target,
		Authority $performer,
		string $reason,
		array $tags = []
	) {
		// Process dependencies
		$this->blockPermissionChecker = $blockPermissionCheckerFactory
			->newBlockPermissionChecker(
				$target,
				$performer
			);
		$this->blockStore = $blockStore;
		$this->blockUtils = $blockUtils;
		$this->userFactory = $userFactory;
		$this->hookRunner = new HookRunner( $hookContainer );

		// Process params
		[ $this->target, $this->targetType ] = $this->blockUtils->parseBlockTarget( $target );
		if (
			$this->targetType === AbstractBlock::TYPE_AUTO &&
			is_numeric( $this->target )
		) {
			// Needed, because BlockUtils::parseBlockTarget will strip the # from autoblocks.
			$this->target = '#' . $this->target;
		}
		$this->block = $this->blockStore->newFromTarget( $this->target );
		$this->performer = $performer;
		$this->reason = $reason;
		$this->tags = $tags;
	}

	/**
	 * Unblock user
	 *
	 * @return Status
	 */
	public function unblock(): Status {
		$status = Status::newGood();

		$basePermissionCheckResult = $this->blockPermissionChecker->checkBasePermissions(
			$this->block instanceof DatabaseBlock && $this->block->getHideName()
		);
		if ( $basePermissionCheckResult !== true ) {
			return $status->fatal( $basePermissionCheckResult );
		}

		$blockPermissionCheckResult = $this->blockPermissionChecker->checkBlockPermissions();
		if ( $blockPermissionCheckResult !== true ) {
			return $status->fatal( $blockPermissionCheckResult );
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

		if ( $this->block === null ) {
			return $status->fatal( 'ipb_cant_unblock', $this->target );
		}

		if (
			$this->block->getType() === AbstractBlock::TYPE_RANGE &&
			$this->targetType === AbstractBlock::TYPE_IP
		) {
			return $status->fatal( 'ipb_blocked_as_range', $this->target, $this->block->getTargetName() );
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
		// Redact IP for autoblocks
		if ( $this->block->getType() === DatabaseBlock::TYPE_AUTO ) {
			$page = TitleValue::tryNew( NS_USER, '#' . $this->block->getId() );
		} else {
			$page = TitleValue::tryNew( NS_USER, $this->block->getTargetName() );
		}

		$logEntry = new ManualLogEntry( 'block', 'unblock' );

		if ( $page !== null ) {
			$logEntry->setTarget( $page );
		}
		$logEntry->setComment( $this->reason );
		$logEntry->setPerformer( $this->performer->getUser() );
		$logEntry->addTags( $this->tags );
		$logEntry->setRelations( [ 'ipb_id' => $this->block->getId() ] );
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );
	}

}
