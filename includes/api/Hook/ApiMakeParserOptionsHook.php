<?php

namespace MediaWiki\Api\Hook;

use ApiBase;
use ParserOptions;
use Title;
use Wikimedia\ScopedCallback;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiMakeParserOptionsHook {
	/**
	 * This hook is called from ApiParse and ApiExpandTemplates to allow
	 * extensions to adjust the ParserOptions before parsing.
	 *
	 * @since 1.35
	 *
	 * @param ParserOptions $options
	 * @param Title $title Title to be parsed
	 * @param array $params Parameter array for the API module
	 * @param ApiBase $module API module (which is also a ContextSource)
	 * @param ScopedCallback|null &$reset Set to a ScopedCallback used to reset any hooks after
	 *  the parse is done
	 * @param bool &$suppressCache Set true if cache should be suppressed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiMakeParserOptions( $options, $title, $params, $module,
		&$reset, &$suppressCache
	);
}
