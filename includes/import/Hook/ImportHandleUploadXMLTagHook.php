<?php

namespace MediaWiki\Hook;

use WikiImporter;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ImportHandleUploadXMLTag" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ImportHandleUploadXMLTagHook {
	/**
	 * This hook is called when parsing an XML tag in a file upload.
	 *
	 * @since 1.35
	 *
	 * @param WikiImporter $reader
	 * @param array $revisionInfo Array of information
	 * @return bool|void True or no return value to continue, or false to stop further
	 *   processing of the tag
	 */
	public function onImportHandleUploadXMLTag( $reader, $revisionInfo );
}
