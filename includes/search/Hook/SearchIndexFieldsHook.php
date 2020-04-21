<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SearchIndexFieldsHook {
	/**
	 * Add fields to search index mapping.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$fields Array of fields, all implement SearchIndexField
	 * @param ?mixed $engine SearchEngine instance for which mapping is being built.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchIndexFields( &$fields, $engine );
}
