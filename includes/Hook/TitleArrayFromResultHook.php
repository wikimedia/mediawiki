<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleArrayFromResultHook {
	/**
	 * Called when creating an TitleArray object from a
	 * database result.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$titleArray set this to an object to override the default object returned
	 * @param ?mixed $res database result used to create the object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleArrayFromResult( &$titleArray, $res );
}
