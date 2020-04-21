<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinGetPoweredByHook {
	/**
	 * TODO
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$text additional 'powered by' icons in HTML. Note: Modern skin does not use
	 *   the MediaWiki icon but plain text instead.
	 * @param ?mixed $skin Skin object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinGetPoweredBy( &$text, $skin );
}
