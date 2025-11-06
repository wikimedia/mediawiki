<?php

namespace MediaWiki\Block;

use MediaWiki\User\UserIdentity;

/**
 * Interface for block targets which can be converted to a UserIdentity.
 *
 * @since 1.44
 */
interface BlockTargetWithUserIdentity {
	/**
	 * Get a UserIdentity associated with this target.
	 *
	 * @return UserIdentity
	 */
	public function getUserIdentity(): UserIdentity;
}
