<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SearchableNamespacesHook {
	/**
	 * Use this hook to modify which namespaces are searchable.
	 *
	 * @since 1.35
	 *
	 * @param int[] &$arr Array of namespaces ($nsId => $name) which will be used
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchableNamespaces( &$arr );
}
