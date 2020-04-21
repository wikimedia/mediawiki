<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserBeforeTidyHook {
	/**
	 * Called before tidy and custom tags replacements.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object being used
	 * @param ?mixed &$text actual text
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserBeforeTidy( $parser, &$text );
}
