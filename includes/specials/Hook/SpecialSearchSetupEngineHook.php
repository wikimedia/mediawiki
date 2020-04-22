<?php

namespace MediaWiki\Hook;

use SearchEngine;
use SpecialSearch;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialSearchSetupEngineHook {
	/**
	 * Use this hook for passing custom data to search engine.
	 *
	 * @since 1.35
	 *
	 * @param SpecialSearch $search SpecialSearch special page object
	 * @param string $profile String: current search profile
	 * @param SearchEngine $engine the search engine
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchSetupEngine( $search, $profile, $engine );
}
