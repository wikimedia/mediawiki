<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinTemplateNavigation__UniversalHook {
	/**
	 * Called on both content and special pages
	 * after variants have been added.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $sktemplate SkinTemplate object
	 * @param ?mixed &$links Structured navigation links. This is used to alter the navigation for
	 *   skins which use buildNavigationUrls such as Vector.
	 * @return bool|void This hook must not abort, it must return true or null.
	 */
	public function onSkinTemplateNavigation__Universal( $sktemplate, &$links );
}
