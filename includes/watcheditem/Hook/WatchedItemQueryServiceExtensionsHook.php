<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WatchedItemQueryServiceExtensionsHook {
	/**
	 * Create a WatchedItemQueryServiceExtension.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$extensions Add WatchedItemQueryServiceExtension objects to this array
	 * @param ?mixed $watchedItemQueryService Service object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWatchedItemQueryServiceExtensions( &$extensions,
		$watchedItemQueryService
	);
}
