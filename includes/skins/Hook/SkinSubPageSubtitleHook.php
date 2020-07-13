<?php

namespace MediaWiki\Hook;

use OutputPage;
use Skin;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SkinSubPageSubtitleHook {
	/**
	 * This hook is called at the beginning of Skin::subPageSubtitle().
	 *
	 * @since 1.35
	 *
	 * @param string &$subpages Subpage links HTML
	 * @param Skin $skin
	 * @param OutputPage $out
	 * @return bool|void True or no return value to continue or false to abort.
	 *   If true is returned, $subpages will be ignored and the rest of subPageSubtitle()
	 *   will run. If false is returned, $subpages will be used instead of the HTML
	 *   subPageSubtitle() generates.
	 */
	public function onSkinSubPageSubtitle( &$subpages, $skin, $out );
}
