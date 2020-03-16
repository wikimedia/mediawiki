<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LocalisationChecksBlacklistHook {
	/**
	 * This hook is called when fetching the blacklist of localisation checks.
	 *
	 * @since 1.35
	 *
	 * @param array &$blacklist Array of checks to blacklist. See the bottom of
	 *   maintenance/language/checkLanguage.inc for the format of this variable.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalisationChecksBlacklist( &$blacklist );
}
