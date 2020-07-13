<?php

namespace MediaWiki\Storage\Hook;

// phpcs:disable Generic.Files.LineLength -- Remove this after doc review
use DeferrableUpdate;
use MediaWiki\Revision\RenderedRevision;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface RevisionDataUpdatesHook {
	/**
	 * This hook is called when constructing a list of DeferrableUpdate to be
	 * executed to record secondary data about a revision.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title of the page the revision belongs to
	 * @param RenderedRevision $renderedRevision RenderedRevision object representing the new
	 *   revision and providing access to the RevisionRecord as well as ParserOutput of that revision
	 * @param DeferrableUpdate[] &$updates List of DeferrableUpdate that can be manipulated by
	 *   the hook handler
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRevisionDataUpdates( $title, $renderedRevision, &$updates );
}
