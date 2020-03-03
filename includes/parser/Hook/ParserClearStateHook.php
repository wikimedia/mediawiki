<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserClearStateHook {
	/**
	 * Called at the end of Parser::clearState().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object being cleared
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserClearState( $parser );
}
