<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface PrefixSearchExtractNamespaceHook {
	/**
	 * This hook is called if core was not able to extract a
	 * namespace from the search string so that extensions can attempt it.
	 *
	 * @since 1.35
	 *
	 * @param int[] &$namespaces Array of int namespace keys to search in
	 *   (change this if you can extract namespaces)
	 * @param string &$search Search term (replace this with term without
	 *   the namespace if you can extract one)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPrefixSearchExtractNamespace( &$namespaces, &$search );
}
