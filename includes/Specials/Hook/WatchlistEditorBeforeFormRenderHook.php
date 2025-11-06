<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "WatchlistEditorBeforeFormRender" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface WatchlistEditorBeforeFormRenderHook {
	/**
	 * This hook is called before building the Special:EditWatchlist form.
	 *
	 * It is used to manipulate the list of pages or preload data based on that list.
	 *
	 * @since 1.35
	 *
	 * @param array &$watchlistInfo Array of watchlisted pages in
	 *   [namespaceId => ['title1' => 1, 'title2' => 1]] format
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWatchlistEditorBeforeFormRender( &$watchlistInfo );
}
