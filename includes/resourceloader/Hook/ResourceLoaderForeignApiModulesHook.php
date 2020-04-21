<?php

namespace MediaWiki\Hook;

use ResourceLoaderContext;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ResourceLoaderForeignApiModulesHook {
	/**
	 * This hook is called from ResourceLoaderForeignApiModule.
	 * Use this hook to add dependencies to mediawiki.ForeignApi module when you wish
	 * to override its behavior. See the module docs for more information.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$dependencies List of modules that mediawiki.ForeignApi should
	 *   depend on
	 * @param ResourceLoaderContext|null $context
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderForeignApiModules( &$dependencies, $context );
}
