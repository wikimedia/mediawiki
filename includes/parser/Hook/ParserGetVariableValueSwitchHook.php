<?php

namespace MediaWiki\Hook;

use Parser;
use PPFrame;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ParserGetVariableValueSwitch" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserGetVariableValueSwitchHook {
	/**
	 * This hook is called when the parser needs the value of a
	 * custom magic word.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param array &$variableCache Array to cache the value; when you return
	 *   $variableCache[$magicWordId] should be the same as $ret
	 * @param string $magicWordId Index of the magic word (hook should not mutate it!)
	 * @param string &$ret Value of the magic word (the hook should set it)
	 * @param PPFrame $frame PPFrame object to use for expanding any template variables
	 * @return bool|void True or no return value to continue or false to abort
	 * @note Setting $variableCache[$magicWordId] is no longer necessary since
	 *   MW 1.39, but hooks may wish to do so for backward compatibility.
	 */
	public function onParserGetVariableValueSwitch( $parser, &$variableCache,
		$magicWordId, &$ret, $frame
	);
}
