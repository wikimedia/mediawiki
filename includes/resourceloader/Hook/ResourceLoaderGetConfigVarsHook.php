<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ResourceLoaderGetConfigVarsHook {
	/**
	 * Called at the end of
	 * ResourceLoaderStartUpModule::getConfigSettings(). Use this to export static
	 * configuration variables to JavaScript. Things that depend on the current page
	 * or request state must be added through MakeGlobalVariablesScript instead.
	 * Skin is made available for skin specific config.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$vars [ variable name => value ]
	 * @param ?mixed $skin Skin
	 * @param ?mixed $config Config object (since 1.34)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderGetConfigVars( &$vars, $skin, $config );
}
