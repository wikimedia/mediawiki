<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserArrayFromResultHook {
	/**
	 * Called when creating an UserArray object from a database
	 * result.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$userArray set this to an object to override the default object returned
	 * @param ?mixed $res database result used to create the object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserArrayFromResult( &$userArray, $res );
}
