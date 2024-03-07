<?php

namespace MediaWiki\Deferred;

/**
 * Interface that deferrable updates can implement to signal that updates can be combined.
 *
 * DeferredUpdates uses this to merge all pending updates of PHP class into a single update
 * by calling merge(). Note that upon merge(), the combined update goes to the back of the FIFO
 * queue so that such updates occur after related non-mergeable deferred updates. For example,
 * suppose updates that purge URL objects all use the same MergeableUpdate class, updates that
 * delete URL objects use a different class, and the calling pattern is:
 *   - a) DeferredUpdates::addUpdate( $purgeCdnUrlsA );
 *   - b) DeferredUpdates::addUpdate( $deleteContentUrlsB );
 *   - c) DeferredUpdates::addUpdate( $purgeCdnUrlsB )
 *
 * In this case, purges for urls A and B will all happen after the $deleteContentUrlsB update.
 *
 * @stable to implement
 *
 * @since 1.27
 */
interface MergeableUpdate extends DeferrableUpdate {
	/**
	 * Merge this enqueued update with a new MergeableUpdate of the same qualified class name
	 *
	 * @param MergeableUpdate $update The new update (having the same class)
	 */
	public function merge( MergeableUpdate $update );
}

/** @deprecated class alias since 1.42 */
class_alias( MergeableUpdate::class, 'MergeableUpdate' );
