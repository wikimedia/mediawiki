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
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use TitleFactory;

class UserBlockCommandFactory implements BlockUserFactory, UnblockUserFactory {
	/**
	 * @var BlockPermissionCheckerFactory
	 */
	private $blockPermissionCheckerFactory;

	/** @var BlockUtils */
	private $blockUtils;

	/** @var HookContainer */
	private $hookContainer;

	/** @var BlockRestrictionStore */
	private $blockRestrictionStore;

	/** @var ServiceOptions */
	private $options;

	/** @var DatabaseBlockStore */
	private $blockStore;

	/** @var UserFactory */
	private $userFactory;

	/** @var UserEditTracker */
	private $userEditTracker;

	/** @var LoggerInterface */
	private $logger;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var BlockActionInfo */
	private $blockActionInfo;

	/**
	 * @internal Use only in ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = BlockUser::CONSTRUCTOR_OPTIONS;

	/**
	 * @param ServiceOptions $options
	 * @param HookContainer $hookContainer
	 * @param BlockPermissionCheckerFactory $blockPermissionCheckerFactory
	 * @param BlockUtils $blockUtils
	 * @param DatabaseBlockStore $blockStore
	 * @param BlockRestrictionStore $blockRestrictionStore
	 * @param UserFactory $userFactory
	 * @param UserEditTracker $userEditTracker
	 * @param LoggerInterface $logger
	 * @param TitleFactory $titleFactory
	 * @param BlockActionInfo $blockActionInfo
	 */
	public function __construct(
		ServiceOptions $options,
		HookContainer $hookContainer,
		BlockPermissionCheckerFactory $blockPermissionCheckerFactory,
		BlockUtils $blockUtils,
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
		$this->blockUtils = $blockUtils;
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
	 * @param string|UserIdentity $target Target of the block
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
		if ( $tags === null ) {
			$tags = [];
		}

		return new BlockUser(
			$this->options,
			$this->blockRestrictionStore,
			$this->blockPermissionCheckerFactory,
			$this->blockUtils,
			$this->blockActionInfo,
			$this->hookContainer,
			$this->blockStore,
			$this->userFactory,
			$this->userEditTracker,
			$this->logger,
			$this->titleFactory,
			$target,
			$performer,
			$expiry,
			$reason,
			$blockOptions,
			$blockRestrictions,
			$tags
		);
	}

	/**
	 * @param UserIdentity|string $target
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
			$this->blockUtils,
			$this->userFactory,
			$this->hookContainer,
			$target,
			$performer,
			$reason,
			$tags
		);
	}
}
