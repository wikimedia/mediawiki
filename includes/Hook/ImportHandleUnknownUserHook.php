<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImportHandleUnknownUserHook {
	/**
	 * When a user doesn't exist locally, this hook is
	 * called to give extensions an opportunity to auto-create it. If the auto-creation
	 * is successful, return false.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $name User name
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImportHandleUnknownUser( $name );
}
