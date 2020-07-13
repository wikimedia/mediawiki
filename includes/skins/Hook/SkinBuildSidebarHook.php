<?php

namespace MediaWiki\Hook;

use Skin;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SkinBuildSidebarHook {
	/**
	 * This hook is called at the end of Skin::buildSidebar().
	 *
	 * @since 1.35
	 *
	 * @param Skin $skin
	 * @param array &$bar Sidebar contents. Modify $bar to add or modify sidebar portlets.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinBuildSidebar( $skin, &$bar );
}
