<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SearchableNamespacesHook {
	/**
	 * An option to modify which namespaces are searchable.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$arr Array of namespaces ($nsId => $name) which will be used.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchableNamespaces( &$arr );
}
