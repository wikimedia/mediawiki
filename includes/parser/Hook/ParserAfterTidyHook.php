<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserAfterTidyHook {
	/**
	 * Called after Parser::tidy() in Parser::parse()
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object being used
	 * @param ?mixed &$text text that will be returned
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserAfterTidy( $parser, &$text );
}
