<?php

namespace MediaWiki\Parser\Hook;

use MediaWiki\Parser\Parser;
use MediaWiki\Parser\PPFrame;

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
	 * @param array &$variableCache Deprecated since 1.35 and unused since 1.39
	 * @param string $magicWordId Normalized, canonical id for the magic word.
	 *   Each extension should check if it is responsible for the given magic
	 *   word (registered via GetMagicVariableIDs).
	 * @param string &$ret Value of the magic word. For magic words which
	 *   an extension has registered with the GetMagicVariableIDs hook, the
	 *   extension should use this hook to set $ret to a non-null string;
	 *   it should be left untouched otherwise.
	 * @param PPFrame $frame PPFrame object to use for expanding any template variables
	 * @return true|void True or no return value
	 */
	public function onParserGetVariableValueSwitch( $parser, &$variableCache,
		$magicWordId, &$ret, $frame
	);
}

/** @deprecated class alias since 1.46 */
class_alias( ParserGetVariableValueSwitchHook::class, 'MediaWiki\\Hook\\ParserGetVariableValueSwitchHook' );
