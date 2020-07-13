<?php

namespace MediaWiki\SpecialPage\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialPage_initListHook {
	/**
	 * This hook is called when setting up SpecialPageFactory::$list. Use
	 * this hook to remove a core special page or conditionally register special pages.
	 *
	 * @since 1.35
	 *
	 * @param array &$list List of core special pages
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialPage_initList( &$list );
}
