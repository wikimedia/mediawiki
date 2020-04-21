<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditFilterHook {
	/**
	 * Perform checks on an edit
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editor EditPage instance (object). The edit form (see includes/EditPage.php)
	 * @param ?mixed $text Contents of the edit box
	 * @param ?mixed $section Section being edited
	 * @param ?mixed &$error Error message to return
	 * @param ?mixed $summary Edit summary for page
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditFilter( $editor, $text, $section, &$error, $summary );
}
