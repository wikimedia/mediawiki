<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiOpenSearchSuggestHook {
	/**
	 * This hook is called when constructing the OpenSearch results. Hooks
	 * can alter or append to the array.
	 *
	 * @since 1.35
	 *
	 * @param array[] &$results Array with integer keys to associative arrays.
	 *   Keys in associative array:
	 *     - `title`: Title object
	 *     - `redirect from`: Title or null
	 *     - `extract`: Description for this result
	 *     - `extract trimmed`: If truthy, the extract will not be trimmed to
	 *       $wgOpenSearchDescriptionLength.
	 *     - `image`: Thumbnail for this result. Value is an array with subkeys `source`
	 *       (URL), `width`, `height`, `alt`, and `align`.
	 *     - `url`: URL for the given title
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiOpenSearchSuggest( &$results );
}
