<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OpportunisticLinksUpdateHook {
	/**
	 * Called by WikiPage::triggerOpportunisticLinksUpdate
	 * when a page view triggers a re-rendering of the page. This may happen
	 * particularly if the parser cache is split by user language, and no cached
	 * rendering of the page exists in the user's language. The hook is called
	 * before checking whether page_links_updated indicates that the links are up
	 * to date. Returning false will cause triggerOpportunisticLinksUpdate() to abort
	 * without triggering any updates.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $page the Page that was rendered.
	 * @param ?mixed $title the Title of the rendered page.
	 * @param ?mixed $parserOutput ParserOutput resulting from rendering the page.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOpportunisticLinksUpdate( $page, $title, $parserOutput );
}
