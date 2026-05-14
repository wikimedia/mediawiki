<?php

namespace MediaWiki\ShadowPage;

use MediaWiki\Content\Content;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Page\PageReference;

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
	 * @return SimpleView
	 */
	public function getParsedContentView( Content $content, PageReference $title ): SimpleView {
		$cpp = new ContentParseParams( $title );
		return new SimpleView(
			$content->getContentHandler()->getParserOutput( $content, $cpp ),
			$cpp->getParserOptions()
		);
	}

}
