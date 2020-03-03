<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinTemplateGetLanguageLinkHook {
	/**
	 * After building the data for a language link from
	 * which the actual html is constructed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$languageLink array containing data about the link. The following keys can be
	 *   modified: href, text, title, class, lang, hreflang. Each of them is a string.
	 * @param ?mixed $languageLinkTitle Title object belonging to the external language link.
	 * @param ?mixed $title Title object of the page the link belongs to.
	 * @param ?mixed $outputPage The OutputPage object the links are built from.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplateGetLanguageLink( &$languageLink,
		$languageLinkTitle, $title, $outputPage
	);
}
