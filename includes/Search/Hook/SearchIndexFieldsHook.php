<?php

namespace MediaWiki\Search\Hook;

use SearchEngine;
use SearchIndexField;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SearchIndexFields" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SearchIndexFieldsHook {
	/**
	 * Use this hook to add fields to search index mapping.
	 *
	 * @since 1.35
	 *
	 * @param SearchIndexField[] &$fields Array of fields, all implement SearchIndexField
	 * @param SearchEngine $engine SearchEngine instance for which mapping is being built
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchIndexFields( &$fields, $engine );
}
