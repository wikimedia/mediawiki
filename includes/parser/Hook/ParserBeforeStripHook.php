<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserBeforeStripHook {
	/**
	 * Called at start of parsing time.
	 * TODO: No more strip, deprecated ?
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser parser object
	 * @param ?mixed &$text text being parsed
	 * @param ?mixed $stripState stripState used (object)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserBeforeStrip( $parser, &$text, $stripState );
}
