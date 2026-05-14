<?php

namespace MediaWiki\ShadowPage;

use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;

/**
 * A bundle for page view data
 *
 * @since 1.47
 */
interface ShadowPageView {
	/**
	 * Get content to be shown on page view of a non-existent page.
	 *
	 * Providers can use BaseShadowPageProvider::getParseHelper() if they
	 * need help making one of these objects.
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput(): ParserOutput;

	/**
	 * Get the ParserOptions which were used for getParserOutput(), to be
	 * passed to OutputTransformPipeline.
	 *
	 * @return ParserOptions
	 */
	public function getParserOptions(): ParserOptions;
}
