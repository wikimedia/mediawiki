<?php

namespace MediaWiki\Page\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface Article__MissingArticleConditionsHook {
	/**
	 * Before fetching deletion & move log entries
	 * to display a message of a non-existing page being deleted/moved, give extensions
	 * a chance to hide their (unrelated) log entries.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$conds Array of query conditions (all of which have to be met; conditions will
	 *   AND in the final query)
	 * @param ?mixed $logTypes Array of log types being queried
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticle__MissingArticleConditions( &$conds, $logTypes );
}
