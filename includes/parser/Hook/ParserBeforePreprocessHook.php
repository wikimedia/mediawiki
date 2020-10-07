<?php

namespace MediaWiki\Hook;

use Parser;
use StripState;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserBeforePreprocessHook {
	/**
	 * Called at the beginning of Parser::preprocess()
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser Parser object
	 * @param string &$text text to parse
	 * @param StripState $stripState StripState instance being used
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserBeforePreprocess( $parser, &$text, $stripState );
}
