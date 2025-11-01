<?php

namespace MediaWiki\ChangeTags\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ListDefinedTags" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ListDefinedTagsHook {
	/**
	 * This hook is called when trying to find all defined tags.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$tags List of tags
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onListDefinedTags( &$tags );
}
