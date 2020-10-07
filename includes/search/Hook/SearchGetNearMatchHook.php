<?php

namespace MediaWiki\Search\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SearchGetNearMatchHook {
	/**
	 * Use this hook to perform exact-title-matches in "go" searches
	 * if nothing was found.
	 *
	 * @since 1.35
	 *
	 * @param string $term Search term
	 * @param Title &$title Outparam; set to $title object and return false for a match
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchGetNearMatch( $term, &$title );
}
