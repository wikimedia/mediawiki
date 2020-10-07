<?php

namespace MediaWiki\Page\Hook;

use Article;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface IsFileCacheableHook {
	/**
	 * Use this hook to override the result of Article::isFileCacheable().
	 *
	 * @since 1.35
	 *
	 * @param Article $article Article being checked
	 * @return bool|void True or no return value to override or false to abort
	 */
	public function onIsFileCacheable( $article );
}
