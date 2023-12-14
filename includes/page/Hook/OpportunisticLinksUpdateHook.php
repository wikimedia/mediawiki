<?php

namespace MediaWiki\Page\Hook;

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Title\Title;
use WikiPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "OpportunisticLinksUpdate" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface OpportunisticLinksUpdateHook {
	/**
	 * This hook is called by WikiPage::triggerOpportunisticLinksUpdate
	 * when a page view triggers a re-rendering of the page. This may happen
	 * particularly if the parser cache is split by user language, and no cached
	 * rendering of the page exists in the user's language. The hook is called
	 * before checking whether page_links_updated indicates that the links are up
	 * to date.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $page WikiPage that was rendered
	 * @param Title $title Title of the rendered page
	 * @param ParserOutput $parserOutput ParserOutput resulting from rendering the page
	 * @return bool|void True or no return value to continue, or false to abort
	 *   triggerOpportunisticLinksUpdate() without triggering any updates
	 */
	public function onOpportunisticLinksUpdate( $page, $title, $parserOutput );
}
