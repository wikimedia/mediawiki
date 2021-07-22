<?php

namespace MediaWiki\ResourceLoader\Hook;

use ResourceLoader;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ResourceLoaderRegisterModules" to register handlers implementing this interface.
 *
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
	public function onResourceLoaderRegisterModules( ResourceLoader $rl ): void;
}
