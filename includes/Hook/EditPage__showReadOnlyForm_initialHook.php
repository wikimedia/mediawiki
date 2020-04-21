<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPage__showReadOnlyForm_initialHook {
	/**
	 * similar to EditPage::showEditForm:initial
	 * but for the read-only 'view source' variant of the edit form.
	 * Return value is ignored (should always return true)
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editor EditPage instance (object)
	 * @param ?mixed $out an OutputPage instance to write to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__showReadOnlyForm_initial( $editor, $out );
}
