<?php

namespace MediaWiki\Parser\Hook;

use MediaWiki\Parser\Parser;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ParserClearState" to register handlers implementing this interface.
 *
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

/** @deprecated class alias since 1.46 */
class_alias( ParserClearStateHook::class, 'MediaWiki\\Hook\\ParserClearStateHook' );
