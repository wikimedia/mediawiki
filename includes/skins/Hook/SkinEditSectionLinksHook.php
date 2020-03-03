<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinEditSectionLinksHook {
	/**
	 * Modify the section edit links
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $skin Skin object rendering the UI
	 * @param ?mixed $title Title object for the title being linked to (may not be the same as
	 *   the page title, if the section is included from a template)
	 * @param ?mixed $section The designation of the section being pointed to, to be included in
	 *   the link, like "&section=$section"
	 * @param ?mixed $tooltip The default tooltip.  Escape before using.
	 *   By default, this is wrapped in the 'editsectionhint' message.
	 * @param ?mixed &$result Array containing all link detail arrays. Each link detail array should
	 *   contain the following keys:
	 *     - targetTitle - Target Title object
	 *     - text - String for the text
	 *     - attribs - Array of attributes
	 *     - query - Array of query parameters to add to the URL
	 * @param ?mixed $lang The language code to use for the link in the wfMessage function
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinEditSectionLinks( $skin, $title, $section, $tooltip,
		&$result, $lang
	);
}
