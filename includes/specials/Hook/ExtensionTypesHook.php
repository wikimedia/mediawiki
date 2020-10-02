<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ExtensionTypes" to register handlers implementing this interface.
 *
 * @stable to implement
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
	 * @param string[] &$extTypes Associative array of extensions types. The
	 *   key of each element contains the symbolic type string as used in
	 *   extension.json, and the value contains the description of the type,
	 *   in the current user language, to be used as a header on
	 *   Special:Version.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onExtensionTypes( &$extTypes );
}
