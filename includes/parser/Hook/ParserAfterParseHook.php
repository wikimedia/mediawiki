<?php

namespace MediaWiki\Hook;

use Parser;
use StripState;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserAfterParseHook {
	/**
	 * This hook is called from Parser::parse() just after the call to
	 * Parser::internalParse() returns.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param string &$text Text being parsed
	 * @param StripState $stripState StripState used
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserAfterParse( $parser, &$text, $stripState );
}
