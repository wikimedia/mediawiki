<?php

namespace MediaWiki\Hook;

use Article;
use IContextSource;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface PageHistoryBeforeListHook {
	/**
	 * This hook is called when a history page list is about to be constructed.
	 *
	 * @since 1.35
	 *
	 * @param Article $article The article that the history is loading for
	 * @param IContextSource $context
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageHistoryBeforeList( $article, $context );
}
