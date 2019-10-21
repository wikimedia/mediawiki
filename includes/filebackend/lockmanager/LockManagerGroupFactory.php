<?php

namespace MediaWiki\FileBackend\LockManager;

use LockManagerGroup;
use Wikimedia\Rdbms\LBFactory;

/**
 * Service to construct LockManagerGroups.
 */
class LockManagerGroupFactory {
	/** @var string */
	private $defaultDomain;

	/** @var array */
	private $lockManagerConfigs;

	/** @var LBFactory */
	private $lbFactory;

	/** @var LockManagerGroup[] (domain => LockManagerGroup) */
	private $instances = [];

	/**
	 * Do not call directly, use MediaWikiServices.
	 *
	 * @param string $defaultDomain
	 * @param array $lockManagerConfigs In format of $wgLockManagers
	 * @param LBFactory $lbFactory
	 */
	public function __construct( $defaultDomain, array $lockManagerConfigs, LBFactory $lbFactory ) {
		$this->defaultDomain = $defaultDomain;
		$this->lockManagerConfigs = $lockManagerConfigs;
		$this->lbFactory = $lbFactory;
	}

	/**
	 * @param string|null|false $domain Domain (usually wiki ID). false for the default (normally
	 *   the current wiki's domain).
	 * @return LockManagerGroup
	 */
	public function getLockManagerGroup( $domain = false ) : LockManagerGroup {
		if ( $domain === false || $domain === null ) {
			$domain = $this->defaultDomain;
		}

		if ( !isset( $this->instances[$domain] ) ) {
			$this->instances[$domain] =
				new LockManagerGroup( $domain, $this->lockManagerConfigs, $this->lbFactory );
		}

		return $this->instances[$domain];
	}
}
