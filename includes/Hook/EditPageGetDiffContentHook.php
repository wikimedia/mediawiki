<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPageGetDiffContentHook {
	/**
	 * Allow modifying the wikitext that will be used in
	 * "Show changes". Note that it is preferable to implement diff handling for
	 * different data types using the ContentHandler facility.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editPage EditPage object
	 * @param ?mixed &$newtext wikitext that will be used as "your version"
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageGetDiffContent( $editPage, &$newtext );
}
