<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ResourceLoaderSiteStylesModulePagesHook {
	/**
	 * Use this hook to modify list of pages for a given skin.
	 *
	 * @since 1.35
	 *
	 * @param string $skin Current skin key
	 * @param array &$pages Array of pages and their types
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderSiteStylesModulePages( $skin, &$pages );
}
