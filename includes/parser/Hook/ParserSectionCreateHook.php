<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserSectionCreateHook {
	/**
	 * Called each time the parser creates a document section
	 * from wikitext. Use this to apply per-section modifications to HTML (like
	 * wrapping the section in a DIV).  Caveat: DIVs are valid wikitext, and a DIV
	 * can begin in one section and end in another. Make sure your code can handle
	 * that case gracefully. See the EditSectionClearerLink extension for an example.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser the calling Parser instance
	 * @param ?mixed $section the section number, zero-based, but section 0 is usually empty
	 * @param ?mixed &$sectionContent ref to the content of the section. modify this.
	 * @param ?mixed $showEditLinks boolean describing whether this section has an edit link
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserSectionCreate( $parser, $section, &$sectionContent,
		$showEditLinks
	);
}
