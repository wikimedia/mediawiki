<?php

namespace MediaWiki\Import\Hook;

use MediaWiki\Import\WikiImporter;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ImportHandleContentXMLTag" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ImportHandleContentXMLTagHook {
	/**
	 * This hook is called when parsing a content XML tag.
	 *
	 * @since 1.36
	 *
	 * @param WikiImporter $reader
	 * @param array $contentInfo
	 * @return bool|void True or no return value to continue, or false to stop further
	 *   processing of the tag
	 */
	public function onImportHandleContentXMLTag( $reader, $contentInfo );
}

/** @deprecated class alias since 1.46 */
class_alias( ImportHandleContentXMLTagHook::class, 'MediaWiki\\Hook\\ImportHandleContentXMLTagHook' );
