<?php

/**
 * Callback wrapper that has an originating method
 *
 * @stable for implementation
 *
 * @since 1.28
 */
interface DeferrableCallback {
	/**
	 * @return string Originating method name
	 */
	public function getOrigin();
}
