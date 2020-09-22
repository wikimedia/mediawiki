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
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use User;

class UserBlockCommandFactory implements BlockUserFactory, UnblockUserFactory {
	/**
	 * @var BlockPermissionCheckerFactory
	 */
	private $blockPermissionCheckerFactory;

	/** @var HookContainer */
	private $hookContainer;

	/** @var BlockRestrictionStore */
	private $blockRestrictionStore;

	/** @var ServiceOptions */
	private $options;

	/** @var DatabaseBlockStore */
	private $blockStore;

	/** @var LoggerInterface */
	private $logger;

	/**
	 * @internal Use only in ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = BlockUser::CONSTRUCTOR_OPTIONS;

	/**
	 * @param ServiceOptions $options
	 * @param HookContainer $hookContainer
	 * @param BlockPermissionCheckerFactory $blockPermissionCheckerFactory
	 * @param DatabaseBlockStore $blockStore
	 * @param BlockRestrictionStore $blockRestrictionStore
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		ServiceOptions $options,
		HookContainer $hookContainer,
		BlockPermissionCheckerFactory $blockPermissionCheckerFactory,
		DatabaseBlockStore $blockStore,
		BlockRestrictionStore $blockRestrictionStore,
		LoggerInterface $logger
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->hookContainer = $hookContainer;
		$this->blockPermissionCheckerFactory = $blockPermissionCheckerFactory;
		$this->blockStore = $blockStore;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->logger = $logger;
	}

	/**
	 * Create BlockUser
	 *
	 * @param string|UserIdentity $target Target of the block
	 * @param User $performer Performer of the block
	 * @param string $expiry Expiry of the block (timestamp or 'infinity')
	 * @param string $reason Reason of the block
	 * @param array $blockOptions Block options
	 * @param array $blockRestrictions Block restrictions
	 * @param array|null $tags Tags that should be assigned to the log entry
	 *
	 * @return BlockUser
	 */
	public function newBlockUser(
		$target,
		User $performer,
		string $expiry,
		string $reason = '',
		array $blockOptions = [],
		array $blockRestrictions = [],
		$tags = []
	) : BlockUser {
		if ( $tags === null ) {
			$tags = [];
		}

		return new BlockUser(
			$this->options,
			$this->blockRestrictionStore,
			$this->blockPermissionCheckerFactory,
			$this->hookContainer,
			$this->blockStore,
			$this->logger,
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
	 * @param User|string $target
	 * @param User $performer
	 * @param string $reason
	 * @param array $tags
	 *
	 * @return UnblockUser
	 */
	public function newUnblockUser(
		$target,
		User $performer,
		string $reason,
		array $tags = []
	) : UnblockUser {
		return new UnblockUser(
			$this->blockPermissionCheckerFactory,
			$this->blockStore,
			$this->hookContainer,
			$target,
			$performer,
			$reason,
			$tags
		);
	}
}
