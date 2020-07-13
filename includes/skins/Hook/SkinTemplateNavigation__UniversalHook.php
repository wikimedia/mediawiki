<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use SkinTemplate;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SkinTemplateNavigation__UniversalHook {
	/**
	 * This hook is called on both content and special pages
	 * after variants have been added.
	 *
	 * @since 1.35
	 *
	 * @param SkinTemplate $sktemplate
	 * @param array &$links Structured navigation links. This is used to alter the navigation for
	 *   skins which use buildNavigationUrls such as Vector.
	 * @return void This hook must not abort, it must return no value
	 */
	public function onSkinTemplateNavigation__Universal( $sktemplate, &$links ) : void;
}
