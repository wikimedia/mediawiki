<?php

namespace MediaWiki\Rest\Hook;

/**
 * Called by REST SearchHandler in order to allow extensions to fill the 'thumbnail'
 * field in rest search results. Warning: this hook, as well as SearchResultPageIdentity
 * interface, is under development and still unstable.
 *
 * @unstable
 * @ingroup Hooks
 */
interface SearchResultProvideThumbnailHook {
	/**
	 * This hook is called when generating search results in order to fill the 'thumbnail'
	 * field in an extension.
	 *
	 * @since 1.35
	 *
	 * @param array $pageIdentities Array (string=>SearchResultPageIdentity) where key is pageId
	 * @param array &$thumbnails Output array (string=>SearchResultThumbnail|null) where key
	 *   is pageId and value is either a valid SearchResultThumbnail for given page or null
	 */
	public function onSearchResultProvideThumbnail( array $pageIdentities, &$thumbnails );
}
