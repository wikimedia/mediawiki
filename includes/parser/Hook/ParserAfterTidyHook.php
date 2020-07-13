<?php

namespace MediaWiki\Hook;

use Parser;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserAfterTidyHook {
	/**
	 * This hook is called after Parser::tidy() in Parser::parse().
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser Parser being used
	 * @param string &$text Text that will be returned
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserAfterTidy( $parser, &$text );
}
