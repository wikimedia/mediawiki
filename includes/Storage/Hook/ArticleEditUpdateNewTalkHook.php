<?php

namespace MediaWiki\Storage\Hook;

use MediaWiki\User\User;
use WikiPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticleEditUpdateNewTalk" to register handlers implementing this interface.
 *
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
