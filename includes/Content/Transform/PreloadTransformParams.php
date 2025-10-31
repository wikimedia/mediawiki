<?php
namespace MediaWiki\Content\Transform;

use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOptions;

/**
 * @since 1.37
 * An interface to hold pre-load transform params.
 */
interface PreloadTransformParams {

	public function getPage(): PageReference;

	public function getParams(): array;

	public function getParserOptions(): ParserOptions;
}
