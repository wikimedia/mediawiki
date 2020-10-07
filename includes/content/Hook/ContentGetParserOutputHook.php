<?php

namespace MediaWiki\Content\Hook;

use Content;
use ParserOptions;
use ParserOutput;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ContentGetParserOutputHook {
	/**
	 * Use this hook to customize parser output for a given content object. This hook is
	 * called by AbstractContent::getParserOutput. May be used to override the normal
	 * model-specific rendering of page content.
	 *
	 * @since 1.35
	 *
	 * @param Content $content Content to render
	 * @param Title $title Title of the page, as context
	 * @param int $revId Revision ID, as context
	 * @param ParserOptions $options ParserOptions for rendering. To avoid confusing the parser cache,
	 *   the output can only depend on parameters provided to this hook function, not on global state.
	 * @param bool $generateHtml Whether full HTML should be generated. If false, generation of HTML
	 *   may be skipped, but other information should still be present in the ParserOutput object.
	 * @param ParserOutput &$output ParserOutput to manipulate or replace
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentGetParserOutput( $content, $title, $revId, $options,
		$generateHtml, &$output
	);
}
