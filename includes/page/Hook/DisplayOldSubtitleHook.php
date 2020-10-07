<?php

namespace MediaWiki\Page\Hook;

use Article;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface DisplayOldSubtitleHook {
	/**
	 * This hook is called before creating subtitle when browsing old versions of
	 * an article.
	 *
	 * @since 1.35
	 *
	 * @param Article $article Article being viewed
	 * @param int &$oldid Old ID being viewed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDisplayOldSubtitle( $article, &$oldid );
}
