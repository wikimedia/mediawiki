<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserAfterParseHook {
	/**
	 * Called from Parser::parse() just after the call to
	 * Parser::internalParse() returns.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser parser object
	 * @param ?mixed &$text text being parsed
	 * @param ?mixed $stripState stripState used (object)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserAfterParse( $parser, &$text, $stripState );
}
