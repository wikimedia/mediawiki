<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserBeforeInternalParseHook {
	/**
	 * Called at the beginning of Parser::internalParse().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object
	 * @param ?mixed &$text text to parse
	 * @param ?mixed $stripState StripState instance being used
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserBeforeInternalParse( $parser, &$text, $stripState );
}
