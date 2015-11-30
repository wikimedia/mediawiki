<?php

/**
 * Interface that deferrable updates can implement. DeferredUpdates uses this to merge
 * all pending updates of PHP class into a single update by calling merge().
 *
 * @since 1.27
 */
interface MergeableUpdate {
	/**
	 * Merge this update with $update
	 *
	 * @param MergeableUpdate $update Update of the same class type
	 */
	function merge( MergeableUpdate $update );
}
