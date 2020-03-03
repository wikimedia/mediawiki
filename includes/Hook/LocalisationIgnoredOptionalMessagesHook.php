<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LocalisationIgnoredOptionalMessagesHook {
	/**
	 * When fetching the list of ignored and
	 * optional localisation messages
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$ignored Array of ignored message keys
	 * @param ?mixed &$optional Array of optional message keys
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalisationIgnoredOptionalMessages( &$ignored, &$optional );
}
