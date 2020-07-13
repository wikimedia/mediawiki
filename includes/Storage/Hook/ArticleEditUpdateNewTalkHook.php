<?php

namespace MediaWiki\Storage\Hook;

use User;
use WikiPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleEditUpdateNewTalkHook {
	/**
	 * This hook is called before updating user_newtalk when a user talk page
	 * was changed.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage of the user talk page
	 * @param User $recipient User whose talk page was edited
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleEditUpdateNewTalk( $wikiPage, $recipient );
}
