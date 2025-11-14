<?php

namespace MediaWiki\Watchlist\Hook;

use MediaWiki\Watchlist\WatchedItemQueryService;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "WatchedItemQueryServiceExtensions" to register handlers implementing this interface.
 *
 * @deprecated since 1.46 use ApiQueryBaseBeforeQueryHook
 * @ingroup Hooks
 */
interface WatchedItemQueryServiceExtensionsHook {
	/**
	 * Use this hook to create a WatchedItemQueryServiceExtension.
	 *
	 * @since 1.35
	 * @deprecated since 1.46 and no longer called. Use ApiQueryBaseBeforeQueryHook.
	 *
	 * @param array &$extensions Add WatchedItemQueryServiceExtension objects to this array
	 * @param WatchedItemQueryService $watchedItemQueryService Service object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWatchedItemQueryServiceExtensions( &$extensions,
		$watchedItemQueryService
	);
}

/** @deprecated class alias since 1.46 */
class_alias( WatchedItemQueryServiceExtensionsHook::class, 'MediaWiki\\Hook\\WatchedItemQueryServiceExtensionsHook' );
