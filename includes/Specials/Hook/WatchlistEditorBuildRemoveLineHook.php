<?php

namespace MediaWiki\Specials\Hook;

use MediaWiki\Skin\Skin;
use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "WatchlistEditorBuildRemoveLine" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface WatchlistEditorBuildRemoveLineHook {
	/**
	 * This hook is called when building remove lines in Special:Watchlist/edit.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$tools Array of extra HTML links
	 * @param Title $title
	 * @param bool $redirect whether the page is a redirect
	 * @param Skin $skin
	 * @param string &$link HTML link to title
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWatchlistEditorBuildRemoveLine( &$tools, $title, $redirect,
		$skin, &$link
	);
}

/** @deprecated class alias since 1.46 */
class_alias( WatchlistEditorBuildRemoveLineHook::class, 'MediaWiki\\Hook\\WatchlistEditorBuildRemoveLineHook' );
