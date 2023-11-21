<?php

namespace MediaWiki\Storage\Hook;

use MediaWiki\Deferred\DeferrableUpdate;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RevisionDataUpdates" to register handlers implementing this interface.
 *
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
