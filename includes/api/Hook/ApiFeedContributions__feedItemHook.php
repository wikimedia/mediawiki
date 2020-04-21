<?php

namespace MediaWiki\Api\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiFeedContributions__feedItemHook {
	/**
	 * Called to convert the result of ContribsPager
	 * into a FeedItem instance that ApiFeedContributions can consume. Implementors of
	 * this hook may cancel the hook to signal that the item is not viewable in the
	 * provided context.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $row A row of data from ContribsPager.  The set of data returned by
	 *   ContribsPager can be adjusted by handling the ContribsPager::reallyDoQuery
	 *   hook.
	 * @param ?mixed $context An IContextSource implementation.
	 * @param ?mixed &$feedItem Set this to a FeedItem instance if the callback can handle the
	 *   provided row. This is provided to the hook as a null, if it is non null then
	 *   another callback has already handled the hook.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiFeedContributions__feedItem( $row, $context, &$feedItem );
}
