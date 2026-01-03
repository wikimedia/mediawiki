<?php

namespace MediaWiki\Content;

/**
 * Value object for code highlighting output.
 *
 * @newable
 * @since 1.47
 * @ingroup Content
 */
class CodeHighlighterOutput {

	/**
	 * @param string $html The HTML representing the highlighted code
	 * @param ICodeHighlighterMetadata $metadata Metadata necessary to render the highlighted code
	 */
	public function __construct(
		private string $html,
		private ICodeHighlighterMetadata $metadata = new CodeHighlighterMetadata(),
	) {
	}

	public function getHtml(): string {
		return $this->html;
	}

	public function getMetadata(): ICodeHighlighterMetadata {
		return $this->metadata;
	}

}
