<?php

namespace MediaWiki\ChangeTags\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ListDefinedTagsHook {
	/**
	 * When trying to find all defined tags.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$tags The list of tags.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onListDefinedTags( &$tags );
}
