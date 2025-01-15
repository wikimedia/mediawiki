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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Permissions\Authority;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;

class UserBlockCommandFactory implements BlockUserFactory, UnblockUserFactory {
	private BlockPermissionCheckerFactory $blockPermissionCheckerFactory;
	private BlockTargetFactory $blockTargetFactory;
	private HookContainer $hookContainer;
	private BlockRestrictionStore $blockRestrictionStore;
	private ServiceOptions $options;
	private DatabaseBlockStore $blockStore;
	private UserFactory $userFactory;
	private UserEditTracker $userEditTracker;
	private LoggerInterface $logger;
	private TitleFactory $titleFactory;
	private BlockActionInfo $blockActionInfo;

	/**
	 * @internal Use only in ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = BlockUser::CONSTRUCTOR_OPTIONS;

	public function __construct(
		ServiceOptions $options,
		HookContainer $hookContainer,
		BlockPermissionCheckerFactory $blockPermissionCheckerFactory,
		BlockTargetFactory $blockTargetFactory,
		DatabaseBlockStore $blockStore,
		BlockRestrictionStore $blockRestrictionStore,
		UserFactory $userFactory,
		UserEditTracker $userEditTracker,
		LoggerInterface $logger,
		TitleFactory $titleFactory,
		BlockActionInfo $blockActionInfo
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->hookContainer = $hookContainer;
		$this->blockPermissionCheckerFactory = $blockPermissionCheckerFactory;
		$this->blockTargetFactory = $blockTargetFactory;
		$this->blockStore = $blockStore;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->userFactory = $userFactory;
		$this->userEditTracker = $userEditTracker;
		$this->logger = $logger;
		$this->titleFactory = $titleFactory;
		$this->blockActionInfo = $blockActionInfo;
	}

	/**
	 * Create BlockUser
	 *
	 * @param BlockTarget|string|UserIdentity $target Target of the block
	 * @param Authority $performer Performer of the block
	 * @param string $expiry Expiry of the block (timestamp or 'infinity')
	 * @param string $reason Reason of the block
	 * @param array $blockOptions
	 * @param array $blockRestrictions
	 * @param array|null $tags Tags that should be assigned to the log entry
	 *
	 * @return BlockUser
	 */
	public function newBlockUser(
		$target,
		Authority $performer,
		string $expiry,
		string $reason = '',
		array $blockOptions = [],
		array $blockRestrictions = [],
		$tags = []
	): BlockUser {
		return new BlockUser(
			$this->options,
			$this->blockRestrictionStore,
			$this->blockPermissionCheckerFactory,
			$this->blockTargetFactory,
			$this->blockActionInfo,
			$this->hookContainer,
			$this->blockStore,
			$this->userFactory,
			$this->userEditTracker,
			$this->logger,
			$this->titleFactory,
			null,
			$target,
			$performer,
			$expiry,
			$reason,
			$blockOptions,
			$blockRestrictions,
			$tags ?? []
		);
	}

	/**
	 * Create a BlockUser which updates a specified block
	 *
	 * @since 1.44
	 *
	 * @param DatabaseBlock $block
	 * @param Authority $performer Performer of the block
	 * @param string $expiry New expiry of the block (timestamp or 'infinity')
	 * @param string $reason Reason of the block
	 * @param array $blockOptions
	 * @param array $blockRestrictions
	 * @param array|null $tags Tags that should be assigned to the log entry
	 * @return BlockUser
	 */
	public function newUpdateBlock(
		DatabaseBlock $block,
		Authority $performer,
		string $expiry,
		string $reason = '',
		array $blockOptions = [],
		array $blockRestrictions = [],
		$tags = []
	): BlockUser {
		return new BlockUser(
			$this->options,
			$this->blockRestrictionStore,
			$this->blockPermissionCheckerFactory,
			$this->blockTargetFactory,
			$this->blockActionInfo,
			$this->hookContainer,
			$this->blockStore,
			$this->userFactory,
			$this->userEditTracker,
			$this->logger,
			$this->titleFactory,
			$block,
			null,
			$performer,
			$expiry,
			$reason,
			$blockOptions,
			$blockRestrictions,
			$tags ?? []
		);
	}

	/**
	 * Creates UnblockUser
	 *
	 * @since 1.44
	 *
	 * @param BlockTarget|UserIdentity|string $target
	 * @param Authority $performer
	 * @param string $reason
	 * @param string[] $tags
	 *
	 * @return UnblockUser
	 */
	public function newUnblockUser(
		$target,
		Authority $performer,
		string $reason,
		array $tags = []
	): UnblockUser {
		return new UnblockUser(
			$this->blockPermissionCheckerFactory,
			$this->blockStore,
			$this->blockTargetFactory,
			$this->userFactory,
			$this->hookContainer,
			null,
			$target,
			$performer,
			$reason,
			$tags
		);
	}

	/**
	 * Creates UnblockUser to remove a specific block
	 *
	 * @param DatabaseBlock $block
	 * @param Authority $performer
	 * @param string $reason
	 * @param array $tags
	 *
	 * @return UnblockUser
	 */
	public function newRemoveBlock(
		DatabaseBlock $block,
		Authority $performer,
		string $reason,
		array $tags = []
	): UnblockUser {
		return new UnblockUser(
			$this->blockPermissionCheckerFactory,
			$this->blockStore,
			$this->blockTargetFactory,
			$this->userFactory,
			$this->hookContainer,
			$block,
			null,
			$performer,
			$reason,
			$tags
		);
	}
}
