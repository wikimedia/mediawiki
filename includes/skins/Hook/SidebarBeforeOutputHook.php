<?php

namespace MediaWiki\Hook;

use Skin;

/**
 * @stable to implement
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
	 * @param array &$sidebar Sidebar content. Modify $sidebar to add or modify sidebar portlets.
	 * @return void This hook must not abort; it must not return value.
	 */
	public function onSidebarBeforeOutput( $skin, &$sidebar ): void;
}
