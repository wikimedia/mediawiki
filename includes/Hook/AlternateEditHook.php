<?php

namespace MediaWiki\Hook;

use EditPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "AlternateEdit" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface AlternateEditHook {
	/**
	 * This hook is called before checking if a user can edit a page and before showing
	 * the edit form ( EditPage::edit() ). This is triggered on &action=edit.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editPage
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAlternateEdit( $editPage );
}
