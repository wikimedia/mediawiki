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
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use RevisionDeleteUser;
use Status;
use TitleValue;
use User;

/**
 * Backend class for unblocking users
 *
 * @since 1.36
 */
class UnblockUser {
	/** @var BlockPermissionChecker */
	private $blockPermissionChecker;

	/** @var DatabaseBlockStore */
	private $blockStore;

	/** @var BlockUtils */
	private $blockUtils;

	/** @var UserFactory */
	private $userFactory;

	/** @var HookRunner */
	private $hookRunner;

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
		list( $this->target, $this->targetType ) = $this->blockUtils->parseBlockTarget( $target );
		if (
			$this->targetType === AbstractBlock::TYPE_AUTO &&
			is_numeric( $this->target )
		) {
			// Needed, because AbstractBlock::parseTarget will strip the # from autoblocks.
			$this->target = '#' . $this->target;
		}
		$this->block = DatabaseBlock::newFromTarget( $this->target );
		$this->performer = $performer;
		$this->reason = $reason;
		$this->tags = $tags;
	}

	/**
	 * Unblock user
	 *
	 * @return Status
	 */
	public function unblock() : Status {
		$status = Status::newGood();

		$basePermissionCheckResult = $this->blockPermissionChecker->checkBasePermissions(
			$this->block instanceof DatabaseBlock && $this->block->getHideName()
		);
		if ( $basePermissionCheckResult !== true ) {
			$status->fatal( $basePermissionCheckResult );
			return $status;
		}

		$blockPermissionCheckResult = $this->blockPermissionChecker->checkBlockPermissions();
		if ( $blockPermissionCheckResult !== true ) {
			$status->fatal( $blockPermissionCheckResult );
			return $status;
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
	public function unblockUnsafe() : Status {
		$status = Status::newGood();

		if ( $this->block === null ) {
			$status->fatal( 'ipb_cant_unblock', $this->target );
			return $status;
		}

		if (
			$this->block->getType() === AbstractBlock::TYPE_RANGE &&
			$this->targetType === AbstractBlock::TYPE_IP
		) {
			$status->fatal( 'ipb_blocked_as_range', $this->target, $this->block->getTarget() );
			return $status;
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
			$status->fatal( 'ipb_cant_unblock', $this->block->getTarget() );
			return $status;
		}

		$this->hookRunner->onUnblockUserComplete( $this->block, $legacyUser );

		// Unset _deleted fields as needed
		if ( $this->block->getHideName() && $this->block->getTarget() instanceof User ) {
			// Something is deeply FUBAR if this is not a User object, but who knows?
			$id = $this->block->getTarget()->getId();
			RevisionDeleteUser::unsuppressUserName( $this->block->getTarget(), $id );
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
			$page = $this->block->getTarget() instanceof UserIdentity
				? $this->block->getTarget()->getUserPage()
				: TitleValue::tryNew( NS_USER, $this->block->getTarget() );
		}

		$logEntry = new ManualLogEntry( 'block', 'unblock' );

		if ( $page !== null ) { // Sanity
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
