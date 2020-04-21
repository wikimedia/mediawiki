<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SidebarBeforeOutputHook {
	/**
	 * Allows to edit sidebar just before it is output by skins.
	 * Warning: This hook is run on each display. You should consider to use
	 * 'SkinBuildSidebar' that is aggressively cached.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $skin Skin object
	 * @param ?mixed &$bar Sidebar content
	 *   Modify $bar to add or modify sidebar portlets.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSidebarBeforeOutput( $skin, &$bar );
}
