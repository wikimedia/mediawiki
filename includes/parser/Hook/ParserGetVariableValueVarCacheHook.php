<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserGetVariableValueVarCacheHook {
	/**
	 * DEPRECATED since 1.35!  Use this to
	 * change the value of the variable cache or return false to not use it.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object
	 * @param ?mixed &$varCache variable cache (array)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserGetVariableValueVarCache( $parser, &$varCache );
}
