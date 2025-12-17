<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Base class to represent dependencies for LocalisationCache entries.
 *
 * @stable to extend
 * @ingroup Language
 */
abstract class CacheDependency {
	/**
	 * @param callback|null $callback Optional callback which will be called with a string
	 *    describing the reason why isExpired() is returning true.
	 * Returns true if the dependency is expired, false otherwise
	 *
	 * @return bool
	 */
	abstract public function isExpired( $callback = null );

	/**
	 * Hook to perform any expensive pre-serialize loading of dependency values.
	 * @stable to override
	 */
	public function loadDependencyValues() {
	}
}
