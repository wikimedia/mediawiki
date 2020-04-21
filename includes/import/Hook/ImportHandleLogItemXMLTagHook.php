<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImportHandleLogItemXMLTagHook {
	/**
	 * When parsing a XML tag in a log item.
	 * Return false to stop further processing of the tag
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $reader XMLReader object
	 * @param ?mixed $logInfo Array of information
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImportHandleLogItemXMLTag( $reader, $logInfo );
}
