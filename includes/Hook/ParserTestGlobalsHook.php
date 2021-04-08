<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ParserTestGlobals" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserTestGlobalsHook {
	/**
	 * Use this hook to define globals for parser tests.
	 *
	 * @since 1.35
	 *
	 * @param array &$globals Array with all the globals which should be set for parser tests.
	 *   The arrays keys serve as the globals' names, its values are the globals' values.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserTestGlobals( &$globals );
}
