<?php

namespace MediaWiki\Api\Hook;

use MediaWiki\Api\ApiValidatePassword;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ApiValidatePassword" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiValidatePasswordHook {
	/**
	 * This hook is called from ApiValidatePassword.
	 *
	 * @since 1.35
	 *
	 * @param ApiValidatePassword $module
	 * @param array &$r Result array
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiValidatePassword( $module, &$r );
}
