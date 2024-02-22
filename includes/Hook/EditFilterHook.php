<?php

namespace MediaWiki\Hook;

use MediaWiki\EditPage\EditPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditFilter" to register handlers implementing this interface.
 *
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
	 * @return bool|void True or no return value without altering $error to allow the
	 *    edit to continue. Modifying $error and returning true will cause the contents
	 *    of $error to be echoed at the top of the edit form as wikitext. Return false
	 *    to halt editing; you'll need to handle error messages, etc. yourself.
	 */
	public function onEditFilter( $editor, $text, $section, &$error, $summary );
}
