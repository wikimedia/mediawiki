<?php

namespace MediaWiki\Storage\Hook;

use MediaWiki\Edit\PreparedEdit;
use WikiPage;

/**
 * @deprecated since 1.35 Use RevisionDataUpdates instead
 * @ingroup Hooks
 */
interface ArticleEditUpdatesHook {
	/**
	 * This hook is called when edit updates (mainly link tracking) are made
	 * when an article has been changed.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage
	 * @param PreparedEdit $editInfo Data holder that includes the parser output
	 *   ($editInfo->output) for that page after the change
	 * @param bool $changed Whether the page was changed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleEditUpdates( $wikiPage, $editInfo, $changed );
}
