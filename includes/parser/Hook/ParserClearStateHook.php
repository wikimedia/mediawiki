<?php

namespace MediaWiki\Hook;

use Parser;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserClearStateHook {
	/**
	 * This hook is called at the end of Parser::clearState().
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser Parser object being cleared
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserClearState( $parser );
}
