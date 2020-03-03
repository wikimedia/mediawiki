<?php

namespace MediaWiki\ChangeTags\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ChangeTagsListActiveHook {
	/**
	 * Allows you to nominate which of the tags your extension
	 * uses are in active use.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$tags list of all active tags. Append to this array.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeTagsListActive( &$tags );
}
