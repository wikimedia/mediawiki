<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use EditPage;
use WebRequest;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPage__importFormDataHook {
	/**
	 * Use this hook to read additional data posted in the form.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editpage
	 * @param WebRequest $request
	 * @return bool|void Return value is ignored; this hook should always return true
	 */
	public function onEditPage__importFormData( $editpage, $request );
}
