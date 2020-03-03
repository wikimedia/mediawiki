<?php

namespace MediaWiki\Storage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleEditUpdateNewTalkHook {
	/**
	 * Before updating user_newtalk when a user talk page
	 * was changed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage WikiPage (object) of the user talk page
	 * @param ?mixed $recipient User (object) who's talk page was edited
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleEditUpdateNewTalk( $wikiPage, $recipient );
}
