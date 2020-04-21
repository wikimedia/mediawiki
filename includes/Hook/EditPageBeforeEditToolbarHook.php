<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPageBeforeEditToolbarHook {
	/**
	 * Allow adding an edit toolbar above the textarea in
	 * the edit form.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$toolbar The toolbar HTML, initially an empty `<div id="toolbar"></div>`
	 *   Hook subscribers can return false to have no toolbar HTML be loaded.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageBeforeEditToolbar( &$toolbar );
}
