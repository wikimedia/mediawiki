<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialTrackingCategories__preprocessHook {
	/**
	 * Called after LinkBatch on
	 * Special:TrackingCategories
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $specialPage The SpecialTrackingCategories object
	 * @param ?mixed $trackingCategories Array of data from Special:TrackingCategories with msg and
	 *   cats
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialTrackingCategories__preprocess( $specialPage,
		$trackingCategories
	);
}
