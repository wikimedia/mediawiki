<?php

namespace MediaWiki\Hook;

use EditPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPageBeforeEditButtonsHook {
	/**
	 * Use this hook to modify the edit buttons below the textarea in the edit form.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editpage Current EditPage object
	 * @param array &$buttons Array of edit buttons, "Save", "Preview", "Live", and "Diff"
	 * @param string &$tabindex HTML tabindex of the last edit check/button
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageBeforeEditButtons( $editpage, &$buttons, &$tabindex );
}
