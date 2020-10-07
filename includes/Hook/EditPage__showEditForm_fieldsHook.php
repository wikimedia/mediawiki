<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use EditPage;
use OutputPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPage__showEditForm_fieldsHook {
	/**
	 * Use this hook to inject form field into edit form.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editor EditPage instance for reference
	 * @param OutputPage $out OutputPage instance to write to
	 * @return bool|void Return value is ignored; this hook should always return true
	 */
	public function onEditPage__showEditForm_fields( $editor, $out );
}
