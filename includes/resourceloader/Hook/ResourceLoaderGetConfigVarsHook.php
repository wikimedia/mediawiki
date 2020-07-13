<?php

namespace MediaWiki\ResourceLoader\Hook;

use Config;

/**
 * @stable to implement
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
	 * @return void This hook must not abort, it must return no value
	 */
	public function onResourceLoaderGetConfigVars( array &$vars, $skin, Config $config ) : void;
}
