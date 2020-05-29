<?php

namespace MediaWiki\Rest\Hook;

/**
 * Called by REST SearchHandler in order to allow extensions to fill the 'description'
 * field in search results. Warning: this hook, as well as SearchResultPageIdentity interface,
 * is under development and still unstable.
 *
 * @unstable
 * @ingroup Hooks
 */
interface SearchResultProvideDescriptionHook {
	/**
	 * This hook is called when generating search results in order to fill the 'description'
	 * field in an extension.
	 *
	 * @since 1.35
	 *
	 * @param array $pageIdentities Array (string=>SearchResultPageIdentity) where key is pageId
	 * @param array &$descriptions Output array (string=>string|null) where key
	 *   is pageId and value is either a description for given page or null
	 */
	public function onSearchResultProvideDescription( array $pageIdentities, &$descriptions );
}
