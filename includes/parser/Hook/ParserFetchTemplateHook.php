<?php

namespace MediaWiki\Hook;

use Parser;
use Revision;
use Title;

/**
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface ParserFetchTemplateHook {
	/**
	 * This hook is called when the parser fetches a template.
	 *
	 * @since 1.35
	 *
	 * @param Parser|bool $parser Parser object or false
	 * @param Title $title Title object of the template to be fetched
	 * @param Revision $rev Revision object of the template
	 * @param string|bool|null &$text Transclusion text of the template or false or null
	 * @param array &$deps Array of template dependencies with 'title', 'page_id', and
	 *   'rev_id' keys
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserFetchTemplate( $parser, $title, $rev, &$text, &$deps );
}
