<?php

namespace MediaWiki\Api\Hook;

use ApiBase;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface APIHelpModifyOutputHook {
	/**
	 * Use this hook to modify an API module's help output.
	 *
	 * @since 1.35
	 *
	 * @param ApiBase $module Module object
	 * @param string[] &$help Array of HTML strings to be joined for the output
	 * @param array $options Array of formatting options passed to ApiHelp::getHelp
	 * @param array &$tocData If a TOC is being generated, this array has keys as anchors in
	 *   the page and values as for Linker::generateTOC().
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIHelpModifyOutput( $module, &$help, $options, &$tocData );
}
