<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinAfterBottomScriptsHook {
	/**
	 * At the end of Skin::bottomScripts().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $skin Skin object
	 * @param ?mixed &$text bottomScripts Text. Append to $text to add additional text/scripts after
	 *   the stock bottom scripts.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinAfterBottomScripts( $skin, &$text );
}
