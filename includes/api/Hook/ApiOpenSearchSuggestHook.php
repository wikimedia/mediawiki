<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiOpenSearchSuggestHook {
	/**
	 * Called when constructing the OpenSearch results. Hooks
	 * can alter or append to the array.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$results array with integer keys to associative arrays. Keys in associative
	 *   array:
	 *     - title: Title object.
	 *     - redirect from: Title or null.
	 *     - extract: Description for this result.
	 *     - extract trimmed: If truthy, the extract will not be trimmed to
	 *       $wgOpenSearchDescriptionLength.
	 *     - image: Thumbnail for this result. Value is an array with subkeys 'source'
	 *       (url), 'width', 'height', 'alt', 'align'.
	 *     - url: Url for the given title.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiOpenSearchSuggest( &$results );
}
