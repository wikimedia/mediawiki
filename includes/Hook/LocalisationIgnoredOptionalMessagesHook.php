<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LocalisationIgnoredOptionalMessagesHook {
	/**
	 * This hook is called when fetching the list of ignored and optional localisation messages.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$ignored Array of ignored message keys
	 * @param string[] &$optional Array of optional message keys
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalisationIgnoredOptionalMessages( &$ignored, &$optional );
}
