<?php

namespace MediaWiki\ResourceLoader\Hook;

use ResourceLoaderContext;

/**
 * @stable to implement
 * @ingroup ResourceLoaderHooks
 */
interface ResourceLoaderForeignApiModulesHook {
	/**
	 * Add dependencies to the `mediawiki.ForeignApi` module when you wish
	 * to override its behavior. See the JS docs for more information.
	 *
	 * This hook is called from ResourceLoaderForeignApiModule.
	 *
	 * @since 1.35
	 * @param string[] &$dependencies List of modules that mediawiki.ForeignApi should
	 *   depend on
	 * @param ResourceLoaderContext|null $context
	 * @return void This hook must not abort, it must return no value
	 */
	public function onResourceLoaderForeignApiModules( &$dependencies, $context ) : void;
}
