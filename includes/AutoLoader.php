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
	protected static $autoloadLocalClassesLower = null;

	/**
	 * @internal Only public for ExtensionRegistry
	 * @var string[] Namespace (ends with \) => Path (ends with /)
	 */
	public static $psr4Namespaces = [];

	/**
	 * autoload - take a class name and attempt to load it
	 *
	 * @param string $className Name of class we're looking for.
	 */
	public static function autoload( $className ) {
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
			while ( ( $pos = strrpos( $prefix, '\\' ) ) !== false ) {
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
	public static function resetAutoloadLocalClassesLower() {
		self::$autoloadLocalClassesLower = null;
	}

	/**
	 * Get a mapping of namespace => file path
	 * The namespaces should follow the PSR-4 standard for autoloading
	 *
	 * @see <https://www.php-fig.org/psr/psr-4/>
	 * @internal Only public for usage in AutoloadGenerator
	 * @codeCoverageIgnore
	 * @since 1.31
	 * @return string[]
	 */
	public static function getAutoloadNamespaces() {
		return [
			'MediaWiki\\' => __DIR__ . '/',
			'MediaWiki\\Api\\' => __DIR__ . '/api/',
			'MediaWiki\\Auth\\' => __DIR__ . '/auth/',
			'MediaWiki\\Block\\' => __DIR__ . '/block/',
			'MediaWiki\\Cache\\' => __DIR__ . '/cache/',
			'MediaWiki\\ChangeTags\\' => __DIR__ . '/changetags/',
			'MediaWiki\\Config\\' => __DIR__ . '/config/',
			'MediaWiki\\Content\\' => __DIR__ . '/content/',
			'MediaWiki\\DB\\' => __DIR__ . '/db/',
			'MediaWiki\\Diff\\' => __DIR__ . '/diff/',
			'MediaWiki\\Edit\\' => __DIR__ . '/edit/',
			'MediaWiki\\EditPage\\' => __DIR__ . '/editpage/',
			'MediaWiki\\FileBackend\\LockManager\\' => __DIR__ . '/filebackend/lockmanager/',
			'MediaWiki\\Http\\' => __DIR__ . '/http/',
			'MediaWiki\\Installer\\' => __DIR__ . '/installer/',
			'MediaWiki\\Interwiki\\' => __DIR__ . '/interwiki/',
			'MediaWiki\\Linker\\' => __DIR__ . '/linker/',
			'MediaWiki\\Logger\\' => __DIR__ . '/debug/logger/',
			'MediaWiki\\Logger\Monolog\\' => __DIR__ . '/debug/logger/monolog/',
			'MediaWiki\\Page\\' => __DIR__ . '/page/',
			'MediaWiki\\Preferences\\' => __DIR__ . '/preferences/',
			'MediaWiki\\ResourceLoader\\' => __DIR__ . '/resourceloader/',
			'MediaWiki\\Search\\' => __DIR__ . '/search/',
			'MediaWiki\\Search\\SearchWidgets\\' => __DIR__ . '/search/searchwidgets/',
			'MediaWiki\\Session\\' => __DIR__ . '/session/',
			'MediaWiki\\Shell\\' => __DIR__ . '/shell/',
			'MediaWiki\\Site\\' => __DIR__ . '/site/',
			'MediaWiki\\Sparql\\' => __DIR__ . '/sparql/',
			'MediaWiki\\SpecialPage\\' => __DIR__ . '/specialpage/',
			'MediaWiki\\Tidy\\' => __DIR__ . '/tidy/',
			'MediaWiki\\User\\' => __DIR__ . '/user/',
			'MediaWiki\\Widget\\' => __DIR__ . '/widget/',
			'Wikimedia\\' => __DIR__ . '/libs/',
			'Wikimedia\\Http\\' => __DIR__ . '/libs/http/',
			'Wikimedia\\UUID\\' => __DIR__ . '/libs/uuid/',
		];
	}
}

AutoLoader::$psr4Namespaces = AutoLoader::getAutoloadNamespaces();
spl_autoload_register( [ 'AutoLoader', 'autoload' ] );
