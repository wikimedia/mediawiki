<?php

namespace MediaWiki\Hook;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Revision\RevisionRecord;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface BeforeParserFetchTemplateRevisionRecordHook {
	/**
	 * This hook is called before a template is fetched by Parser.
	 * It allows redirection of the title and/or revision id of the
	 * template.  For example: the template could be redirected to
	 * an appropriately localized version of the template; or the
	 * template fetch could be redirected to a 'stable revision' of
	 * the template.  If the returned RevisionRecord does not exist,
	 * its title will be added to the page dependencies and then this
	 * hook will be invoked again to resolve that title.  This allows
	 * for fallback chains (of limited length).
	 *
	 * @since 1.36
	 *
	 * @param ?LinkTarget $contextTitle The top-level page title, if any
	 * @param LinkTarget $title The template link (from the literal wikitext)
	 * @param bool &$skip Skip this template and link it?
	 * @param ?RevisionRecord &$revRecord The desired revision record
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeParserFetchTemplateRevisionRecord(
		?LinkTarget $contextTitle, LinkTarget $title,
		bool &$skip, ?RevisionRecord &$revRecord
	);
}
