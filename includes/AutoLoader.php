<?php
/**
 * This defines autoloading handler for whole MediaWiki framework
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Locations of core classes
 * Extension classes are specified with $wgAutoloadClasses
 * This array is a global instead of a static member of AutoLoader to work around a bug in APC
 */
require_once __DIR__ . '/../autoload.php';

class AutoLoader {
	static protected $autoloadLocalClassesLower = null;

	/**
	 * autoload - take a class name and attempt to load it
	 *
	 * @param string $className Name of class we're looking for.
	 */
	static function autoload( $className ) {
		global $wgAutoloadClasses, $wgAutoloadLocalClasses,
			$wgAutoloadAttemptLowercase;

		// Workaround for PHP bug <https://bugs.php.net/bug.php?id=49143> (5.3.2. is broken, it's
		// fixed in 5.3.6). Strip leading backslashes from class names. When namespaces are used,
		// leading backslashes are used to indicate the top-level namespace, e.g. \foo\Bar. When
		// used like this in the code, the leading backslash isn't passed to the auto-loader
		// ($className would be 'foo\Bar'). However, if a class is accessed using a string instead
		// of a class literal (e.g. $class = '\foo\Bar'; new $class()), then some versions of PHP
		// do not strip the leading backlash in this case, causing autoloading to fail.
		$className = ltrim( $className, '\\' );

		$filename = false;

		if ( isset( $wgAutoloadLocalClasses[$className] ) ) {
			$filename = $wgAutoloadLocalClasses[$className];
		} elseif ( isset( $wgAutoloadClasses[$className] ) ) {
			$filename = $wgAutoloadClasses[$className];
		} elseif ( $wgAutoloadAttemptLowercase ) {
			/*
			 * Try a different capitalisation.
			 *
			 * PHP 4 objects are always serialized with the classname coerced to lowercase,
			 * and we are plagued with several legacy uses created by MediaWiki < 1.5, see
			 * https://wikitech.wikimedia.org/wiki/Text_storage_data
			 */
			$lowerClass = strtolower( $className );

			if ( self::$autoloadLocalClassesLower === null ) {
				self::$autoloadLocalClassesLower = array_change_key_case( $wgAutoloadLocalClasses, CASE_LOWER );
			}

			if ( isset( self::$autoloadLocalClassesLower[$lowerClass] ) ) {
				if ( function_exists( 'wfDebugLog' ) ) {
					wfDebugLog( 'autoloader', "Class {$className} was loaded using incorrect case" );
				}
				$filename = self::$autoloadLocalClassesLower[$lowerClass];
			}
		}

		if ( !$filename ) {
			// Class not found; let the next autoloader try to find it
			return;
		}

		// Make an absolute path, this improves performance by avoiding some stat calls
		if ( substr( $filename, 0, 1 ) != '/' && substr( $filename, 1, 1 ) != ':' ) {
			global $IP;
			$filename = "$IP/$filename";
		}

		require $filename;
	}

	/**
	 * Force a class to be run through the autoloader, helpful for things like
	 * Sanitizer that have define()s outside of their class definition. Of course
	 * this wouldn't be necessary if everything in MediaWiki was class-based. Sigh.
	 *
	 * @param string $class
	 * @return bool Return the results of class_exists() so we know if we were successful
	 */
	static function loadClass( $class ) {
		return class_exists( $class );
	}

	/**
	 * Method to clear the protected class property $autoloadLocalClassesLower.
	 * Used in tests.
	 */
	static function resetAutoloadLocalClassesLower() {
		self::$autoloadLocalClassesLower = null;
	}
}

spl_autoload_register( array( 'AutoLoader', 'autoload' ) );
