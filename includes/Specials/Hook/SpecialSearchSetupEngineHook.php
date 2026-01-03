<?php

namespace MediaWiki\Specials\Hook;

use MediaWiki\Search\SearchEngine;
use MediaWiki\Specials\SpecialSearch;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialSearchSetupEngine" to register handlers implementing this interface.
 *
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

/** @deprecated class alias since 1.46 */
class_alias( SpecialSearchSetupEngineHook::class, 'MediaWiki\\Hook\\SpecialSearchSetupEngineHook' );
