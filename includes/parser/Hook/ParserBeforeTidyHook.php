<?php

namespace MediaWiki\Hook;

use Parser;

/**
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface ParserBeforeTidyHook {
	/**
	 * This hook is called before tidy and custom tags replacements.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser Parser being used
	 * @param string &$text Actual text
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserBeforeTidy( $parser, &$text );
}
