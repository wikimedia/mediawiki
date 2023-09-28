<?php

namespace MediaWiki\User;

use MediaWiki\User\TempUser\TempUserConfig;

/**
 * Convenience functions for interpreting UserIdentity objects using additional
 * services or config.
 *
 * @since 1.41
 */
class UserIdentityUtils {
	private TempUserConfig $tempUserConfig;

	/**
	 * @internal
	 *
	 * @param TempUserConfig $tempUserConfig
	 */
	public function __construct( TempUserConfig $tempUserConfig ) {
		$this->tempUserConfig = $tempUserConfig;
	}

	/**
	 * Is the user a temporary user?
	 *
	 * @param UserIdentity $user
	 * @return bool
	 */
	public function isTemp( UserIdentity $user ) {
		return $this->tempUserConfig->isTempName( $user->getName() );
	}

	/**
	 * Is the user a normal non-temporary registered user?
	 *
	 * @param UserIdentity $user
	 * @return bool
	 */
	public function isNamed( UserIdentity $user ) {
		return $user->isRegistered()
			&& !$this->tempUserConfig->isTempName( $user->getName() );
	}
}
