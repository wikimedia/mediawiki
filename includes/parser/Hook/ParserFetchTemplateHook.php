<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserFetchTemplateHook {
	/**
	 * Called when the parser fetches a template
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser Parser object or false
	 * @param ?mixed $title Title object of the template to be fetched
	 * @param ?mixed $rev Revision object of the template
	 * @param ?mixed &$text Transclusion text of the template or false or null
	 * @param ?mixed &$deps Array of template dependencies with 'title', 'page_id', 'rev_id' keys
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserFetchTemplate( $parser, $title, $rev, &$text, &$deps );
}
