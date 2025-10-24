<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\User\Registration\UserRegistrationLookup;
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
		private readonly UserRegistrationLookup $userRegistrationLookup,
		private readonly UserFactory $userFactory,
		private readonly IContextSource $context,
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
				$this->userRegistrationLookup,
				$this->userFactory,
				$this->context,
				$userGroupManager,
				$wikiId,
			);
		}

		return $this->instances[$key];
	}
}
