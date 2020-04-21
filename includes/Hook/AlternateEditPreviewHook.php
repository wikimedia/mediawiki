<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AlternateEditPreviewHook {
	/**
	 * Before generating the preview of the page when editing
	 * ( EditPage::getPreviewText() ).
	 * Return false and set $previewHTML and $parserOutput to output custom page
	 * preview HTML.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editPage the EditPage object
	 * @param ?mixed &$content the Content object for the text field from the edit page
	 * @param ?mixed &$previewHTML Text to be placed into the page for the preview
	 * @param ?mixed &$parserOutput the ParserOutput object for the preview
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAlternateEditPreview( $editPage, &$content, &$previewHTML,
		&$parserOutput
	);
}
