<?php

namespace MediaWiki\Hook;

use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditFormPreloadText" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EditFormPreloadTextHook {
	/**
	 * Use this hook to populate the edit form when creating pages.
	 *
	 * @since 1.35
	 *
	 * @param string &$text Text to preload with
	 * @param Title $title Page being created
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditFormPreloadText( &$text, $title );
}
