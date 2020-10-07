<?php

namespace MediaWiki\ResourceLoader\Hook;

use ResourceLoader;

/**
 * @stable to implement
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderRegisterModulesHook {
	/**
	 * This hook is called right before modules information is required,
	 * such as when responding to a resource
	 * loader request or generating HTML output.
	 *
	 * @since 1.35
	 * @param ResourceLoader $rl
	 * @return void This hook must not abort, it must return no value
	 */
	public function onResourceLoaderRegisterModules( ResourceLoader $rl ) : void;
}
