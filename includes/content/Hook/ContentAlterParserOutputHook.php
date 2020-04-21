<?php

namespace MediaWiki\Content\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContentAlterParserOutputHook {
	/**
	 * Modify parser output for a given content object.
	 * Called by Content::getParserOutput after parsing has finished. Can be used
	 * for changes that depend on the result of the parsing but have to be done
	 * before LinksUpdate is called (such as adding tracking categories based on
	 * the rendered HTML).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $content The Content to render
	 * @param ?mixed $title Title of the page, as context
	 * @param ?mixed $parserOutput ParserOutput to manipulate
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentAlterParserOutput( $content, $title, $parserOutput );
}
