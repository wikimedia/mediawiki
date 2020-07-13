<?php

namespace MediaWiki\Hook;

use Parser;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserFirstCallInitHook {
	/**
	 * This hook is called when the parser initialises for the first time.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser Parser object being initialised
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserFirstCallInit( $parser );
}
