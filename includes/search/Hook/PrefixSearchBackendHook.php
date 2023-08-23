<?php

namespace MediaWiki\Search\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PrefixSearchBackend" to register handlers implementing this interface.
 *
 * @deprecated since 1.27. Override SearchEngine::completionSearchBackend instead. Emitting warnings since 1.41.
 * @ingroup Hooks
 */
interface PrefixSearchBackendHook {
	/**
	 * Use this hook to override the title prefix search used for OpenSearch and
	 * AJAX search suggestions. Put results into &$results outparam and return false.
	 *
	 * @since 1.35
	 *
	 * @param int[] $ns Array of int namespace keys to search in
	 * @param string $search Search term (not guaranteed to be conveniently normalized)
	 * @param int $limit Maximum number of results to return
	 * @param string[] &$results Out param: array of page names
	 * @param int $offset Number of results to offset from the beginning
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPrefixSearchBackend( $ns, $search, $limit, &$results,
		$offset
	);
}
