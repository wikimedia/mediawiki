<?php

namespace MediaWiki\Hook;

use Parser;

/**
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface ParserGetVariableValueVarCacheHook {
	/**
	 * Use this hook to change the value of the variable
	 * cache or return false to not use it.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param array &$varCache Variable cache
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserGetVariableValueVarCache( $parser, &$varCache );
}
