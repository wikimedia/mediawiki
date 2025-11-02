<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Preferences;

/**
 * Base interface for user preference filters that work as a middleware between
 * storage and interface.
 */
interface Filter {
	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function filterForForm( $value );

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function filterFromForm( $value );
}
