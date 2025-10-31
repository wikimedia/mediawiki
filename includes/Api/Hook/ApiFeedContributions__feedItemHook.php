<?php

namespace MediaWiki\Api\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use MediaWiki\Context\IContextSource;
use stdClass;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ApiFeedContributions::feedItem" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiFeedContributions__feedItemHook {
	/**
	 * Use this hook to convert the result of ContribsPager into a MediaWiki\Feed\FeedItem instance
	 * that ApiFeedContributions can consume. Implementors of this hook may cancel
	 * the hook to signal that the item is not viewable in the provided context.
	 *
	 * @param stdClass $row A row of data from ContribsPager. The set of data returned by
	 *   ContribsPager can be adjusted by handling the ContribsPager::reallyDoQuery
	 *   hook.
	 * @param IContextSource $context
	 * @param \MediaWiki\Feed\FeedItem|null &$feedItem Set this to a FeedItem instance if the callback can handle the
	 *   provided row. This is provided to the hook as a null, if it is non-null then
	 *   another callback has already handled the hook.
	 * @return bool|void True or no return value to continue or false to abort
	 * @since 1.35
	 */
	public function onApiFeedContributions__feedItem( $row, $context, &$feedItem );
}
