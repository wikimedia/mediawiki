<?php

namespace MediaWiki\Hook;

use SpecialTrackingCategories;
use Title;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialTrackingCategories__generateCatLinkHook {
	/**
	 * This hook is called for each cat link on Special:TrackingCategories
	 *
	 * @since 1.35
	 *
	 * @param SpecialTrackingCategories $specialPage The SpecialTrackingCategories object
	 * @param Title $catTitle The Title object of the linked category
	 * @param string &$html The Result html
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialTrackingCategories__generateCatLink( $specialPage,
		$catTitle, &$html
	);
}
