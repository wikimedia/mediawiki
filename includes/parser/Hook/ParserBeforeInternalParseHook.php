<?php

namespace MediaWiki\Hook;

use Parser;
use StripState;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserBeforeInternalParseHook {
	/**
	 * This hook is called at the beginning of Parser::internalParse().
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param string &$text Text to parse
	 * @param StripState $stripState StripState instance being used
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserBeforeInternalParse( $parser, &$text, $stripState );
}
