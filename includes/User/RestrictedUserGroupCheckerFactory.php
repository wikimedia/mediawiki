<?php
/*
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

/**
 * A service that helps to create RestrictedUserGroupChecker instances, either for the local or remote wikis.
 *
 * @since 1.46
 */
class RestrictedUserGroupCheckerFactory {

	private array $instances = [];

	public function __construct(
		private readonly RestrictedUserGroupConfigReader $configReader,
		private readonly UserRequirementsConditionChecker $userRequirementsConditionChecker,
	) {
	}

	/**
	 * Creates an instance of RestrictedUserGroupChecker for the specified wiki.
	 * @param false|string $wiki Wiki ID for which the checker should be created. `false` means the current wiki.
	 */
	public function getRestrictedUserGroupChecker( false|string $wiki = false ): RestrictedUserGroupChecker {
		$key = (string)$wiki;
		if ( !isset( $this->instances[$key] ) ) {
			$config = $this->configReader->getConfig( $wiki );
			$this->instances[$key] = new RestrictedUserGroupChecker(
				$config,
				$this->userRequirementsConditionChecker,
			);
		}
		return $this->instances[$key];
	}
}
