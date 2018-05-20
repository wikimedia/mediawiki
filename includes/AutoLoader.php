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
	 * @private Only public for ExtensionRegistry
	 * @var string[] Namespace (ends with \) => Path (ends with /)
	 */
	static public $psr4Namespaces = [];

	/**
	 * autoload - take a class name and attempt to load it
	 *
	 * @param string $className Name of class we're looking for.
	 */
	static function autoload( $className ) {
		global $wgAutoloadClasses, $wgAutoloadLocalClasses,
			$wgAutoloadAttemptLowercase;

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

		if ( !$filename && strpos( $className, '\\' ) !== false ) {
			// This class is namespaced, so try looking at the namespace map
			$prefix = $className;
			while ( false !== $pos = strrpos( $prefix, '\\' ) ) {
				// Check to see if this namespace prefix is in the map
				$prefix = substr( $className, 0, $pos + 1 );
				if ( isset( self::$psr4Namespaces[$prefix] ) ) {
					$relativeClass = substr( $className, $pos + 1 );
					// Build the expected filename, and see if it exists
					$file = self::$psr4Namespaces[$prefix] . '/' .
						str_replace( '\\', '/', $relativeClass ) . '.php';
					if ( file_exists( $file ) ) {
						$filename = $file;
						break;
					}
				}

				// Remove trailing separator for next iteration
				$prefix = rtrim( $prefix, '\\' );
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
	 * Method to clear the protected class property $autoloadLocalClassesLower.
	 * Used in tests.
	 */
	static function resetAutoloadLocalClassesLower() {
		self::$autoloadLocalClassesLower = null;
	}

	/**
	 * Get a mapping of namespace => file path
	 * The namespaces should follow the PSR-4 standard for autoloading
	 *
	 * @see <http://www.php-fig.org/psr/psr-4/>
	 * @private Only public for usage in AutoloadGenerator
	 * @since 1.31
	 * @return string[]
	 */
	public static function getAutoloadNamespaces() {
		return [
			'MediaWiki\\Linker\\' => __DIR__ .'/linker/'
		];
	}
}

AutoLoader::$psr4Namespaces = AutoLoader::getAutoloadNamespaces();
spl_autoload_register( [ 'AutoLoader', 'autoload' ] );
