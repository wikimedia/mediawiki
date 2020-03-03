<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinSubPageSubtitleHook {
	/**
	 * At the beginning of Skin::subPageSubtitle().
	 * If false is returned $subpages will be used instead of the HTML
	 * subPageSubtitle() generates.
	 * If true is returned, $subpages will be ignored and the rest of
	 * subPageSubtitle() will run.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$subpages Subpage links HTML
	 * @param ?mixed $skin Skin object
	 * @param ?mixed $out OutputPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinSubPageSubtitle( &$subpages, $skin, $out );
}
