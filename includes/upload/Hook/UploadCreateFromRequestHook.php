<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UploadCreateFromRequestHook {
	/**
	 * When UploadBase::createFromRequest has been called.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $type (string) the requested upload type
	 * @param ?mixed &$className the class name of the Upload instance to be created
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadCreateFromRequest( $type, &$className );
}
