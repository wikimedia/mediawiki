<?php

namespace MediaWiki\Content\Hook;

use Language;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface PageContentLanguageHook {
	/**
	 * Use this hook to change the language in which the content of a page is written.
	 * Defaults to the wiki content language.
	 *
	 * @since 1.35
	 *
	 * @param Title $title
	 * @param Language|string &$pageLang Page content language. Input can be anything (under control of
	 *   hook subscribers), but hooks should return Language objects. Language code
	 *   strings are deprecated.
	 * @param Language $userLang User language
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageContentLanguage( $title, &$pageLang, $userLang );
}
