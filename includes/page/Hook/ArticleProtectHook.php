<?php

namespace MediaWiki\Page\Hook;

use User;
use WikiPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleProtectHook {
	/**
	 * This hook is called before an article is protected.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage being protected
	 * @param User $user User doing the protection
	 * @param array $protect Set of restriction keys
	 * @param string $reason Reason for protect
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleProtect( $wikiPage, $user, $protect, $reason );
}
