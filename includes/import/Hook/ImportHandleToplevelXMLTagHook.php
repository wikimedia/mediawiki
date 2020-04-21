<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImportHandleToplevelXMLTagHook {
	/**
	 * When parsing a top level XML tag.
	 * Return false to stop further processing of the tag
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $reader XMLReader object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImportHandleToplevelXMLTag( $reader );
}
