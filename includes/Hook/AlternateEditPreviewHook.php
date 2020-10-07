<?php

namespace MediaWiki\Hook;

use Content;
use EditPage;
use ParserOutput;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface AlternateEditPreviewHook {
	/**
	 * This hook is called before generating the preview of the page when editing
	 * ( EditPage::getPreviewText() ).
	 *
	 * @since 1.35
	 *
	 * @param EditPage $editPage
	 * @param Content &$content Content object for the text field from the edit page
	 * @param string &$previewHTML Text to be placed into the page for the preview
	 * @param ParserOutput &$parserOutput ParserOutput object for the preview
	 * @return bool|void True or no return value to continue, or false and set $previewHTML and
	 *   $parserOutput to output custom page preview HTML
	 */
	public function onAlternateEditPreview( $editPage, &$content, &$previewHTML,
		&$parserOutput
	);
}
