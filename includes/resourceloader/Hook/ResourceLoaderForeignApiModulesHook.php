<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ResourceLoaderForeignApiModulesHook {
	/**
	 * Called from ResourceLoaderForeignApiModule.
	 * Use this to add dependencies to 'mediawiki.ForeignApi' module when you wish
	 * to override its behavior. See the module docs for more information.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$dependencies string[] List of modules that 'mediawiki.ForeignApi' should
	 *   depend on
	 * @param ?mixed $context ResourceLoaderContext|null
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderForeignApiModules( &$dependencies, $context );
}
