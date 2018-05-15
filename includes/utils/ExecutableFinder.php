<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@member.fsf.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
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

		Wikimedia\suppressWarnings();
		$file_exists = is_executable( $command );
		Wikimedia\restoreWarnings();

		if ( $file_exists ) {
			if ( !$versionInfo ) {
				return $command;
			}

			$output = Shell::command( $command, $versionInfo[0] )
				->includeStderr()->execute()->getStdout();
			if ( strstr( $output, $versionInfo[1] ) !== false ) {
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
