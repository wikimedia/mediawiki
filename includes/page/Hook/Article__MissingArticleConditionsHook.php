<?php

namespace MediaWiki\Page\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface Article__MissingArticleConditionsHook {
	/**
	 * This hook is called before fetching deletion and move log entries
	 * to display a message of a non-existing page being deleted/moved.
	 * Use this hook to hide unrelated log entries.
	 *
	 * @since 1.35
	 *
	 * @param array &$conds Array of query conditions (all of which have to be met;
	 *   conditions will AND in the final query)
	 * @param string[] $logTypes Array of log types being queried
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticle__MissingArticleConditions( &$conds, $logTypes );
}
