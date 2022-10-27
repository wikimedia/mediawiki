<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use EditPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditPage::importFormData" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPage__importFormDataHook {
	/**
	 * Use this hook to read additional data posted in the form.
	 *
	 * @param EditPage $editpage
	 * @param \MediaWiki\Request\WebRequest $request
	 * @return bool|void Return value is ignored; this hook should always return true
	 * @since 1.35
	 *
	 */
	public function onEditPage__importFormData( $editpage, $request );
}
