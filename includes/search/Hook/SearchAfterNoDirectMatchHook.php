<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SearchAfterNoDirectMatchHook {
	/**
	 * If there was no match for the exact result. This
	 * runs before lettercase variants are attempted, whereas 'SearchGetNearMatch'
	 * runs after.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $term Search term string
	 * @param ?mixed &$title Outparam; set to $title object and return false for a match
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchAfterNoDirectMatch( $term, &$title );
}
