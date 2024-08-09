<?php

namespace MediaWiki\Output\Hook;

use MediaWiki\Output\OutputPage;
use MediaWiki\Page\ProperPageIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "OutputPageRenderCategoryLink" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface OutputPageRenderCategoryLinkHook {
	/**
	 * This hook is called when a category link is rendered.
	 *
	 * @since 1.43
	 *
	 * @param OutputPage $outputPage
	 * @param ProperPageIdentity $categoryTitle Category title
	 * @param string $text HTML escaped category name
	 * @param ?string &$link HTML of rendered category link which can be replaced by a different HTML
	 * @return void This hook must not abort, it must return no value
	 */
	public function onOutputPageRenderCategoryLink(
		OutputPage $outputPage,
		ProperPageIdentity $categoryTitle,
		string $text,
		?string &$link
	): void;
}
