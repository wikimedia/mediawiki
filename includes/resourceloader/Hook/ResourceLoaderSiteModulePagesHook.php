<?php

namespace MediaWiki\ResourceLoader\Hook;

/**
 * @stable for implementation
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
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderSiteModulePages( $skin, array &$pages );
}
