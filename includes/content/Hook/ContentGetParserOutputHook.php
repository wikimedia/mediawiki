<?php

namespace MediaWiki\Content\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContentGetParserOutputHook {
	/**
	 * Customize parser output for a given content object,
	 * called by AbstractContent::getParserOutput. May be used to override the normal
	 * model-specific rendering of page content.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $content The Content to render
	 * @param ?mixed $title Title of the page, as context
	 * @param ?mixed $revId The revision ID, as context
	 * @param ?mixed $options ParserOptions for rendering. To avoid confusing the parser cache,
	 *   the output can only depend on parameters provided to this hook function, not
	 *   on global state.
	 * @param ?mixed $generateHtml boolean, indicating whether full HTML should be generated. If
	 *   false, generation of HTML may be skipped, but other information should still
	 *   be present in the ParserOutput object.
	 * @param ?mixed &$output ParserOutput, to manipulate or replace
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentGetParserOutput( $content, $title, $revId, $options,
		$generateHtml, &$output
	);
}
