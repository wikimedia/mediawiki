<?php

namespace MediaWiki\Page\Hook;

use MediaWiki\Title\Title;
use OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticleDeleteAfterSuccess" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleDeleteAfterSuccessHook {
	/**
	 * Use this hook to modify the output after an article has been deleted.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Article that has been deleted
	 * @param OutputPage $outputPage OutputPage that can be used to append the output
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleDeleteAfterSuccess( $title, $outputPage );
}
