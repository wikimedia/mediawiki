<?php

namespace MediaWiki\Hook;

use stdClass;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialListusersFormatRow" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialListusersFormatRowHook {
	/**
	 * This hook is called right before the end of UsersPager::formatRow().
	 *
	 * @since 1.35
	 *
	 * @param string &$item HTML to be returned. Will be wrapped in an <li> after the hook finishes
	 * @param stdClass $row Database row object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialListusersFormatRow( &$item, $row );
}
