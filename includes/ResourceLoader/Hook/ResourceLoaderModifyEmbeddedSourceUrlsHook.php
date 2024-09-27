<?php

namespace MediaWiki\ResourceLoader\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ResourceLoaderModifyEmbeddedSourceUrls" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderModifyEmbeddedSourceUrlsHook {

	/**
	 * Allow modifying source URLs (i.e. URLs to load.php, see {@link ResourceLoader::getSources()})
	 * when they are used for output that may be embedded in the page HTML, rather than referenced
	 * using `<link>` etc., and thus the URL will be expanded relative to index.php URLs, rather than
	 * other load.php URLs.
	 *
	 * The hook must not add or remove sources, and calling the new URL should have a roughly similar
	 * outcome to calling the old URL. It is mainly intended to support serving index.php and load.php
	 * from different domains.
	 *
	 * This hook is currently called from StartUpModule and ImageModule.
	 *
	 * @since 1.43
	 *
	 * @param string[] &$urls An array of source name => URL; the URL might be relative.
	 * @return void This hook must not abort, it must return no value
	 */
	public function onResourceLoaderModifyEmbeddedSourceUrls( array &$urls ): void;

}
