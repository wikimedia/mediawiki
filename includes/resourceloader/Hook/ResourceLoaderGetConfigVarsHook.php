<?php

namespace MediaWiki\ResourceLoader\Hook;

use Config;

/**
 * @stable for implementation
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderGetConfigVarsHook {
	/**
	 * Export static site-wide `mw.config` variables to JavaScript.
	 *
	 * Variables that depend on the current page or request state must be added
	 * through MediaWiki\Hook\MakeGlobalVariablesScriptHook instead.
	 * The skin name is made available to send skin-specific config only when needed.
	 *
	 * This hook is called from ResourceLoaderStartUpModule.
	 *
	 * @since 1.35
	 * @param array &$vars `[ variable name => value ]`
	 * @param string $skin
	 * @param Config $config since 1.34
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderGetConfigVars( array &$vars, $skin, Config $config );
}
