<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialListusersFormatRowHook {
	/**
	 * Called right before the end of
	 * UsersPager::formatRow().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$item HTML to be returned. Will be wrapped in an <li> after the hook finishes
	 * @param ?mixed $row Database row object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialListusersFormatRow( &$item, $row );
}
