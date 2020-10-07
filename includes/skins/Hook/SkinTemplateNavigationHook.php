<?php

namespace MediaWiki\Hook;

use SkinTemplate;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SkinTemplateNavigationHook {
	/**
	 * This hook is called on content pages after the tabs have been
	 * added, but before variants have been added.
	 *
	 * @since 1.35
	 *
	 * @param SkinTemplate $sktemplate
	 * @param array &$links Structured navigation links. This is used to alter the navigation for
	 *   skins which use buildNavigationUrls such as Vector.
	 * @return void This hook must not abort, it must return no value
	 */
	public function onSkinTemplateNavigation( $sktemplate, &$links ) : void;
}
