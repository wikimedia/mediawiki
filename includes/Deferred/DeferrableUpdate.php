<?php

namespace MediaWiki\Deferred;

/**
 * Interface that deferrable updates should implement. Basically required so we
 * can validate input on DeferredUpdates::addUpdate()
 *
 * @stable to implement
 *
 * @since 1.19
 */
interface DeferrableUpdate {
	/**
	 * Perform the actual work
	 */
	public function doUpdate();
}

/** @deprecated class alias since 1.42 */
class_alias( DeferrableUpdate::class, 'DeferrableUpdate' );
