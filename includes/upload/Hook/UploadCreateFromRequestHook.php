<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UploadCreateFromRequest" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UploadCreateFromRequestHook {
	/**
	 * This hook is called when UploadBase::createFromRequest has been called.
	 *
	 * @since 1.35
	 *
	 * @param string $type Requested upload type
	 * @param string &$className Class name of the Upload instance to be created
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadCreateFromRequest( $type, &$className );
}
