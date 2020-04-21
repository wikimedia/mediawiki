<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinBuildSidebarHook {
	/**
	 * At the end of Skin::buildSidebar().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $skin Skin object
	 * @param ?mixed &$bar Sidebar contents
	 *   Modify $bar to add or modify sidebar portlets.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinBuildSidebar( $skin, &$bar );
}
