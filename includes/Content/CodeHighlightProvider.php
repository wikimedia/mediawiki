<?php

namespace MediaWiki\Content;

/**
 * @stable to extend
 * @since 1.47
 * @ingroup Content
 */
abstract class CodeHighlightProvider {

	/**
	 * Checks if the given language is supported by this code highlighter implementation.
	 *
	 * @param string $lang
	 * @return bool
	 */
	abstract public function isSupportedLanguage( string $lang ): bool;

	/**
	 * Implements the highlighting of the code based on the given language.
	 *
	 * @param string $code
	 * @param CodeHighlighterOptions $options
	 * @return CodeHighlighterOutput
	 */
	abstract public function highlight( string $code, CodeHighlighterOptions $options ): CodeHighlighterOutput;
}
