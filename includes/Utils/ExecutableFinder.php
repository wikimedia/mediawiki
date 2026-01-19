<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@debian.org>
 *
 * @license GPL-2.0-or-later
 */

use MediaWiki\Shell\Shell;

/**
 * Utility class to find executables in likely places
 *
 * @since 1.31
 */
class ExecutableFinder {

	/**
	 * Get an array of likely places we can find executables. Check a bunch
	 * of known Unix-like defaults, as well as the PATH environment variable
	 * (which should maybe make it work for Windows?)
	 *
	 * @return array
	 */
	protected static function getPossibleBinPaths() {
		return array_unique( array_merge(
			[ '/usr/bin', '/bin', '/usr/local/bin', '/opt/csw/bin',
				'/usr/gnu/bin', '/usr/sfw/bin', '/sw/bin', '/opt/local/bin' ],
			explode( PATH_SEPARATOR, getenv( 'PATH' ) )
		) );
	}

	/**
	 * Search a path for any of the given executable names. Returns the
	 * executable name if found. Also checks the version string returned
	 * by each executable.
	 *
	 * Used only by environment checks.
	 *
	 * @param string $path Path to search
	 * @param string $name Executable name to look for
	 * @param array|bool $versionInfo False or array with two members:
	 *   0 => Parameter to pass to binary for version check (e.g. --version)
	 *   1 => String to compare the output with
	 *
	 * If $versionInfo is not false, only executables with a version
	 * matching $versionInfo[1] will be returned.
	 * @return bool|string
	 */
	protected static function findExecutable( $path, $name, $versionInfo = false ) {
		$command = $path . DIRECTORY_SEPARATOR . $name;

		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		$file_exists = @is_executable( $command );

		if ( $file_exists ) {
			if ( !$versionInfo ) {
				return $command;
			}

			$output = Shell::command( $command, $versionInfo[0] )
				->includeStderr()->execute()->getStdout();
			if ( str_contains( $output, $versionInfo[1] ) ) {
				return $command;
			}
		}

		return false;
	}

	/**
	 * Same as locateExecutable(), but checks in getPossibleBinPaths() by default
	 * @see locateExecutable()
	 * @param string|string[] $names Array of possible names.
	 * @param array|bool $versionInfo Default: false or array with two members:
	 *   0 => Parameter to run for version check, e.g. '--version'
	 *   1 => String to compare the output with
	 *
	 * If $versionInfo is not false, only executables with a version
	 * matching $versionInfo[1] will be returned.
	 * @return bool|string
	 */
	public static function findInDefaultPaths( $names, $versionInfo = false ) {
		if ( Shell::isDisabled() ) {
			// If we can't shell out, there's no point looking for executables
			return false;
		}

		$paths = self::getPossibleBinPaths();
		foreach ( (array)$names as $name ) {
			foreach ( $paths as $path ) {
				$exe = self::findExecutable( $path, $name, $versionInfo );
				if ( $exe !== false ) {
					return $exe;
				}
			}
		}

		return false;
	}

}
