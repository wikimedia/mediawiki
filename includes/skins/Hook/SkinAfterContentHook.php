<?php

namespace MediaWiki\Hook;

use Skin;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SkinAfterContentHook {
	/**
	 * Use this hook to add text after the page content and
	 * article metadata. This hook should work in all skins. Set the &$data variable to
	 * the text you're going to add.
	 *
	 * @since 1.35
	 *
	 * @param string &$data Text to be printed out directly (without parsing)
	 * @param Skin $skin
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinAfterContent( &$data, $skin );
}
