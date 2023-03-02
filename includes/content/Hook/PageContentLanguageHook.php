<?php

namespace MediaWiki\Content\Hook;

use Language;
use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PageContentLanguage" to register handlers implementing this interface.
 *
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
	 * @param Language &$pageLang Page content language
	 * @param Language $userLang User language
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageContentLanguage( $title, &$pageLang, $userLang );
}
