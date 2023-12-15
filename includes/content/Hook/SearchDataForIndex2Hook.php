<?php

namespace MediaWiki\Content\Hook;

use ContentHandler;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\RevisionRecord;
use SearchEngine;
use WikiPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SearchDataForIndex" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SearchDataForIndex2Hook {

	/**
	 * Use this hook to add data to search document. Allows you to add any data to
	 * the field map used to index the document.
	 *
	 * @since 1.40
	 *
	 * @param array &$fields Array of name => value pairs for fields
	 * @param ContentHandler $handler ContentHandler for the content being indexed
	 * @param WikiPage $page WikiPage that is being indexed
	 * @param ParserOutput $output ParserOutput that is produced from the page
	 * @param SearchEngine $engine SearchEngine for which the indexing is intended
	 * @param RevisionRecord $revision RevisionRecord being indexed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchDataForIndex2(
		array &$fields,
		ContentHandler $handler,
		WikiPage $page,
		ParserOutput $output,
		SearchEngine $engine,
		RevisionRecord $revision
	);
}
