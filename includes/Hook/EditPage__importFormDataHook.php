<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPage__importFormDataHook {
	/**
	 * allow extensions to read additional data
	 * posted in the form
	 * Return value is ignored (should always return true)
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editpage EditPage instance
	 * @param ?mixed $request Webrequest
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__importFormData( $editpage, $request );
}
