<?php

namespace MediaWiki\ResourceLoader\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ResourceLoaderSiteModulePages" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderSiteModulePagesHook {
	/**
	 * Change which wiki pages comprise the `site` module in given skin.
	 *
	 * This hook is called from ResourceLoaderSiteModule.
	 *
	 * @since 1.35
	 * @param string $skin Current skin key
	 * @param array &$pages Array of pages and their types
	 * @return void This hook must not abort, it must return no value
	 */
	public function onResourceLoaderSiteModulePages( $skin, array &$pages ): void;
}
