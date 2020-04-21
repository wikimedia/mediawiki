<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinPreloadExistenceHook {
	/**
	 * Supply titles that should be added to link existence
	 * cache before the page is rendered.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$titles Array of Title objects
	 * @param ?mixed $skin Skin object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinPreloadExistence( &$titles, $skin );
}
