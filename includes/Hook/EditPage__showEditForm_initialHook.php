<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPage__showEditForm_initialHook {
	/**
	 * before showing the edit form
	 * Return false to halt editing; you'll need to handle error messages, etc.
	 * yourself. Alternatively, modifying $error and returning true will cause the
	 * contents of $error to be echoed at the top of the edit form as wikitext.
	 * Return true without altering $error to allow the edit to proceed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editor EditPage instance (object)
	 * @param ?mixed $out an OutputPage instance to write to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__showEditForm_initial( $editor, $out );
}
