<?php

namespace MediaWiki\User\TempUser;

use MediaWiki\Status\Status;
use MediaWiki\User\User;

/**
 * Status object with strongly typed value, for TempUserManager::createUser()
 *
 * @since 1.39
 * @internal
 * @extends Status<User>
 */
class CreateStatus extends Status {

	public function getUser(): User {
		return $this->value;
	}
}
