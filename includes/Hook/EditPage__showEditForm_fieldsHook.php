<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPage__showEditForm_fieldsHook {
	/**
	 * allows injection of form field into edit form
	 * Return value is ignored (should always return true)
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editor the EditPage instance for reference
	 * @param ?mixed $out an OutputPage instance to write to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__showEditForm_fields( $editor, $out );
}
