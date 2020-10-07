<?php

namespace MediaWiki\Hook;

use Skin;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SkinPreloadExistenceHook {
	/**
	 * Use this hook to supply titles that should be added to link existence
	 * cache before the page is rendered.
	 *
	 * @since 1.35
	 *
	 * @param Title[] &$titles Array of Title objects
	 * @param Skin $skin
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinPreloadExistence( &$titles, $skin );
}
