<?php

namespace MediaWiki\Content\Hook;

use Content;
use ParserOutput;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ContentAlterParserOutputHook {
	/**
	 * Use this hook to modify parser output for a given content object. This hook is called by
	 * Content::getParserOutput after parsing has finished. Can be used for changes that depend
	 * on the result of the parsing but have to be done before LinksUpdate is called (such as
	 * adding tracking categories based on the rendered HTML).
	 *
	 * @since 1.35
	 *
	 * @param Content $content Content to render
	 * @param Title $title Title of the page, as context
	 * @param ParserOutput $parserOutput ParserOutput to manipulate
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentAlterParserOutput( $content, $title, $parserOutput );
}
