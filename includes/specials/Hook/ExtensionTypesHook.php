<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ExtensionTypesHook {
	/**
	 * This hook is called when generating the extensions credits
	 *
	 * Use this to change the tables headers.
	 *
	 * @since 1.35
	 *
	 * @param array &$extTypes associative array of extensions types
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onExtensionTypes( &$extTypes );
}
