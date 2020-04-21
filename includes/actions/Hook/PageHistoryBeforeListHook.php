<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageHistoryBeforeListHook {
	/**
	 * When a history page list is about to be constructed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article the article that the history is loading for
	 * @param ?mixed $context RequestContext object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageHistoryBeforeList( $article, $context );
}
