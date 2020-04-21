<?php

namespace MediaWiki\Content\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SearchDataForIndexHook {
	/**
	 * Add data to search document. Allows to add any data to
	 * the field map used to index the document.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$fields Array of name => value pairs for fields
	 * @param ?mixed $handler ContentHandler for the content being indexed
	 * @param ?mixed $page WikiPage that is being indexed
	 * @param ?mixed $output ParserOutput that is produced from the page
	 * @param ?mixed $engine SearchEngine for which the indexing is intended
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchDataForIndex( &$fields, $handler, $page, $output,
		$engine
	);
}
