<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PrefixSearchBackendHook {
	/**
	 * DEPRECATED since 1.27! Override
	 * SearchEngine::completionSearchBackend instead.
	 * Override the title prefix search used for OpenSearch and
	 * AJAX search suggestions. Put results into &$results outparam and return false.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $ns array of int namespace keys to search in
	 * @param ?mixed $search search term (not guaranteed to be conveniently normalized)
	 * @param ?mixed $limit maximum number of results to return
	 * @param ?mixed &$results out param: array of page names (strings)
	 * @param ?mixed $offset number of results to offset from the beginning
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPrefixSearchBackend( $ns, $search, $limit, &$results,
		$offset
	);
}
