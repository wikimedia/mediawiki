<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ResourceLoaderRegisterModulesHook {
	/**
	 * Right before modules information is required,
	 * such as when responding to a resource
	 * loader request or generating HTML output.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $resourceLoader ResourceLoader object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResourceLoaderRegisterModules( $resourceLoader );
}
