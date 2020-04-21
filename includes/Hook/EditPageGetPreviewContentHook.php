<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EditPageGetPreviewContentHook {
	/**
	 * Allow modifying the wikitext that will be
	 * previewed. Note that it is preferable to implement previews for different data
	 * types using the ContentHandler facility.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editPage EditPage object
	 * @param ?mixed &$content Content object to be previewed (may be replaced by hook function)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPageGetPreviewContent( $editPage, &$content );
}
