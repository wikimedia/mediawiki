<?php

namespace MediaWiki\Hook;

use Language;
use Skin;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SkinEditSectionLinksHook {
	/**
	 * Use this hook to modify section edit links.
	 *
	 * @since 1.35
	 *
	 * @param Skin $skin Skin object rendering the UI
	 * @param Title $title Title being linked to (may not be the same as the page title,
	 *   if the section is included from a template)
	 * @param string $section Designation of the section being pointed to, to be included in
	 *   the link, like "&section=$section"
	 * @param string $tooltip Default tooltip. Escape before using.
	 *   By default, this is wrapped in the 'editsectionhint' message.
	 * @param array &$result Array containing all link detail arrays. Each link detail array should
	 *   contain the following keys:
	 *     - `targetTitle`: Target Title object
	 *     - `text`: String for the text
	 *     - `attribs`: Array of attributes
	 *     - `query`: Array of query parameters to add to the URL
	 * @param Language $lang Language to use for the link in the wfMessage function
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinEditSectionLinks( $skin, $title, $section, $tooltip,
		&$result, $lang
	);
}
