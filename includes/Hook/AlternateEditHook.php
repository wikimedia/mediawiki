<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AlternateEditHook {
	/**
	 * Before checking if a user can edit a page and before showing
	 * the edit form ( EditPage::edit() ). This is triggered on &action=edit.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editPage the EditPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAlternateEdit( $editPage );
}
