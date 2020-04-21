<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserGetLanguageObjectHook {
	/**
	 * Called when getting user's interface language object.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User object
	 * @param ?mixed &$code Language code that will be used to create the object
	 * @param ?mixed $context IContextSource object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetLanguageObject( $user, &$code, $context );
}
