<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiValidatePasswordHook {
	/**
	 * Called from ApiValidatePassword.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module ApiValidatePassword instance.
	 * @param ?mixed &$r Result array.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiValidatePassword( $module, &$r );
}
