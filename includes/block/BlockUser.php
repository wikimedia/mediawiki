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

use InvalidArgumentException;
use MediaWiki\Block\Restriction\AbstractRestriction;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\Authority;
use MediaWiki\Status\Status;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use RevisionDeleteUser;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;

/**
 * Handles the backend logic of blocking users
 *
 * @since 1.36
 */
class BlockUser {
	/** On conflict, do not insert the block. The value is false for b/c */
	public const CONFLICT_FAIL = false;
	/** On conflict, create a new block. */
	public const CONFLICT_NEW = 'new';
	/** On conflict, update the block if there was only one block. The value is true for b/c. */
	public const CONFLICT_REBLOCK = true;

	/**
	 * @var BlockTarget|null
	 *
	 * Target of the block. This is null in case BlockTargetFactory failed to
	 * parse the target.
	 */
	private $target;

	/** @var DatabaseBlock|null */
	private $blockToUpdate;

	/** @var Authority Performer of the block */
	private $performer;

	/** @var DatabaseBlock[]|null */
	private $priorBlocksForTarget;

	private ServiceOptions $options;
	private BlockRestrictionStore $blockRestrictionStore;
	private BlockPermissionChecker $blockPermissionChecker;
	private BlockTargetFactory $blockTargetFactory;
	private BlockActionInfo $blockActionInfo;
	private HookRunner $hookRunner;
	private DatabaseBlockStore $blockStore;
	private UserFactory $userFactory;
	private UserEditTracker $userEditTracker;
	private LoggerInterface $logger;
	private TitleFactory $titleFactory;

	/**
	 * @internal For use by UserBlockCommandFactory
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::HideUserContribLimit,
		MainConfigNames::BlockAllowsUTEdit,
		MainConfigNames::EnableMultiBlocks,
	];

	/**
	 * @var string
	 *
	 * Expiry of the to-be-placed block exactly as it was passed to the constructor.
	 */
	private $rawExpiry;

	/**
	 * @var string|bool
	 *
	 * Parsed expiry. This may be false in case of an error in parsing.
	 */
	private $expiryTime;

	/** @var string */
	private $reason;

	/** @var bool */
	private $isCreateAccountBlocked = false;

	/**
	 * @var bool|null
	 *
	 * This may be null when an invalid option was passed to the constructor.
	 * Such a case is caught in placeBlockUnsafe.
	 */
	private $isUserTalkEditBlocked = null;

	/** @var bool */
	private $isEmailBlocked = false;

	/** @var bool */
	private $isHardBlock = true;

	/** @var bool */
	private $isAutoblocking = true;

	/** @var bool */
	private $isHideUser = false;

	/**
	 * @var bool
	 *
	 * Flag that needs to be true when the to-be-created block allows all editing,
	 * but does not allow some other action.
	 *
	 * This flag is used only by isPartial(), and should not be used anywhere else,
	 * even within this class. If you want to determine whether the block will be partial,
	 * use $this->isPartial().
	 */
	private $isPartialRaw;

	/** @var AbstractRestriction[] */
	private $blockRestrictions = [];

	/** @var string[] */
	private $tags = [];

	/** @var int|null */
	private $logDeletionFlags;

	/**
	 * @param ServiceOptions $options
	 * @param BlockRestrictionStore $blockRestrictionStore
	 * @param BlockPermissionCheckerFactory $blockPermissionCheckerFactory
	 * @param BlockTargetFactory $blockTargetFactory
	 * @param BlockActionInfo $blockActionInfo
	 * @param HookContainer $hookContainer
	 * @param DatabaseBlockStore $databaseBlockStore
	 * @param UserFactory $userFactory
	 * @param UserEditTracker $userEditTracker
	 * @param LoggerInterface $logger
	 * @param TitleFactory $titleFactory
	 * @param DatabaseBlock|null $blockToUpdate
	 * @param BlockTarget|string|UserIdentity|null $target Target of the block
	 * @param Authority $performer Performer of the block
	 * @param string $expiry Expiry of the block (timestamp or 'infinity')
	 * @param string $reason Reason of the block
	 * @param bool[] $blockOptions
	 *    Valid options:
	 *    - isCreateAccountBlocked      : Are account creations prevented?
	 *    - isEmailBlocked              : Is emailing other users prevented?
	 *    - isHardBlock                 : Are named (non-temporary) users prevented from editing?
	 *    - isAutoblocking              : Should this block spread to others to
	 *                                    limit block evasion?
	 *    - isUserTalkEditBlocked       : Is editing blocked user's own talk page prevented?
	 *    - isHideUser                  : Should blocked user's name be hidden (needs hideuser)?
	 *    - isPartial                   : Is this block partial? This is ignored when
	 *                                    blockRestrictions is not an empty array.
	 * @param AbstractRestriction[] $blockRestrictions
	 * @param string[] $tags Tags that should be assigned to the log entry
	 */
	public function __construct(
		ServiceOptions $options,
		BlockRestrictionStore $blockRestrictionStore,
		BlockPermissionCheckerFactory $blockPermissionCheckerFactory,
		BlockTargetFactory $blockTargetFactory,
		BlockActionInfo $blockActionInfo,
		HookContainer $hookContainer,
		DatabaseBlockStore $databaseBlockStore,
		UserFactory $userFactory,
		UserEditTracker $userEditTracker,
		LoggerInterface $logger,
		TitleFactory $titleFactory,
		?DatabaseBlock $blockToUpdate,
		$target,
		Authority $performer,
		string $expiry,
		string $reason,
		array $blockOptions,
		array $blockRestrictions,
		array $tags
	) {
		// Process dependencies
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->blockTargetFactory = $blockTargetFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->blockStore = $databaseBlockStore;
		$this->userFactory = $userFactory;
		$this->userEditTracker = $userEditTracker;
		$this->logger = $logger;
		$this->titleFactory = $titleFactory;
		$this->blockActionInfo = $blockActionInfo;

		// Process block target
		if ( $blockToUpdate !== null ) {
			if ( $blockToUpdate->getType() === AbstractBlock::TYPE_AUTO ) {
				// Caller must check this
				throw new \InvalidArgumentException( "Can't update an autoblock" );
			}
			$this->blockToUpdate = $blockToUpdate;
			$this->target = $blockToUpdate->getTarget();
		} elseif ( $target instanceof BlockTarget ) {
			$this->target = $target;
		} elseif ( $target === null ) {
			throw new \InvalidArgumentException(
				'Either $target or $blockToUpdate must be specified' );
		} else {
			// TODO: deprecate
			$this->target = $this->blockTargetFactory->newFromLegacyUnion( $target );
		}

		$this->blockPermissionChecker = $blockPermissionCheckerFactory
			->newChecker(
				$performer
			);

		// Process other block parameters
		$this->performer = $performer;
		$this->rawExpiry = $expiry;
		$this->expiryTime = self::parseExpiryInput( $this->rawExpiry );
		$this->reason = $reason;
		$this->blockRestrictions = $blockRestrictions;
		$this->tags = $tags;

		// Process blockOptions
		foreach ( [
			'isCreateAccountBlocked',
			'isEmailBlocked',
			'isHardBlock',
			'isAutoblocking',
		] as $possibleBlockOption ) {
			if ( isset( $blockOptions[ $possibleBlockOption ] ) ) {
				$this->$possibleBlockOption = $blockOptions[ $possibleBlockOption ];
			}
		}

		$this->isPartialRaw = !empty( $blockOptions['isPartial'] ) && !$blockRestrictions;

		if (
			!$this->isPartial() ||
			in_array( NS_USER_TALK, $this->getNamespaceRestrictions() )
		) {

			// It is possible to block user talk edit. User talk edit is:
			// - always blocked if the config says so;
			// - otherwise blocked/unblocked if the option was passed in;
			// - otherwise defaults to not blocked.
			if ( !$this->options->get( MainConfigNames::BlockAllowsUTEdit ) ) {
				$this->isUserTalkEditBlocked = true;
			} else {
				$this->isUserTalkEditBlocked = $blockOptions['isUserTalkEditBlocked'] ?? false;
			}

		} else {

			// It is not possible to block user talk edit. If the option
			// was passed, an error will be thrown in ::placeBlockUnsafe.
			// Otherwise, set to not blocked.
			if ( !isset( $blockOptions['isUserTalkEditBlocked'] ) || !$blockOptions['isUserTalkEditBlocked'] ) {
				$this->isUserTalkEditBlocked = false;
			}

		}

		if ( isset( $blockOptions['isHideUser'] ) && $this->target instanceof UserBlockTarget ) {
			$this->isHideUser = $blockOptions['isHideUser'];
		}
	}

	/**
	 * @unstable This method might be removed without prior notice (see T271101)
	 * @param int $flags One of LogPage::* constants
	 */
	public function setLogDeletionFlags( int $flags ): void {
		$this->logDeletionFlags = $flags;
	}

	/**
	 * Convert a submitted expiry time, which may be relative ("2 weeks", etc) or absolute
	 * ("24 May 2034", etc), into an absolute timestamp we can put into the database.
	 *
	 * @todo strtotime() only accepts English strings. This means the expiry input
	 *       can only be specified in English.
	 * @see https://www.php.net/manual/en/function.strtotime.php
	 *
	 * @param string $expiry Whatever was typed into the form
	 *
	 * @return string|false Timestamp (format TS_MW) or 'infinity' or false on error.
	 */
	public static function parseExpiryInput( string $expiry ) {
		try {
			return ExpiryDef::normalizeExpiry( $expiry, TS_MW );
		} catch ( InvalidArgumentException ) {
			return false;
		}
	}

	/**
	 * Is the to-be-placed block partial?
	 */
	private function isPartial(): bool {
		return $this->blockRestrictions !== [] || $this->isPartialRaw;
	}

	/**
	 * Configure DatabaseBlock according to class properties
	 *
	 * @param DatabaseBlock|null $sourceBlock Copy any options from this block.
	 *   Null to construct a new one.
	 *
	 * @return DatabaseBlock
	 */
	private function configureBlock( $sourceBlock = null ): DatabaseBlock {
		if ( $sourceBlock === null ) {
			$block = new DatabaseBlock();
		} else {
			$block = clone $sourceBlock;
		}

		$isSitewide = !$this->isPartial();

		$block->setTarget( $this->target );
		$block->setBlocker( $this->performer->getUser() );
		$block->setReason( $this->reason );
		$block->setExpiry( $this->expiryTime );
		$block->isCreateAccountBlocked( $this->isCreateAccountBlocked );
		$block->isEmailBlocked( $this->isEmailBlocked );
		$block->isHardblock( $this->isHardBlock );
		$block->isAutoblocking( $this->isAutoblocking );
		$block->isSitewide( $isSitewide );
		$block->isUsertalkEditAllowed( !$this->isUserTalkEditBlocked );
		$block->setHideName( $this->isHideUser );

		$blockId = $block->getId();
		if ( $blockId === null ) {
			// Block wasn't inserted into the DB yet
			$block->setRestrictions( $this->blockRestrictions );
		} else {
			// Block is in the DB, we need to set restrictions through a service
			$block->setRestrictions(
				$this->blockRestrictionStore->setBlockId(
					$blockId,
					$this->blockRestrictions
				)
			);
		}

		return $block;
	}

	/**
	 * Get prior blocks matching the current target. If we are updating a block
	 * by ID, this will include blocks for the same target as that ID.
	 *
	 * @return DatabaseBlock[]
	 */
	private function getPriorBlocksForTarget() {
		if ( $this->priorBlocksForTarget === null ) {
			$this->priorBlocksForTarget = $this->blockStore->newListFromTarget(
				$this->target, null, true,
				// If we're blocking an IP, ignore any matching autoblocks (T287798)
				DatabaseBlockStore::AUTO_SPECIFIED
			);
		}
		return $this->priorBlocksForTarget;
	}

	/**
	 * Determine if the target user is hidden (prior to applying pending changes)
	 * @return bool
	 */
	private function wasTargetHidden() {
		if ( $this->target->getType() !== AbstractBlock::TYPE_USER ) {
			return false;
		}
		foreach ( $this->getPriorBlocksForTarget() as $block ) {
			if ( $block->getHideName() ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Place a block, checking permissions
	 *
	 * @param string|bool $conflictMode The insertion conflict mode. Ignored if
	 *   a block to update was specified in the constructor, for example by
	 *   calling UserBlockCommandFactory::newUpdateBlock(). May be one of:
	 *    - self::CONFLICT_FAIL: Allow the block only if there are no prior
	 *      blocks on the same target.
	 *    - self::CONFLICT_NEW: Create an additional block regardless of
	 *      pre-existing blocks on the same target. This is allowed only if
	 *      $wgEnableMultiBlocks is true.
	 *    - self::CONFLICT_REBLOCK: This value is deprecated. If there is one
	 *      prior block on the target, update it. If there is more than one block,
	 *      throw an exception.
	 *
	 * @return Status If the block is successful, the value of the returned
	 *   Status is an instance of a newly placed block.
	 */
	public function placeBlock( $conflictMode = self::CONFLICT_FAIL ): Status {
		$priorHideUser = $this->wasTargetHidden();
		if (
			$this->blockPermissionChecker
				->checkBasePermissions(
					$this->isHideUser || $priorHideUser
				) !== true
		) {
			$this->logger->debug( 'placeBlock: checkBasePermissions failed' );
			return Status::newFatal( $priorHideUser ? 'cant-see-hidden-user' : 'badaccess-group0' );
		}

		$blockCheckResult = $this->blockPermissionChecker->checkBlockPermissions( $this->target );
		if ( $blockCheckResult !== true ) {
			$this->logger->debug( 'placeBlock: checkBlockPermissions failed' );
			return Status::newFatal( $blockCheckResult );
		}

		if (
			$this->isEmailBlocked &&
			!$this->blockPermissionChecker->checkEmailPermissions()
		) {
			// TODO: Maybe not ignore the error here?
			$this->isEmailBlocked = false;
		}

		if ( $this->tags !== [] ) {
			$status = ChangeTags::canAddTagsAccompanyingChange(
				$this->tags,
				$this->performer
			);

			if ( !$status->isOK() ) {
				$this->logger->debug( 'placeBlock: ChangeTags::canAddTagsAccompanyingChange failed' );
				return $status;
			}
		}

		$status = Status::newGood();
		foreach ( $this->getPageRestrictions() as $pageRestriction ) {
			try {
				$title = $this->titleFactory->newFromTextThrow( $pageRestriction );
				if ( !$title->exists() ) {
					$this->logger->debug( "placeBlock: nonexistent page restriction $title" );
					$status->fatal( 'cant-block-nonexistent-page', $pageRestriction );
				}
			} catch ( MalformedTitleException $e ) {
				$this->logger->debug( 'placeBlock: malformed page restriction title' );
				$status->fatal( $e->getMessageObject() );
			}
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		return $this->placeBlockUnsafe( $conflictMode );
	}

	/**
	 * Place a block without any sort of permissions checks.
	 *
	 * @param string|bool $conflictMode
	 *
	 * @return Status If the block is successful, the value of the returned
	 *   Status is an instance of a newly placed block.
	 */
	public function placeBlockUnsafe( $conflictMode = self::CONFLICT_FAIL ): Status {
		$status = Status::wrap( $this->target->validateForCreation() );

		if ( !$status->isOK() ) {
			$this->logger->debug( 'placeBlockUnsafe: invalid target' );
			return $status;
		}

		if ( $this->isUserTalkEditBlocked === null ) {
			$this->logger->debug( 'placeBlockUnsafe: partial block on user talk page' );
			return Status::newFatal( 'ipb-prevent-user-talk-edit' );
		}

		if (
			// There should be some expiry
			$this->rawExpiry === '' ||
			// can't be a larger string as 50 (it should be a time format in any way)
			strlen( $this->rawExpiry ) > 50 ||
			// the time can't be parsed
			!$this->expiryTime
		) {
			$this->logger->debug( 'placeBlockUnsafe: invalid expiry' );
			return Status::newFatal( 'ipb_expiry_invalid' );
		}

		if ( $this->expiryTime < wfTimestampNow() ) {
			$this->logger->debug( 'placeBlockUnsafe: expiry in the past' );
			return Status::newFatal( 'ipb_expiry_old' );
		}

		$hideUserTarget = $this->getHideUserTarget();
		if ( $hideUserTarget ) {
			if ( $this->isPartial() ) {
				$this->logger->debug( 'placeBlockUnsafe: partial block cannot hide user' );
				return Status::newFatal( 'ipb_hide_partial' );
			}

			if ( !wfIsInfinity( $this->rawExpiry ) ) {
				$this->logger->debug( 'placeBlockUnsafe: temp user block has expiry' );
				return Status::newFatal( 'ipb_expiry_temp' );
			}

			$hideUserContribLimit = $this->options->get( MainConfigNames::HideUserContribLimit );
			if (
				$hideUserContribLimit !== false &&
				$this->userEditTracker->getUserEditCount( $hideUserTarget ) > $hideUserContribLimit
			) {
				$this->logger->debug( 'placeBlockUnsafe: hide user with too many contribs' );
				return Status::newFatal( 'ipb_hide_invalid', Message::numParam( $hideUserContribLimit ) );
			}
		}

		if ( $this->isPartial() ) {
			if (
				$this->blockRestrictions === [] &&
				!$this->isEmailBlocked &&
				!$this->isCreateAccountBlocked &&
				!$this->isUserTalkEditBlocked
			) {
				$this->logger->debug( 'placeBlockUnsafe: empty partial block' );
				return Status::newFatal( 'ipb-empty-block' );
			}
		}

		return $this->placeBlockInternal( $conflictMode );
	}

	/**
	 * Places a block without any sort of permission or double checking, hooks can still
	 * abort the block through, as well as already existing block.
	 *
	 * @param string|bool $conflictMode
	 *
	 * @return Status
	 */
	private function placeBlockInternal( $conflictMode ): Status {
		$block = $this->configureBlock( $this->blockToUpdate );

		$denyReason = [ 'hookaborted' ];
		$legacyUser = $this->userFactory->newFromAuthority( $this->performer );
		if ( !$this->hookRunner->onBlockIp( $block, $legacyUser, $denyReason ) ) {
			$status = Status::newGood();
			foreach ( $denyReason as $key ) {
				$this->logger->debug( "placeBlockInternal: hook aborted with message \"$key\"" );
				$status->fatal( $key );
			}
			return $status;
		}

		$expectedTargetCount = 0;
		$priorBlocks = $this->getPriorBlocksForTarget();

		if ( $this->blockToUpdate !== null ) {
			if ( $block->equals( $this->blockToUpdate ) ) {
				$this->logger->debug( 'placeBlockInternal: ' .
					'already blocked with same params (blockToUpdate case)' );
				return Status::newFatal( 'ipb_already_blocked', $block->getTargetName() );
			}
			$priorBlock = $this->blockToUpdate;
			$update = true;
		} elseif ( $conflictMode === self::CONFLICT_NEW
			&& $this->options->get( MainConfigNames::EnableMultiBlocks )
		) {
			foreach ( $priorBlocks as $priorBlock ) {
				if ( $block->equals( $priorBlock ) ) {
					// Block settings are equal => user is already blocked
					$this->logger->debug( 'placeBlockInternal: ' .
						'already blocked with same params (CONFLICT_NEW case)' );
					return Status::newFatal( 'ipb_already_blocked', $block->getTargetName() );
				}
			}
			$expectedTargetCount = null;
			$priorBlock = null;
			$update = false;
		} elseif ( !$priorBlocks ) {
			$priorBlock = null;
			$update = false;
		} else {
			// Reblock only if the caller wants so
			if ( $conflictMode !== self::CONFLICT_REBLOCK ) {
				$this->logger->debug(
					'placeBlockInternal: already blocked and reblock not requested' );
				return Status::newFatal( 'ipb_already_blocked', $block->getTargetName() );
			}

			// Can't update multiple blocks unless blockToUpdate was given
			if ( count( $priorBlocks ) > 1 ) {
				throw new MultiblocksException(
					"Can\'t reblock a user with multiple blocks already present. " .
					"Update calling code for multiblocks, providing a specific block to update." );
			}

			// Check for identical blocks
			$priorBlock = $priorBlocks[0];
			if ( $block->equals( $priorBlock ) ) {
				// Block settings are equal => user is already blocked
				$this->logger->debug( 'placeBlockInternal: already blocked, no change' );
				return Status::newFatal( 'ipb_already_blocked', $block->getTargetName() );
			}

			$update = true;
			$block = $this->configureBlock( $priorBlock );
		}

		if ( $update ) {
			$logEntry = $this->prepareLogEntry( true );
			$this->blockStore->updateBlock( $block );
		} else {
			$logEntry = $this->prepareLogEntry( false );
			// Try to insert block.
			$insertStatus = $this->blockStore->insertBlock( $block, $expectedTargetCount );
			if ( !$insertStatus ) {
				$this->logger->warning( 'Block could not be inserted. No existing block was found.' );
				return Status::newFatal( 'ipb-block-not-found', $block->getTargetName() );
			}
			if ( $insertStatus['finalTargetCount'] > 1 ) {
				$logEntry->addParameter( 'finalTargetCount', $insertStatus['finalTargetCount'] );
			}
		}
		// Relate log ID to block ID (T27763)
		$logEntry->setRelations( [ 'ipb_id' => $block->getId() ] );
		// Also save the ID to log_params, since MW 1.44
		$logEntry->addParameter( 'blockId', $block->getId() );

		// Set *_deleted fields if requested
		$hideUserTarget = $this->getHideUserTarget();
		if ( $hideUserTarget ) {
			RevisionDeleteUser::suppressUserName( $hideUserTarget->getName(), $hideUserTarget->getId() );
		}

		DeferredUpdates::addCallableUpdate( function () use ( $block, $legacyUser, $priorBlock ) {
			$this->hookRunner->onBlockIpComplete( $block, $legacyUser, $priorBlock );
		} );

		// DatabaseBlock constructor sanitizes certain block options on insert
		$this->isEmailBlocked = $block->isEmailBlocked();
		$this->isAutoblocking = $block->isAutoblocking();

		$this->log( $logEntry );

		$this->logger->debug( 'placeBlockInternal: success' );
		return Status::newGood( $block );
	}

	/**
	 * If the operation is hiding a user, get the user being hidden
	 *
	 * @return UserIdentity|null
	 */
	private function getHideUserTarget(): ?UserIdentity {
		if ( !$this->isHideUser ) {
			return null;
		}
		if ( !( $this->target instanceof UserBlockTarget ) ) {
			// Should be unreachable -- constructor checks this
			throw new \LogicException( 'Wrong target type used with hide user option' );
		}
		return $this->target->getUserIdentity();
	}

	/**
	 * Build namespace restrictions array from $this->blockRestrictions
	 *
	 * Returns an array of namespace IDs.
	 *
	 * @return int[]
	 */
	private function getNamespaceRestrictions(): array {
		$namespaceRestrictions = [];
		foreach ( $this->blockRestrictions as $restriction ) {
			if ( $restriction instanceof NamespaceRestriction ) {
				$namespaceRestrictions[] = $restriction->getValue();
			}
		}
		return $namespaceRestrictions;
	}

	/**
	 * Build an array of page restrictions from $this->blockRestrictions
	 *
	 * Returns an array of stringified full page titles.
	 *
	 * @return string[]
	 */
	private function getPageRestrictions(): array {
		$pageRestrictions = [];
		foreach ( $this->blockRestrictions as $restriction ) {
			if ( $restriction instanceof PageRestriction ) {
				$pageRestrictions[] = $restriction->getTitle()->getFullText();
			}
		}
		return $pageRestrictions;
	}

	/**
	 * Build an array of actions from $this->blockRestrictions
	 *
	 * Returns an array of stringified actions.
	 *
	 * @return string[]
	 */
	private function getActionRestrictions(): array {
		$actionRestrictions = [];
		foreach ( $this->blockRestrictions as $restriction ) {
			if ( $restriction instanceof ActionRestriction ) {
				$actionRestrictions[] = $this->blockActionInfo->getActionFromId( $restriction->getValue() );
			}
		}
		return $actionRestrictions;
	}

	/**
	 * Prepare $logParams
	 *
	 * Helper method for $this->log()
	 */
	private function constructLogParams(): array {
		$logExpiry = wfIsInfinity( $this->rawExpiry ) ? 'infinity' : $this->rawExpiry;
		$logParams = [
			'5::duration' => $logExpiry,
			'6::flags' => $this->blockLogFlags(),
			'sitewide' => !$this->isPartial()
		];

		if ( $this->isPartial() ) {
			$pageRestrictions = $this->getPageRestrictions();
			$namespaceRestrictions = $this->getNamespaceRestrictions();
			$actionRestrictions = $this->getActionRestrictions();

			if ( count( $pageRestrictions ) > 0 ) {
				$logParams['7::restrictions']['pages'] = $pageRestrictions;
			}
			if ( count( $namespaceRestrictions ) > 0 ) {
				$logParams['7::restrictions']['namespaces'] = $namespaceRestrictions;
			}
			if ( count( $actionRestrictions ) ) {
				$logParams['7::restrictions']['actions'] = $actionRestrictions;
			}
		}
		return $logParams;
	}

	/**
	 * Create the log entry object to be inserted. Do read queries here before
	 * we start locking block_target rows.
	 *
	 * @param bool $isReblock
	 * @return ManualLogEntry
	 */
	private function prepareLogEntry( bool $isReblock ) {
		$logType = $this->isHideUser ? 'suppress' : 'block';
		$logAction = $isReblock ? 'reblock' : 'block';
		$title = Title::makeTitle( NS_USER, $this->target );
		// Preload the page_id: needed for log_page in ManualLogEntry::insert()
		$title->getArticleID();

		$logEntry = new ManualLogEntry( $logType, $logAction );
		$logEntry->setTarget( $title );
		$logEntry->setComment( $this->reason );
		$logEntry->setPerformer( $this->performer->getUser() );
		$logEntry->setParameters( $this->constructLogParams() );
		$logEntry->addTags( $this->tags );
		if ( $this->logDeletionFlags !== null ) {
			$logEntry->setDeleted( $this->logDeletionFlags );
		}
		return $logEntry;
	}

	/**
	 * Log the block to Special:Log
	 */
	private function log( ManualLogEntry $logEntry ) {
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );
	}

	/**
	 * Return a comma-delimited list of flags to be passed to the log
	 * reader for this block, to provide more information in the logs.
	 */
	private function blockLogFlags(): string {
		$flags = [];

		if ( $this->target->getType() != AbstractBlock::TYPE_USER && !$this->isHardBlock ) {
			// For grepping: message block-log-flags-anononly
			$flags[] = 'anononly';
		}

		if ( $this->isCreateAccountBlocked ) {
			// For grepping: message block-log-flags-nocreate
			$flags[] = 'nocreate';
		}

		if ( $this->target->getType() == AbstractBlock::TYPE_USER && !$this->isAutoblocking ) {
			// For grepping: message block-log-flags-noautoblock
			$flags[] = 'noautoblock';
		}

		if ( $this->isEmailBlocked ) {
			// For grepping: message block-log-flags-noemail
			$flags[] = 'noemail';
		}

		if ( $this->options->get( MainConfigNames::BlockAllowsUTEdit ) && $this->isUserTalkEditBlocked ) {
			// For grepping: message block-log-flags-nousertalk
			$flags[] = 'nousertalk';
		}

		if ( $this->isHideUser ) {
			// For grepping: message block-log-flags-hiddenname
			$flags[] = 'hiddenname';
		}

		return implode( ',', $flags );
	}
}
