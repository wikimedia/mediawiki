<?php

namespace MediaWiki\Hook;

use Parser;

/**
 * @stable
 * @ingroup Hooks
 */
interface ParserPreSaveTransformCompleteHook {
	/**
	 * This hook is called from Parser::preSaveTransform() after
	 * processing is complete, giving the extension a chance to further modify the
	 * wikitext.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser Calling Parser instance
	 * @param string &$text Transformed text, which can be modified by the hook
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserPreSaveTransformComplete( $parser, &$text );
}
