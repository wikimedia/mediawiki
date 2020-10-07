<?php

namespace MediaWiki\Hook;

use Parser;

/**
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface ParserSectionCreateHook {
	/**
	 * This hook is called each time the parser creates a document section
	 * from wikitext. Use this to apply per-section modifications to HTML (like
	 * wrapping the section in a DIV).  Caveat: DIVs are valid wikitext, and a DIV
	 * can begin in one section and end in another. Make sure your code can handle
	 * that case gracefully. See the EditSectionClearerLink extension for an example.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser Calling Parser instance
	 * @param int $section Section number, zero-based, but section 0 is usually empty
	 * @param string &$sectionContent Reference to the content of the section, which
	 *   can be modified by the hook
	 * @param bool $showEditLinks Whether this section has an edit link
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserSectionCreate( $parser, $section, &$sectionContent,
		$showEditLinks
	);
}
