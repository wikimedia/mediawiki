<?php

namespace MediaWiki\Content\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageContentLanguageHook {
	/**
	 * Allows changing the language in which the content of a
	 * page is written. Defaults to the wiki content language.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object
	 * @param ?mixed &$pageLang the page content language. Input can be anything (under control of
	 *   hook subscribers), but hooks should return Language objects. Language code
	 *   strings are deprecated.
	 * @param ?mixed $userLang the user language (Language object)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageContentLanguage( $title, &$pageLang, $userLang );
}
