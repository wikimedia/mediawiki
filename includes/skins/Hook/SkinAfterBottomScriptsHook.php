<?php

namespace MediaWiki\Hook;

use Skin;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SkinAfterBottomScriptsHook {
	/**
	 * This hook is called at the end of Skin::bottomScripts().
	 *
	 * @since 1.35
	 *
	 * @param Skin $skin
	 * @param string &$text BottomScripts text. Append to $text to add additional text/scripts after
	 *   the stock bottom scripts.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinAfterBottomScripts( $skin, &$text );
}
