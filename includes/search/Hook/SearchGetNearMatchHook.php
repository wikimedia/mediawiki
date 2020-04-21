<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SearchGetNearMatchHook {
	/**
	 * An extra chance for exact-title-matches in "go" searches
	 * if nothing was found.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $term Search term string
	 * @param ?mixed &$title Outparam; set to $title object and return false for a match
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchGetNearMatch( $term, &$title );
}
