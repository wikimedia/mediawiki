<?php

namespace MediaWiki\Hook;

use TitleArray;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "TitleArrayFromResult" to register handlers implementing this interface.
 *
 * @ingroup Hooks
 */
interface TitleArrayFromResultHook {
	/**
	 * This hook is called when creating a TitleArray object from a
	 * database result.
	 *
	 * @since 1.35
	 * @deprecated since 1.36
	 *
	 * @param TitleArray &$titleArray Set this to an object to override the default object returned
	 * @param IResultWrapper $res Database result used to create the object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleArrayFromResult( &$titleArray, $res );
}
