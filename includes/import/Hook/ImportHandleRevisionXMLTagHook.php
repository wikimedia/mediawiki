<?php

namespace MediaWiki\Hook;

use WikiImporter;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ImportHandleRevisionXMLTagHook {
	/**
	 * This hook is called when parsing an XML tag in a page revision.
	 *
	 * @since 1.35
	 *
	 * @param WikiImporter $reader
	 * @param array $pageInfo Array of page information
	 * @param array $revisionInfo Array of revision information
	 * @return bool|void True or no return value to continue, or false to stop further
	 *   processing of the tag
	 */
	public function onImportHandleRevisionXMLTag( $reader, $pageInfo,
		$revisionInfo
	);
}
