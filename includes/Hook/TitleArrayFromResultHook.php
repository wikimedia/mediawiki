<?php

namespace MediaWiki\Hook;

use TitleArray;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface TitleArrayFromResultHook {
	/**
	 * This hook is called when creating a TitleArray object from a
	 * database result.
	 *
	 * @since 1.35
	 *
	 * @param TitleArray &$titleArray Set this to an object to override the default object returned
	 * @param IResultWrapper $res Database result used to create the object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleArrayFromResult( &$titleArray, $res );
}
