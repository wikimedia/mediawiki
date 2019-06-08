<?php

/**
 * Interface that deferrable updates can implement to signal that updates can be combined.
 *
 * DeferredUpdates uses this to merge all pending updates of PHP class into a single update
 * by calling merge(). Note that upon merge(), the combined update goes to the back of the FIFO
 * queue so that such updates occur after related non-mergeable deferred updates. For example,
 * suppose updates that purge URLs can be merged, and the calling pattern is:
 *   - a) DeferredUpdates::addUpdate( $purgeCdnUrlsA );
 *   - b) DeferredUpdates::addUpdate( $deleteContentUrlsB );
 *   - c) DeferredUpdates::addUpdate( $purgeCdnUrlsB )
 *
 * The purges for urls A and B will all happen after the $deleteContentUrlsB update.
 *
 * @since 1.27
 */
interface MergeableUpdate extends DeferrableUpdate {
	/**
	 * Merge this update with $update
	 *
	 * @param MergeableUpdate $update Update of the same class type
	 */
	function merge( MergeableUpdate $update );
}
