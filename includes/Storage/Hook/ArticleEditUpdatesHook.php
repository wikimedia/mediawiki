<?php

namespace MediaWiki\Storage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleEditUpdatesHook {
	/**
	 * DEPRECATED since 1.35, use RevisionDataUpdates instead.
	 * When edit updates (mainly link tracking) are made when an article has been changed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage the WikiPage (object)
	 * @param ?mixed $editInfo data holder that includes the parser output ($editInfo->output) for
	 *   that page after the change
	 * @param ?mixed $changed bool for if the page was changed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleEditUpdates( $wikiPage, $editInfo, $changed );
}
