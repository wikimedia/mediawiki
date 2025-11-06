<?php

namespace MediaWiki\Hook;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Specials\SpecialTrackingCategories;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialTrackingCategories::generateCatLink" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialTrackingCategories__generateCatLinkHook {
	/**
	 * This hook is called for each category link on Special:TrackingCategories.
	 *
	 * @since 1.35
	 *
	 * @param SpecialTrackingCategories $specialPage
	 * @param LinkTarget $catTitle The LinkTarget object of the linked category
	 * @param string &$html The Result html
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialTrackingCategories__generateCatLink( $specialPage,
		$catTitle, &$html
	);
}
