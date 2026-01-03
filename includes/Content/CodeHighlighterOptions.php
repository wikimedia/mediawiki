<?php

namespace MediaWiki\Content;

/**
 * @newable
 * @since 1.47
 * @ingroup Content
 */
class CodeHighlighterOptions {

	/**
	 * @param string $language Language code
	 * @param string[] $classes Classes to apply to the output HTML element
	 * @param string $dir HTML dir attribute to apply to the output HTML element
	 * @param bool $inline Whether the code block should be rendered inline with text
	 * @param bool $includeLineNumbers Whether the code block should be rendered inline with text
	 * @param int $startingLineNumber Line number for the first line of the code block
	 * @param bool $includeLineLinks Whether the line numbers should have links
	 * @param string $linkLinkAnchorPrefix Prefix for the anchor for each line number (if $includeLineLinks is enabled)
	 * @param string $highlightLines Comma-separated list of line numbers and line number-ranges to be highlighted
	 * @param bool $isCopiable Whether a button should be shown for copying the code to the clipboard
	 */
	public function __construct(
		public string $language,
		public array $classes = [],
		public string $dir = 'ltr',
		public bool $inline = false,
		public bool $includeLineNumbers = false,
		public int $startingLineNumber = 1,
		public bool $includeLineLinks = false,
		public string $linkLinkAnchorPrefix = 'L',
		public string $highlightLines = '',
		public bool $isCopiable = false
	) {
	}

}
