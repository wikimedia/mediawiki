<?php
/**
 * Invocation matcher which checks if a method has been invoked at most a
 * certain amount of times.
 * If the number of invocations exceeds the value it will immediately throw an
 * exception,
 *
 * If we ever upgrade PHPUnit, we can get rid of this in favor of
 * PHPUnit_Framework_MockObject_Matcher_InvokedAtMostCount.
 *
 * @since 1.27
 */
class InvokedAtMost extends PHPUnit_Framework_MockObject_Matcher_InvokedCount {
	/**
	 * @return string
	 */
	public function toString() {
		return 'invoked at most ' . $this->expectedCount . ' time(s)';
	}

	public function verify() {
		// Ignore the base-class version that checks "at least X"
	}
}
