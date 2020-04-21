<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LanguageGetNamespacesHook {
	/**
	 * Provide custom ordering for namespaces or
	 * remove namespaces. Do not use this hook to add namespaces. Use
	 * CanonicalNamespaces for that.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$namespaces Array of namespaces indexed by their numbers
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLanguageGetNamespaces( &$namespaces );
}
