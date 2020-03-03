<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ResourceLoaderSiteStylesModulePagesHook {
	/**
	 * Modify list of pages for a given skin
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $skin string of the current skin key
	 * @param ?mixed &$pages array of pages and their types.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderSiteStylesModulePages( $skin, &$pages );
}
