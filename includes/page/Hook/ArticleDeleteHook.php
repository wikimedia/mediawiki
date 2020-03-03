<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleDeleteHook {
	/**
	 * Before an article is deleted.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage the WikiPage (object) being deleted
	 * @param ?mixed $user the user (object) deleting the article
	 * @param ?mixed &$reason the reason (string) the article is being deleted
	 * @param ?mixed &$error if the deletion was prohibited, the (raw HTML) error message to display
	 *   (added in 1.13)
	 * @param ?mixed &$status Status object, modify this to throw an error. Overridden by $error
	 *   (added in 1.20)
	 * @param ?mixed $suppress Whether this is a suppression deletion or not (added in 1.27)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleDelete( $wikiPage, $user, &$reason, &$error, &$status,
		$suppress
	);
}
