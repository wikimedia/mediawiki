<?php

namespace MediaWiki\Hook;

use Parser;
use StripState;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserAfterStripHook {
	/**
	 * This hook is called at end of parsing time.
	 * TODO: No more strip, deprecated ?
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param string &$text Text being parsed
	 * @param StripState $stripState StripState used
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserAfterStrip( $parser, &$text, $stripState );
}
