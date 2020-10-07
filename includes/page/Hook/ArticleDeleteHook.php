<?php

namespace MediaWiki\Page\Hook;

use Status;
use User;
use WikiPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleDeleteHook {
	/**
	 * This hook is called before an article is deleted.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage being deleted
	 * @param User $user User deleting the article
	 * @param string &$reason Reason the article is being deleted
	 * @param string &$error If the deletion was prohibited, the (raw HTML) error message to display
	 *   (added in 1.13)
	 * @param Status &$status Modify this to throw an error. Overridden by $error
	 *   (added in 1.20)
	 * @param bool $suppress Whether this is a suppression deletion or not (added in 1.27)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleDelete( $wikiPage, $user, &$reason, &$error, &$status,
		$suppress
	);
}
