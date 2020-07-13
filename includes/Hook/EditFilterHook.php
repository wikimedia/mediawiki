<?php

namespace MediaWiki\Hook;

use EditPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EditFilterHook {
	/**
	 * Use this hook to perform checks on an edit.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editor Edit form (see includes/EditPage.php)
	 * @param string $text Contents of the edit box
	 * @param string $section Section being edited
	 * @param string &$error Error message to return
	 * @param string $summary Edit summary for page
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditFilter( $editor, $text, $section, &$error, $summary );
}
