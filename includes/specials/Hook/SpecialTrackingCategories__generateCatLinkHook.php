<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialTrackingCategories__generateCatLinkHook {
	/**
	 * Called for each cat link on
	 * Special:TrackingCategories
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $specialPage The SpecialTrackingCategories object
	 * @param ?mixed $catTitle The Title object of the linked category
	 * @param ?mixed &$html The Result html
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialTrackingCategories__generateCatLink( $specialPage,
		$catTitle, &$html
	);
}
