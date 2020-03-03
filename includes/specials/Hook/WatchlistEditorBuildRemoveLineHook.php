<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WatchlistEditorBuildRemoveLineHook {
	/**
	 * when building remove lines in
	 * Special:Watchlist/edit.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$tools array of extra links
	 * @param ?mixed $title Title object
	 * @param ?mixed $redirect whether the page is a redirect
	 * @param ?mixed $skin Skin object
	 * @param ?mixed &$link HTML link to title
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWatchlistEditorBuildRemoveLine( &$tools, $title, $redirect,
		$skin, &$link
	);
}
