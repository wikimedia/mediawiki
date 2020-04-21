<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SearchResultInitFromTitleHook {
	/**
	 * Set the revision used when displaying a page in
	 * search results.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Current Title object being displayed in search results.
	 * @param ?mixed &$id Revision ID (default is false, for latest)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchResultInitFromTitle( $title, &$id );
}
