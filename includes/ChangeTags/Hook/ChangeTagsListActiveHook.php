<?php

namespace MediaWiki\ChangeTags\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ChangeTagsListActive" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ChangeTagsListActiveHook {
	/**
	 * Use this hook to nominate which of the tags your extension uses are in active use.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$tags List of all active tags. Append to this array.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeTagsListActive( &$tags );
}
