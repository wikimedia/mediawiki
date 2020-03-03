<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserGetVariableValueSwitchHook {
	/**
	 * Called when the parser needs the value of a
	 * custom magic word
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object
	 * @param ?mixed &$variableCache array to cache the value; when you return
	 *   $variableCache[$magicWordId] should be the same as $ret
	 * @param ?mixed $magicWordId index (string) of the magic word (hook should not mutate it!)
	 * @param ?mixed &$ret value of the magic word (the hook should set it)
	 * @param ?mixed $frame PPFrame object to use for expanding any template variables
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserGetVariableValueSwitch( $parser, &$variableCache,
		$magicWordId, &$ret, $frame
	);
}
