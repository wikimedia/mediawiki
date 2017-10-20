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
	 * @var array [ search => string|bool ]
	 */
	private static $cache = [];

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
	 *   0 => Command to run for version check, with $1 for the full executable name
	 *   1 => String to compare the output with
	 *
	 * If $versionInfo is not false, only executables with a version
	 * matching $versionInfo[1] will be returned.
	 * @return bool|string
	 */
	protected static function findExecutable( $path, $name, $versionInfo = false ) {
		$command = $path . DIRECTORY_SEPARATOR . $name;

		MediaWiki\suppressWarnings();
		$file_exists = is_executable( $command );
		MediaWiki\restoreWarnings();

		if ( $file_exists ) {
			if ( !$versionInfo ) {
				return $command;
			}

			$file = str_replace( '$1', $command, $versionInfo[0] );
			$output = Shell::command( $file )->includeStderr()->execute()->getStdout();
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
	 *   0 => Command to run for version check, with $1 for the full executable name
	 *   1 => String to compare the output with
	 *
	 * If $versionInfo is not false, only executables with a version
	 * matching $versionInfo[1] will be returned.
	 * @return bool|string
	 */
	public static function findInDefaultPaths( $names, $versionInfo = false ) {
		$paths = self::getPossibleBinPaths();
		foreach( (array)$names as $name ) {
			if ( $versionInfo === false && isset( self::$cache[$name] ) ) {
				$cached = self::$cache[$name];
				if ( $cached !== false ) {
					return $cached;
				} else {
					continue;
				}
			}
			foreach ( $paths as $path ) {
				$exe = self::findExecutable( $path, $name, $versionInfo );
				if ( $exe !== false ) {
					if ( $versionInfo === false ) {
						self::$cache[$name] = $exe;
					}
					return $exe;
				}
			}
			if ( $versionInfo === false ) {
				self::$cache[$name] = false;
			}
		}

		return false;
	}

}
