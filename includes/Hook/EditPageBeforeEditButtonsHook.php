<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPageBeforeEditButtonsHook {
	/**
	 * Allows modifying the edit buttons below the
	 * textarea in the edit form.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editpage The current EditPage object
	 * @param ?mixed &$buttons Array of edit buttons "Save", "Preview", "Live", and "Diff"
	 * @param ?mixed &$tabindex HTML tabindex of the last edit check/button
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageBeforeEditButtons( $editpage, &$buttons, &$tabindex );
}
