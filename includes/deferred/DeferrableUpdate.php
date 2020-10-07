<?php

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
