<?php

namespace MediaWiki\Search\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SearchResultProvideThumbnail" to register handlers implementing this interface.
 *
 * Called in order to allow extensions to fill the 'thumbnail' field in search results.
 * Warning: this hook is under development and still unstable.
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
	 * @param array $pageIdentities Array (string=>PageIdentity) where key is pageId
	 * @param array &$thumbnails Output array (string=>SearchResultThumbnail|null) where key
	 *   is pageId and value is either a valid SearchResultThumbnail for given page or null
	 * @param int|null $size size of thumbnail height and width in points
	 */
	public function onSearchResultProvideThumbnail( array $pageIdentities, &$thumbnails, ?int $size = null );
}
