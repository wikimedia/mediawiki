<?php

namespace MediaWiki\Hook;

use WikiImporter;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ImportHandleLogItemXMLTagHook {
	/**
	 * This hook is called when parsing an XML tag in a log item.
	 *
	 * @since 1.35
	 *
	 * @param WikiImporter $reader
	 * @param array $logInfo Array of information
	 * @return bool|void True or no return value to continue, or false to stop further
	 *   processing of the tag
	 */
	public function onImportHandleLogItemXMLTag( $reader, $logInfo );
}
