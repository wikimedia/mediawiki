<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EnhancedChangesListModifyLineDataHook {
	/**
	 * to alter data used to build
	 * a grouped recent change inner line in EnhancedChangesList.
	 * Hook subscribers can return false to omit this line from recentchanges.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $changesList EnhancedChangesList object
	 * @param ?mixed &$data An array with all the components that will be joined in order to create
	 *   the line
	 * @param ?mixed $block An array of RecentChange objects in that block
	 * @param ?mixed $rc The RecentChange object for this line
	 * @param ?mixed &$classes An array of classes to change
	 * @param ?mixed &$attribs associative array of other HTML attributes for the <tr> element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEnhancedChangesListModifyLineData( $changesList, &$data,
		$block, $rc, &$classes, &$attribs
	);
}
