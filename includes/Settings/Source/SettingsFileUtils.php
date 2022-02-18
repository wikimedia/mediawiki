<?php

namespace MediaWiki\Settings\Source;

/**
 * A collection of static utility methods for use with
 * settings files.
 *
 * @since 1.38
 * @internal since the behavior may change to accommodate more types of source locations.
 */
class SettingsFileUtils {

	/**
	 * Resolves a relative settings source location. This method will attempt to interpret
	 * $path as relative to $base if possible. This behaves similar to relative URLs are
	 * made absolute using a base URL.
	 *
	 * The current implementation is based on file paths, but this may be expanded in the future
	 * to support other kinds of locations.
	 *
	 * @param string $path
	 * @param string $base
	 *
	 * @return string
	 */
	public static function resolveRelativeLocation( string $path, string $base ): string {
		// Qualify the path if it isn't already absolute
		if ( !preg_match( '!^[a-zA-Z]:\\\\!', $path ) && $path[0] != DIRECTORY_SEPARATOR ) {
			$path = $base . DIRECTORY_SEPARATOR . $path;
		}

		return $path;
	}
}
