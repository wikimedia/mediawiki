<?php

namespace MediaWiki\Hook;

use ResourceLoader;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ResourceLoaderRegisterModulesHook {
	/**
	 * This hook is called right before modules information is required,
	 * such as when responding to a resource
	 * loader request or generating HTML output.
	 *
	 * @since 1.35
	 *
	 * @param ResourceLoader $resourceLoader
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderRegisterModules( $resourceLoader );
}
