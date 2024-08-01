<?php

namespace MediaWiki\ResourceLoader\Hook;

use MediaWiki\ResourceLoader\Context;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ResourceLoaderForeignApiModules" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderModifyStartupSourceUrlsHook {

	/**
	 * Allow modifying source URLs (i.e. URLs to load.php, see {@link ResourceLoader::getSources()})
	 * before they get embedded in the JS generated for the startup module.
	 *
	 * The hook must not add or remove sources, and calling the new URL should have a roughly
	 * similar outcome to calling the old URL. It is mainly intended to preserve URL modifications
	 * that might affect the code generated for the modules (e.g. when load.php?modules=startup is
	 * called on the mobile site, it should generate source URLs which also use the mobile site).
	 *
	 * This hook is called from StartUpModule.
	 *
	 * @since 1.43
	 *
	 * @param string[] &$urls An array of source name => URL; the URL might be relative.
	 * @param Context $context
	 * @return void This hook must not abort, it must return no value
	 */
	public function onResourceLoaderModifyStartupSourceUrls( array &$urls, Context $context ): void;

}
