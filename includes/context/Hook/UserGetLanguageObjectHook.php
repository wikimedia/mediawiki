<?php

namespace MediaWiki\Hook;

use IContextSource;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserGetLanguageObjectHook {
	/**
	 * This hook is called when getting a user's interface language object.
	 *
	 * @since 1.35
	 *
	 * @param User $user
	 * @param string &$code Language code that will be used to create the object
	 * @param IContextSource $context
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetLanguageObject( $user, &$code, $context );
}
