<?php

namespace MediaWiki\Storage\Hook;

// phpcs:disable Generic.Files.LineLength -- Remove this after doc review
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface RevisionDataUpdatesHook {
	/**
	 * Called when constructing a list of DeferrableUpdate to be
	 * executed to record secondary data about a revision.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title The Title of the page the revision  belongs to
	 * @param ?mixed $renderedRevision a RenderedRevision object representing the new revision and providing access
	 *   to the RevisionRecord as well as ParserOutput of that revision.
	 * @param ?mixed &$updates A list of DeferrableUpdate that can be manipulated by the hook handler.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRevisionDataUpdates( $title, $renderedRevision, &$updates );
}
