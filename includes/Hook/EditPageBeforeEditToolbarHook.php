<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditPageBeforeEditToolbar" to register handlers implementing this interface.
 *
 * @ingroup Hooks
 */
interface EditPageBeforeEditToolbarHook {
	/**
	 * Use this hook to add an edit toolbar above the textarea in the edit form.
	 *
	 * @since 1.35
	 * @deprecated 1.36 Use one of the many other EditPage hooks instead
	 *
	 * @param string &$toolbar Toolbar HTML, initially an empty `<div id="toolbar"></div>`
	 * @return bool|void True or no return value to continue, or false to have
	 *   no toolbar HTML be loaded
	 */
	public function onEditPageBeforeEditToolbar( &$toolbar );
}
