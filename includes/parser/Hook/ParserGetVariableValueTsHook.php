<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserGetVariableValueTsHook {
	/**
	 * Use this to change the value of the time for the
	 * {{LOCAL...}} magic word.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object
	 * @param ?mixed &$time actual time (timestamp)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserGetVariableValueTs( $parser, &$time );
}
