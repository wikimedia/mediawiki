<?php

namespace MediaWiki\Hook;

use Parser;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserGetVariableValueTsHook {
	/**
	 * Use this hook to change the value of the time for the
	 * {{LOCAL...}} magic word.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param string &$time Actual time (timestamp)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserGetVariableValueTs( $parser, &$time );
}
