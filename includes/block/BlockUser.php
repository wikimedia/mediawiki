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
use MediaWiki\Block\Restriction\AbstractRestriction;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Status\Status;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Message;
use Psr\Log\LoggerInterface;
use RevisionDeleteUser;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Handles the backend logic of blocking users
 *
 * @since 1.36
 */
class BlockUser {
	/**
	 * @var UserIdentity|string|null
	 *
	 * Target of the block
	 *
	 * This is null in case BlockUtils::parseBlockTarget failed to parse the target.
	 * Such case is detected in placeBlockUnsafe, by calling validateTarget from SpecialBlock.
	 */
	private $target;

	/**
	 * @var int
	 *
	 * One of AbstractBlock::TYPE_* constants
	 *
	 * This will be -1 if BlockUtils::parseBlockTarget failed to parse the target.
	 */
	private $targetType;

	/** @var Authority Performer of the block */
	private $performer;

	/** @var ServiceOptions */
	private $options;

	/** @var BlockRestrictionStore */
	private $blockRestrictionStore;

	/** @var BlockPermissionChecker */
	private $blockPermissionChecker;

	/** @var BlockUtils */
	private $blockUtils;

	/** @var HookRunner */
	private $hookRunner;

	/** @var DatabaseBlockStore */
	private $databaseBlockStore;

	/** @var UserFactory */
	private $userFactory;

	/** @var UserEditTracker */
	private $userEditTracker;

	/** @var LoggerInterface */
	private $logger;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var TempUserConfig */
	private $tempUserConfig;

	/** @var BlockActionInfo */
	private $blockActionInfo;

	/**
	 * @internal For use by UserBlockCommandFactory
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::HideUserContribLimit,
		MainConfigNames::BlockAllowsUTEdit,
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
	 * @param BlockUtils $blockUtils
	 * @param BlockActionInfo $blockActionInfo
	 * @param HookContainer $hookContainer
	 * @param DatabaseBlockStore $databaseBlockStore
	 * @param UserFactory $userFactory
	 * @param UserEditTracker $userEditTracker
	 * @param LoggerInterface $logger
	 * @param TitleFactory $titleFactory
	 * @param TempUserConfig $tempUserConfig
	 * @param string|UserIdentity $target Target of the block
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
		BlockUtils $blockUtils,
		BlockActionInfo $blockActionInfo,
		HookContainer $hookContainer,
		DatabaseBlockStore $databaseBlockStore,
		UserFactory $userFactory,
		UserEditTracker $userEditTracker,
		LoggerInterface $logger,
		TitleFactory $titleFactory,
		TempUserConfig $tempUserConfig,
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
		$this->blockPermissionChecker = $blockPermissionCheckerFactory
			->newBlockPermissionChecker(
				$target,
				$performer
			);
		$this->blockUtils = $blockUtils;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->databaseBlockStore = $databaseBlockStore;
		$this->userFactory = $userFactory;
		$this->userEditTracker = $userEditTracker;
		$this->logger = $logger;
		$this->titleFactory = $titleFactory;
		$this->tempUserConfig = $tempUserConfig;
		$this->blockActionInfo = $blockActionInfo;

		// Process block target
		[ $this->target, $rawTargetType ] = $this->blockUtils->parseBlockTarget( $target );
		if ( $rawTargetType !== null ) { // Guard against invalid targets
			$this->targetType = $rawTargetType;
		} else {
			$this->targetType = -1;
		}

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

		if (
			isset( $blockOptions['isHideUser'] ) &&
			$this->targetType === AbstractBlock::TYPE_USER &&
			!$this->tempUserConfig->isTempName( $this->target->getName() )
		) {
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
		if ( wfIsInfinity( $expiry ) ) {
			return 'infinity';
		}

		// ConvertibleTimestamp::time() used so we can fake the current time in tests
		$expiry = strtotime( $expiry, ConvertibleTimestamp::time() );

		if ( $expiry < 0 || $expiry === false ) {
			return false;
		}

		return wfTimestamp( TS_MW, $expiry );
	}

	/**
	 * Is the to-be-placed block partial?
	 *
	 * @return bool
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
	 * Place a block, checking permissions
	 *
	 * @param bool $reblock Should this reblock?
	 *
	 * @return Status If the block is successful, the value of the returned
	 *   Status is an instance of a newly placed block.
	 */
	public function placeBlock( bool $reblock = false ): Status {
		$priorBlock = DatabaseBlock::newFromTarget( $this->target, null, /*fromPrimary=*/true );
		$priorHideUser = $priorBlock instanceof DatabaseBlock && $priorBlock->getHideName();
		if (
			$this->blockPermissionChecker
				->checkBasePermissions(
					$this->isHideUser || $priorHideUser
				) !== true
		) {
			return Status::newFatal( $priorHideUser ? 'cant-see-hidden-user' : 'badaccess-group0' );
		}

		$blockCheckResult = $this->blockPermissionChecker->checkBlockPermissions();
		if ( $blockCheckResult !== true ) {
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
				return $status;
			}
		}

		$status = Status::newGood();
		foreach ( $this->getPageRestrictions() as $pageRestriction ) {
			try {
				$title = $this->titleFactory->newFromTextThrow( $pageRestriction );
				if ( !$title->exists() ) {
					$status->fatal( 'cant-block-nonexistent-page', $pageRestriction );
				}
			} catch ( MalformedTitleException $e ) {
				$status->fatal( $e->getMessageObject() );
			}
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		return $this->placeBlockUnsafe( $reblock );
	}

	/**
	 * Place a block without any sort of permissions checks.
	 *
	 * @param bool $reblock Should this reblock?
	 *
	 * @return Status If the block is successful, the value of the returned
	 *   Status is an instance of a newly placed block.
	 */
	public function placeBlockUnsafe( bool $reblock = false ): Status {
		$status = $this->blockUtils->validateTarget( $this->target );

		if ( !$status->isOK() ) {
			return $status;
		}

		if ( $this->isUserTalkEditBlocked === null ) {
			return Status::newFatal( 'ipb-prevent-user-talk-edit' );
		}

		if (
			// There should be some expiry
			strlen( $this->rawExpiry ) === 0 ||
			// can't be a larger string as 50 (it should be a time format in any way)
			strlen( $this->rawExpiry ) > 50 ||
			// the time can't be parsed
			!$this->expiryTime
		) {
			return Status::newFatal( 'ipb_expiry_invalid' );
		}

		if ( $this->expiryTime < wfTimestampNow() ) {
			return Status::newFatal( 'ipb_expiry_old' );
		}

		if ( $this->isHideUser ) {
			if ( $this->isPartial() ) {
				return Status::newFatal( 'ipb_hide_partial' );
			}

			if ( !wfIsInfinity( $this->rawExpiry ) ) {
				return Status::newFatal( 'ipb_expiry_temp' );
			}

			$hideUserContribLimit = $this->options->get( MainConfigNames::HideUserContribLimit );
			if (
				$hideUserContribLimit !== false &&
				$this->userEditTracker->getUserEditCount( $this->target ) > $hideUserContribLimit
			) {
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
				return Status::newFatal( 'ipb-empty-block' );
			}
		}

		return $this->placeBlockInternal( $reblock );
	}

	/**
	 * Places a block without any sort of permission or double checking, hooks can still
	 * abort the block through, as well as already existing block.
	 *
	 * @param bool $reblock Should this reblock?
	 *
	 * @return Status
	 */
	private function placeBlockInternal( bool $reblock = true ): Status {
		$block = $this->configureBlock();

		$denyReason = [ 'hookaborted' ];
		$legacyUser = $this->userFactory->newFromAuthority( $this->performer );
		if ( !$this->hookRunner->onBlockIp( $block, $legacyUser, $denyReason ) ) {
			$status = Status::newGood();
			foreach ( $denyReason as $key ) {
				$status->fatal( $key );
			}
			return $status;
		}

		// Is there a conflicting block?
		// xxx: there is an identical call at the beginning of ::placeBlock
		$priorBlock = DatabaseBlock::newFromTarget( $this->target, null, /*fromPrimary=*/true );

		// T287798: we are blocking an IP that is currently autoblocked
		// we can ignore the block because ipb_address_unique allows the IP address
		// be both manually blocked and autoblocked
		// this will work as long as DatabaseBlock::newLoad prefers manual IP blocks
		// over autoblocks
		if ( $priorBlock !== null
			&& $priorBlock->getType() === AbstractBlock::TYPE_AUTO
			&& $this->targetType === AbstractBlock::TYPE_IP
		) {
			$priorBlock = null;
		}

		$isReblock = false;
		if ( $priorBlock !== null ) {
			// Reblock only if the caller wants so
			if ( !$reblock ) {
				return Status::newFatal( 'ipb_already_blocked', $block->getTargetName() );
			}

			if ( $block->equals( $priorBlock ) ) {
				// Block settings are equal => user is already blocked
				return Status::newFatal( 'ipb_already_blocked', $block->getTargetName() );
			}

			$currentBlock = $this->configureBlock( $priorBlock );
			$this->databaseBlockStore->updateBlock( $currentBlock ); // TODO handle failure
			$isReblock = true;
			$block = $currentBlock;
		} else {
			// Try to insert block.
			$insertStatus = $this->databaseBlockStore->insertBlock( $block );
			if ( !$insertStatus ) {
				$this->logger->warning( 'Block could not be inserted. No existing block was found.' );
				return Status::newFatal( 'ipb-block-not-found', $block->getTargetName() );
			}
		}

		// Set *_deleted fields if requested
		if ( $this->isHideUser ) {
			// This should only be the case of $this->target is a user, so we can
			// safely call ->getId()
			RevisionDeleteUser::suppressUserName( $this->target->getName(), $this->target->getId() );
		}

		$this->hookRunner->onBlockIpComplete( $block, $legacyUser, $priorBlock );

		// DatabaseBlock constructor sanitizes certain block options on insert
		$this->isEmailBlocked = $block->isEmailBlocked();
		$this->isAutoblocking = $block->isAutoblocking();

		$this->log( $block, $isReblock );

		return Status::newGood( $block );
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
	 *
	 * @return array
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
	 * Log the block to Special:Log
	 *
	 * @param DatabaseBlock $block
	 * @param bool $isReblock
	 */
	private function log( DatabaseBlock $block, bool $isReblock ) {
		$logType = $this->isHideUser ? 'suppress' : 'block';
		$logAction = $isReblock ? 'reblock' : 'block';

		$logEntry = new ManualLogEntry( $logType, $logAction );
		$logEntry->setTarget( Title::makeTitle( NS_USER, $this->target ) );
		$logEntry->setComment( $this->reason );
		$logEntry->setPerformer( $this->performer->getUser() );
		$logEntry->setParameters( $this->constructLogParams() );
		// Relate log ID to block ID (T27763)
		$logEntry->setRelations( [ 'ipb_id' => $block->getId() ] );
		$logEntry->addTags( $this->tags );
		if ( $this->logDeletionFlags !== null ) {
			$logEntry->setDeleted( $this->logDeletionFlags );
		}
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );
	}

	/**
	 * Return a comma-delimited list of flags to be passed to the log
	 * reader for this block, to provide more information in the logs.
	 *
	 * @return string
	 */
	private function blockLogFlags(): string {
		$flags = [];

		if ( $this->targetType != AbstractBlock::TYPE_USER && !$this->isHardBlock ) {
			// For grepping: message block-log-flags-anononly
			$flags[] = 'anononly';
		}

		if ( $this->isCreateAccountBlocked ) {
			// For grepping: message block-log-flags-nocreate
			$flags[] = 'nocreate';
		}

		if ( $this->targetType == AbstractBlock::TYPE_USER && !$this->isAutoblocking ) {
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
