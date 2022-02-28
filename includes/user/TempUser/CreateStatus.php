<?php

namespace MediaWiki\User\TempUser;

use Status;
use User;

/**
 * Status object with strongly typed value, for TempUserManager::createUser()
 *
 * @since 1.39
 * @internal
 */
class CreateStatus extends Status {
	/**
	 * @return User
	 */
	public function getUser(): User {
		return $this->value;
	}
}
