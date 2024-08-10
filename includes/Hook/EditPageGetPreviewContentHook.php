<?php

namespace MediaWiki\Hook;

use MediaWiki\Content\Content;
use MediaWiki\EditPage\EditPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EditPageGetPreviewContent" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EditPageGetPreviewContentHook {
	/**
	 * Use this hook to modify the wikitext that will be previewed. Note that it is preferable
	 * to implement previews for different data types using the ContentHandler facility.
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editPage
	 * @param Content &$content Content object to be previewed (may be replaced by hook function)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageGetPreviewContent( $editPage, &$content );
}
