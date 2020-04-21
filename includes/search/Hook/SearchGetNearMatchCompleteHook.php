<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SearchGetNearMatchCompleteHook {
	/**
	 * A chance to modify exact-title-matches in "go"
	 * searches.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $term Search term string
	 * @param ?mixed &$title Current Title object that is being returned (null if none found).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchGetNearMatchComplete( $term, &$title );
}
