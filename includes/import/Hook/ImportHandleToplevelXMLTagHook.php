<?php

namespace MediaWiki\Hook;

use XMLReader;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImportHandleToplevelXMLTagHook {
	/**
	 * This hook is called when parsing a top level XML tag.
	 *
	 * @since 1.35
	 *
	 * @param XMLReader $reader
	 * @return bool|void True or no return value to continue, or false to stop further
	 *   processing of the tag
	 */
	public function onImportHandleToplevelXMLTag( $reader );
}
