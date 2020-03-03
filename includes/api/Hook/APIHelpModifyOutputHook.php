<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface APIHelpModifyOutputHook {
	/**
	 * Use this hook to modify an API module's help output.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module ApiBase Module object
	 * @param ?mixed &$help Array of HTML strings to be joined for the output.
	 * @param ?mixed $options Array Options passed to ApiHelp::getHelp
	 * @param ?mixed &$tocData Array If a TOC is being generated, this array has keys as anchors in
	 *   the page and values as for Linker::generateTOC().
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIHelpModifyOutput( $module, &$help, $options, &$tocData );
}
