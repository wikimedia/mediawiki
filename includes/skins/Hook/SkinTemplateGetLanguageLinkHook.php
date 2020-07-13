<?php

namespace MediaWiki\Hook;

use OutputPage;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SkinTemplateGetLanguageLinkHook {
	/**
	 * This hook is called after building the data for a language link from
	 * which the actual html is constructed.
	 *
	 * @since 1.35
	 *
	 * @param array &$languageLink Array containing data about the link. The following keys can be
	 *   modified: href, text, title, class, lang, hreflang. Each of them is a string.
	 * @param Title $languageLinkTitle Title object belonging to the external language link
	 * @param Title $title Title object of the page the link belongs to
	 * @param OutputPage $outputPage OutputPage object the links are built from
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplateGetLanguageLink( &$languageLink,
		$languageLinkTitle, $title, $outputPage
	);
}
