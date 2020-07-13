<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface OpenSearchUrlsHook {
	/**
	 * This hook is called when constructing the OpenSearch description XML. Hooks
	 * can alter or append to the array of URLs for search & suggestion formats.
	 *
	 * @since 1.35
	 *
	 * @param array[] &$urls Array of associative arrays with URL element attributes
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOpenSearchUrls( &$urls );
}
