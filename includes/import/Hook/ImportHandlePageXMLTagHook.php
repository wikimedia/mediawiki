<?php

namespace MediaWiki\Hook;

use WikiImporter;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ImportHandlePageXMLTagHook {
	/**
	 * This hook is called when parsing an XML tag in a page.
	 *
	 * @since 1.35
	 *
	 * @param WikiImporter $reader
	 * @param array &$pageInfo Array of information
	 * @return bool|void True or no return value to continue, or false to stop further
	 *   processing of the tag
	 */
	public function onImportHandlePageXMLTag( $reader, &$pageInfo );
}
