<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditFormPreloadTextHook {
	/**
	 * Allows population of the edit form when creating
	 * new pages
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$text Text to preload with
	 * @param ?mixed $title Title object representing the page being created
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditFormPreloadText( &$text, $title );
}
