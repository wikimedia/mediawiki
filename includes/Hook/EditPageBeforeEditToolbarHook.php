<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPageBeforeEditToolbarHook {
	/**
	 * Use this hook to add an edit toolbar above the textarea in the edit form.
	 *
	 * @since 1.35
	 *
	 * @param string &$toolbar Toolbar HTML, initially an empty `<div id="toolbar"></div>`
	 * @return bool|void True or no return value to continue, or false to have
	 *   no toolbar HTML be loaded
	 */
	public function onEditPageBeforeEditToolbar( &$toolbar );
}
