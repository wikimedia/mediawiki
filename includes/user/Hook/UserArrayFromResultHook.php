<?php

namespace MediaWiki\User\Hook;

use UserArrayFromResult;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserArrayFromResultHook {
	/**
	 * This hook is called when creating an UserArray object from a database result.
	 *
	 * @since 1.35
	 *
	 * @param UserArrayFromResult|null &$userArray Set this to an object to override the default
	 * @param IResultWrapper $res database result used to create the object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserArrayFromResult( &$userArray, $res );
}
