<?php
namespace MediaWiki\Content\Transform;

use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOptions;

/**
 * @since 1.37
 * An interface to hold pre-load transform params.
 */
interface PreloadTransformParams {

	/**
	 * @return PageReference
	 */
	public function getPage(): PageReference;

	/**
	 * @return array
	 */
	public function getParams(): array;

	/**
	 * @return ParserOptions
	 */
	public function getParserOptions(): ParserOptions;
}
