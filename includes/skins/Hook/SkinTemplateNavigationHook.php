<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinTemplateNavigationHook {
	/**
	 * Called on content pages after the tabs have been
	 * added, but before variants have been added.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $sktemplate SkinTemplate object
	 * @param ?mixed &$links Structured navigation links. This is used to alter the navigation for
	 *   skins which use buildNavigationUrls such as Vector.
	 * @return bool|void This hook must not abort, it must return true or null.
	 */
	public function onSkinTemplateNavigation( $sktemplate, &$links );
}
