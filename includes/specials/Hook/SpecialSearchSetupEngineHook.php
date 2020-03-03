<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialSearchSetupEngineHook {
	/**
	 * Allows passing custom data to search engine.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $search SpecialSearch special page object
	 * @param ?mixed $profile String: current search profile
	 * @param ?mixed $engine the search engine
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchSetupEngine( $search, $profile, $engine );
}
