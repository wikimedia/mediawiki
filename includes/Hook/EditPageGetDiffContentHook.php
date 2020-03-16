<?php

namespace MediaWiki\Hook;

use EditPage;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPageGetDiffContentHook {
	/**
	 * Use this hook to modify the wikitext that will be used in "Show changes".
	 * Note that it is preferable to implement diff handling for different data types
	 * using the ContentHandler facility.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editPage
	 * @param string &$newtext Wikitext that will be used in place of "Show changes"
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageGetDiffContent( $editPage, &$newtext );
}
