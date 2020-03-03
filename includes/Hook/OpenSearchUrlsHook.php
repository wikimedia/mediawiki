<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OpenSearchUrlsHook {
	/**
	 * Called when constructing the OpenSearch description XML. Hooks
	 * can alter or append to the array of URLs for search & suggestion formats.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$urls array of associative arrays with Url element attributes
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOpenSearchUrls( &$urls );
}
