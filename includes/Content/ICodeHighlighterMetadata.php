<?php

namespace MediaWiki\Content;

use MediaWiki\Output\OutputPage;
use MediaWiki\Parser\ParserOutput;

/**
 * Metadata needed to render highlighted code.
 *
 * Implementations should apply all metadata they carry to the supplied output
 * object, so callers do not need to know about each individual metadata type.
 *
 * @since 1.47
 * @ingroup Content
 */
interface ICodeHighlighterMetadata {

	/**
	 * Add this metadata to a ParserOutput.
	 *
	 * @param ParserOutput $parserOutput
	 */
	public function addToParserOutput( ParserOutput $parserOutput ): void;

	/**
	 * Add this metadata to an OutputPage.
	 *
	 * @param OutputPage $outputPage
	 */
	public function addToOutputPage( OutputPage $outputPage ): void;
}
