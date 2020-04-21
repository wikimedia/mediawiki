<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinAfterContentHook {
	/**
	 * Allows extensions to add text after the page content and
	 * article metadata. This hook should work in all skins. Set the &$data variable to
	 * the text you're going to add.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$data (string) Text to be printed out directly (without parsing)
	 * @param ?mixed $skin Skin object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinAfterContent( &$data, $skin );
}
