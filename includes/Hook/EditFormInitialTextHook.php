<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditFormInitialTextHook {
	/**
	 * Allows modifying the edit form when editing existing
	 * pages
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editPage EditPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditFormInitialText( $editPage );
}
