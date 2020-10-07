<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use EditPage;
use OutputPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPage__showEditForm_initialHook {
	/**
	 * This hook is called before showing the edit form.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editor
	 * @param OutputPage $out OutputPage instance to write to
	 * @return bool|void True or no return value without altering $error to allow the
	 *   edit to continue. Modifying $error and returning true will cause the contents
	 *   of $error to be echoed at the top of the edit form as wikitext. Return false
	 *   to halt editing; you'll need to handle error messages, etc. yourself.
	 */
	public function onEditPage__showEditForm_initial( $editor, $out );
}
