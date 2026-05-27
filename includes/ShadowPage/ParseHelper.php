<?php

namespace MediaWiki\ShadowPage;

use MediaWiki\Content\Content;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;

/**
 * Helper functions for providers that need to parse wikitext or other content.
 * A policy layer which may also provide caching in future.
 *
 * @since 1.47
 */
class ParseHelper {
	/**
	 * Get parsed content associated with a shadow page
	 *
	 * @param Content $content
	 * @param PageReference $title
	 * @param ParserOptions $parserOptions
	 * @return SimpleView
	 */
	public function getParsedContentView(
		Content $content,
		PageReference $title,
		ParserOptions $parserOptions,
	): SimpleView {
		$cpp = new ContentParseParams( $title, null, $parserOptions );
		return new SimpleView(
			$content->getContentHandler()->getParserOutput( $content, $cpp ),
			$parserOptions
		);
	}

	/**
	 * Create a view containing HTML
	 *
	 * @param string $html
	 * @param ParserOptions $parserOptions
	 * @return SimpleView
	 */
	public function newFromHtml( string $html, ParserOptions $parserOptions ): SimpleView {
		return new SimpleView(
			new ParserOutput( $html ),
			$parserOptions
		);
	}

}
