<?php

namespace MediaWiki\Hook;

use EditPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EditFormInitialTextHook {
	/**
	 * Use this hook to modify the edit form when editing existing pages.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editPage
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditFormInitialText( $editPage );
}
