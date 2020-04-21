<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiMakeParserOptionsHook {
	/**
	 * Called from ApiParse and ApiExpandTemplates to allow
	 * extensions to adjust the ParserOptions before parsing.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $options ParserOptions object
	 * @param ?mixed $title Title to be parsed
	 * @param ?mixed $params Parameter array for the API module
	 * @param ?mixed $module API module (which is also a ContextSource)
	 * @param ?mixed &$reset Set to a ScopedCallback used to reset any hooks after the parse is done.
	 * @param ?mixed &$suppressCache Set true if cache should be suppressed.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiMakeParserOptions( $options, $title, $params, $module,
		&$reset, &$suppressCache
	);
}
