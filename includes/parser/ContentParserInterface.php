<?php
interface ContentParserInterface {
	/**
	 * Convert wikitext to HTML
	 * Do not call this function recursively.
	 *
	 * @param string $text Text we want to parse
	 * @param Title $title
	 * @param ParserOptions $options
	 * @param bool $linestart
	 * @param bool $clearState
	 * @param int $revid Number to pass in {{REVISIONID}}
	 * @return ParserOutput A ParserOutput
	 */
	public function parse( $text, Title $title, ParserOptions $options,
		$lineStart = true, $clearState = true, $revId = null );
}
