<?php

namespace MediaWiki\Hook;

use WikiImporter;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ImportHandleToplevelXMLTagHook {
	/**
	 * This hook is called when parsing a top level XML tag.
	 *
	 * @since 1.35
	 *
	 * @param WikiImporter $reader
	 * @return bool|void True or no return value to continue, or false to stop further
	 *   processing of the tag
	 */
	public function onImportHandleToplevelXMLTag( $reader );
}
