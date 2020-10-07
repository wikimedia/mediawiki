<?php

namespace MediaWiki\Storage\Hook;

use WikiPage;

/**
 * @deprecated since 1.35 Use RecentChange_save or similar instead
 * @ingroup Hooks
 */
interface ArticleEditUpdatesDeleteFromRecentchangesHook {
	/**
	 * This hook is called before deleting old entries from
	 * recentchanges table.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage being modified
	 * @return bool|void True or no return value to continue, or false
	 *   to not delete old entries
	 */
	public function onArticleEditUpdatesDeleteFromRecentchanges( $wikiPage );
}
