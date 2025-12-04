<?php

namespace MediaWiki\Upload\Hook;

use MediaWiki\Upload\UploadBase;

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
	 * @param class-string<UploadBase> &$className Class name of the Upload instance to be created
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadCreateFromRequest( $type, &$className );
}

/** @deprecated class alias since 1.46 */
class_alias( UploadCreateFromRequestHook::class, 'MediaWiki\\Hook\\UploadCreateFromRequestHook' );
