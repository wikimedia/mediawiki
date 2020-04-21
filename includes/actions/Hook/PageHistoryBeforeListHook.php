<?php

namespace MediaWiki\Hook;

use RequestContext;
use WikiPage;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageHistoryBeforeListHook {
	/**
	 * This hook is called when a history page list is about to be constructed.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $article The article that the history is loading for
	 * @param RequestContext $context
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageHistoryBeforeList( $article, $context );
}
