<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ExtensionTypesHook {
	/**
	 * Called when generating the extensions credits, use this to
	 * change the tables headers.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$extTypes associative array of extensions types
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onExtensionTypes( &$extTypes );
}
