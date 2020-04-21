<?php

namespace MediaWiki\Storage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleEditUpdatesDeleteFromRecentchangesHook {
	/**
	 * DEPRECATED since 1.35, use RecentChange_save
	 * or similar instead. Before deleting old entries from
	 * recentchanges table, return false to not delete old entries.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage WikiPage (object) being modified
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleEditUpdatesDeleteFromRecentchanges( $wikiPage );
}
