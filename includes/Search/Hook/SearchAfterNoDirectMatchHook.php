<?php

namespace MediaWiki\Search\Hook;

use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SearchAfterNoDirectMatch" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SearchAfterNoDirectMatchHook {
	/**
	 * This hook is called if there was no match for the exact result. This
	 * runs before lettercase variants are attempted, whereas 'SearchGetNearMatch'
	 * runs after.
	 *
	 * @since 1.35
	 *
	 * @param string $term Search term
	 * @param Title &$title Outparam; set to $title object and return false for a match
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchAfterNoDirectMatch( $term, &$title );
}
