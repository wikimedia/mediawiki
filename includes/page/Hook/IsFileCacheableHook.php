<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface IsFileCacheableHook {
	/**
	 * Override the result of Article::isFileCacheable() (if true)
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article article (object) being checked
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onIsFileCacheable( $article );
}
