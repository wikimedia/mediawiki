<?php

namespace MediaWiki\ResourceLoader\Hook;

/**
 * @stable for implementation
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderSiteStylesModulePagesHook {
	/**
	 * Change which wiki pages comprise the `site.styles` module in given skin.
	 *
	 * This hook is called from ResourceLoaderSiteStylesModule.
	 *
	 * @since 1.35
	 * @param string $skin Current skin key
	 * @param array &$pages Array of pages and their types
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderSiteStylesModulePages( $skin, array &$pages );
}
