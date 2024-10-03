<?php

namespace MediaWiki\Hook;

use MediaWiki\Parser\Parser;
use MediaWiki\Parser\StripState;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "InternalParseBeforeLinks" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface InternalParseBeforeLinksHook {
	/**
	 * This hook is called during Parser's internalParse method before links
	 * but after nowiki/noinclude/includeonly/onlyinclude and other processing.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param string &$text Partially parsed text
	 * @param StripState $stripState Parser's internal StripState object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInternalParseBeforeLinks( $parser, &$text, $stripState );
}
