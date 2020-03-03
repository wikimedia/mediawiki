<?php

namespace MediaWiki\SpecialPage\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialPage_initListHook {
	/**
	 * Called when setting up SpecialPageFactory::$list, use
	 * this hook to remove a core special page or conditionally register special pages.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$list list (array) of core special pages
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialPage_initList( &$list );
}
