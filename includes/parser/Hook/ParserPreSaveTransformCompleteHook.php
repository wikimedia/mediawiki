<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserPreSaveTransformCompleteHook {
	/**
	 * Called from Parser::preSaveTransform() after
	 * processing is complete, giving the extension a chance to further modify the
	 * wikitext.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser the calling Parser instance
	 * @param ?mixed &$text The transformed text, which can be modified by the hook
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserPreSaveTransformComplete( $parser, &$text );
}
