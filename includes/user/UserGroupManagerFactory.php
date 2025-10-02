<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use Psr\Log\LoggerInterface;
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
	private ServiceOptions $options;
	private ReadOnlyMode $readOnlyMode;
	private ILBFactory $dbLoadBalancerFactory;
	private UserEditTracker $userEditTracker;
	private GroupPermissionsLookup $groupPermissionLookup;
	private JobQueueGroupFactory $jobQueueGroupFactory;
	private LoggerInterface $logger;

	/** @var callable[] */
	private $clearCacheCallbacks;

	private HookContainer $hookContainer;
	private TempUserConfig $tempUserConfig;

	/**
	 * @var UserGroupManager[] User group manager instances indexed by wiki
	 */
	private $instances = [];

	/**
	 * @param ServiceOptions $options
	 * @param ReadOnlyMode $readOnlyMode
	 * @param ILBFactory $dbLoadBalancerFactory
	 * @param HookContainer $hookContainer
	 * @param UserEditTracker $userEditTracker
	 * @param GroupPermissionsLookup $groupPermissionsLookup
	 * @param JobQueueGroupFactory $jobQueueGroupFactory
	 * @param LoggerInterface $logger
	 * @param TempUserConfig $tempUserConfig Assumed to be the same across all domains.
	 * @param callable[] $clearCacheCallbacks
	 */
	public function __construct(
		ServiceOptions $options,
		ReadOnlyMode $readOnlyMode,
		ILBFactory $dbLoadBalancerFactory,
		HookContainer $hookContainer,
		UserEditTracker $userEditTracker,
		GroupPermissionsLookup $groupPermissionsLookup,
		JobQueueGroupFactory $jobQueueGroupFactory,
		LoggerInterface $logger,
		TempUserConfig $tempUserConfig,
		array $clearCacheCallbacks = []
	) {
		$this->options = $options;
		$this->readOnlyMode = $readOnlyMode;
		$this->dbLoadBalancerFactory = $dbLoadBalancerFactory;
		$this->hookContainer = $hookContainer;
		$this->userEditTracker = $userEditTracker;
		$this->groupPermissionLookup = $groupPermissionsLookup;
		$this->jobQueueGroupFactory = $jobQueueGroupFactory;
		$this->logger = $logger;
		$this->tempUserConfig = $tempUserConfig;
		$this->clearCacheCallbacks = $clearCacheCallbacks;
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
				$this->userEditTracker,
				$this->groupPermissionLookup,
				$this->jobQueueGroupFactory->makeJobQueueGroup( $wikiId ),
				$this->logger,
				$this->tempUserConfig,
				$this->clearCacheCallbacks,
				$wikiId
			);
		}
		return $this->instances[$key];
	}
}
