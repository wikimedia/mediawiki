<?php

namespace MediaWiki\Hook;

use Config;
use Skin;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ResourceLoaderGetConfigVarsHook {
	/**
	 * This hook is called at the end of
	 * ResourceLoaderStartUpModule::getConfigSettings(). Use this hook to export static
	 * configuration variables to JavaScript. Things that depend on the current page
	 * or request state must be added through MakeGlobalVariablesScript instead.
	 * Skin is made available for skin specific config.
	 *
	 * @since 1.35
	 *
	 * @param array &$vars [ variable name => value ]
	 * @param Skin $skin
	 * @param Config $config since 1.34
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderGetConfigVars( &$vars, $skin, $config );
}
