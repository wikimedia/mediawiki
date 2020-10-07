<?php

namespace MediaWiki\Hook;

use SearchEngine;
use SpecialSearch;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialSearchSetupEngineHook {
	/**
	 * Use this hook for passing custom data to the search engine.
	 *
	 * @since 1.35
	 *
	 * @param SpecialSearch $search
	 * @param string $profile Current search profile
	 * @param SearchEngine $engine
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchSetupEngine( $search, $profile, $engine );
}
