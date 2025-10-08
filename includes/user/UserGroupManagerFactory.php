<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\User\TempUser\TempUserConfig;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Factory service for UserGroupManager instances. This allows UserGroupManager to be created for
 * cross-wiki access.
 *
 * @since 1.35
 * @ingroup User
 */
class UserGroupManagerFactory {

	/**
	 * @var UserGroupManager[] User group manager instances indexed by wiki
	 */
	private $instances = [];

	/**
	 * @param ServiceOptions $options
	 * @param ReadOnlyMode $readOnlyMode
	 * @param ILBFactory $dbLoadBalancerFactory
	 * @param HookContainer $hookContainer
	 * @param JobQueueGroupFactory $jobQueueGroupFactory
	 * @param TempUserConfig $tempUserConfig Assumed to be the same across all domains.
	 * @param UserRequirementsConditionCheckerFactory $userRequirementsConditionCheckerFactory
	 * @param callable[] $clearCacheCallbacks
	 */
	public function __construct(
		private readonly ServiceOptions $options,
		private readonly ReadOnlyMode $readOnlyMode,
		private readonly ILBFactory $dbLoadBalancerFactory,
		private readonly HookContainer $hookContainer,
		private readonly JobQueueGroupFactory $jobQueueGroupFactory,
		private readonly TempUserConfig $tempUserConfig,
		private readonly UserRequirementsConditionCheckerFactory $userRequirementsConditionCheckerFactory,
		private readonly array $clearCacheCallbacks = [],
	) {
	}

	/**
	 * @param string|false $wikiId
	 * @return UserGroupManager
	 */
	public function getUserGroupManager( $wikiId = UserIdentity::LOCAL ): UserGroupManager {
		if ( is_string( $wikiId ) && $this->dbLoadBalancerFactory->getLocalDomainID() === $wikiId ) {
			$wikiId = UserIdentity::LOCAL;
		}
		$key = (string)$wikiId;
		if ( !isset( $this->instances[$key] ) ) {
			$this->instances[$key] = new UserGroupManager(
				$this->options,
				$this->readOnlyMode,
				$this->dbLoadBalancerFactory,
				$this->hookContainer,
				$this->jobQueueGroupFactory->makeJobQueueGroup( $wikiId ),
				$this->tempUserConfig,
				$this->userRequirementsConditionCheckerFactory,
				$this->clearCacheCallbacks,
				$wikiId
			);
		}
		return $this->instances[$key];
	}
}
