<?php

namespace MediaWiki\ShadowPage;

use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;

/**
 * Simple concrete implementation of ShadowPageView
 *
 * @since 1.47
 */
class SimpleView implements ShadowPageView {
	public function __construct(
		private ParserOutput $parserOutput,
		private ParserOptions $parserOptions
	) {
	}

	public function getParserOutput(): ParserOutput {
		return $this->parserOutput;
	}

	public function getParserOptions(): ParserOptions {
		return $this->parserOptions;
	}
}
