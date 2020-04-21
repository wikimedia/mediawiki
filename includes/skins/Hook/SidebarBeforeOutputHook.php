<?php

namespace MediaWiki\Hook;

use Skin;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SidebarBeforeOutputHook {
	/**
	 * Use this hook to edit the sidebar just before it is output by skins.
	 * Warning: This hook is run on each display. You should consider using
	 * 'SkinBuildSidebar', which is aggressively cached.
	 *
	 * @since 1.35
	 *
	 * @param Skin $skin
	 * @param string &$bar Sidebar content. Modify $bar to add or modify sidebar portlets.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSidebarBeforeOutput( $skin, &$bar );
}
