<?php

/**
 * Callback wrapper that has an originating method
 *
 * @since 1.28
 */
interface DeferrableCallback {
	/**
	 * @return string Originating method name
	 */
	function getOrigin();
}
