<?php

namespace MediaWiki\Hook;

use MediaWiki\Parser\Parser;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ParserGetVariableValueTs" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserGetVariableValueTsHook {
	/**
	 * Use this hook to change the value of the time for time-related
	 * magic words, ie {{CURRENTMONTH}}, {{LOCALMONTH}}, etc.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param string &$time Actual time (timestamp) in TS_UNIX format
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserGetVariableValueTs( $parser, &$time );
}
