<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Permissions\GroupPermissionsLookup;
use Psr\Log\LoggerInterface;

/**
 * @since 1.45
 */
class UserRequirementsConditionCheckerFactory {

	/** @var UserRequirementsConditionChecker[] */
	private $instances = [];

	public function __construct(
		private readonly ServiceOptions $options,
		private readonly GroupPermissionsLookup $groupPermissionsLookup,
		private readonly HookContainer $hookContainer,
		private readonly LoggerInterface $logger,
		private readonly UserEditTracker $userEditTracker,
	) {
	}

	/**
	 * @param UserGroupManager $userGroupManager
	 * @param string|false $wikiId
	 * @return UserRequirementsConditionChecker
	 */
	public function getUserRequirementsConditionChecker(
		UserGroupManager $userGroupManager,
		$wikiId = UserIdentity::LOCAL
	): UserRequirementsConditionChecker {
		$key = (string)$wikiId;
		if ( !isset( $this->instances[$key] ) ) {
			$this->instances[$key] = new UserRequirementsConditionChecker(
				$this->options,
				$this->groupPermissionsLookup,
				$this->hookContainer,
				$this->logger,
				$this->userEditTracker,
				$userGroupManager,
				$wikiId,
			);
		}

		return $this->instances[$key];
	}
}
