<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WatchlistEditorBeforeFormRenderHook {
	/**
	 * Before building the Special:EditWatchlist
	 * form, used to manipulate the list of pages or preload data based on that list.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$watchlistInfo array of watchlisted pages in
	 *   [namespaceId => ['title1' => 1, 'title2' => 1]] format
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWatchlistEditorBeforeFormRender( &$watchlistInfo );
}
