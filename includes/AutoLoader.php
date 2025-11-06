<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

// NO_AUTOLOAD -- file scope code, can't load self

/**
 * Locations of core classes
 * Extension classes are specified with $wgAutoloadClasses
 */
require_once __DIR__ . '/../autoload.php';

/**
 * @defgroup Autoload Autoload
 */

/**
 * This initializes autoloading for MediaWiki core, extensions, and vendored libs.
 *
 * NOTE: This file sets up the PHP autoloader and so its stable contract is not this
 * class, but the act of initializing spl_autoload_register and vendor.
 * This file is widely referenced (akin to includes/Defines.php) and is therefore
 * not renamed or moved to /includes/autoload.
 *
 * @since 1.7
 * @ingroup Autoload
 */
class AutoLoader {

	/**
	 * A mapping of namespace => file path for MediaWiki core.
	 * The namespaces must follow the PSR-4 standard for autoloading.
	 *
	 * MediaWiki core does not use PSR-4 autoloading due to performance issues,
	 * but enforce the mapping to be maintained for future use.
	 * Instead using PSR-0, class map stored in autoload.php generated via script:
	 * php maintenance/run.php generateLocalAutoload
	 *
	 * @see <https://www.php-fig.org/psr/psr-4/>
	 * @see <https://techblog.wikimedia.org/2024/01/16/web-perf-hero-mate-szabo/>
	 * @internal Only public for usage in AutoloadGenerator/AutoLoaderTest
	 * @phpcs-require-sorted-array
	 */
	public const CORE_NAMESPACES = [
		'MediaWiki\\' => __DIR__ . '/',
		'MediaWiki\\Maintenance\\' => __DIR__ . '/../maintenance/includes/',
		'Wikimedia\\' => __DIR__ . '/libs/',
	];

	/**
	 * @var string[] Namespace (ends with \) => Path (ends with /)
	 */
	private static $psr4Namespaces = [];

	/**
	 * @var string[] Class => File
	 */
	private static $classFiles = [];

	/**
	 * Register a directory to load the classes of a given namespace from,
	 * per PSR4.
	 *
	 * @see <https://www.php-fig.org/psr/psr-4/>
	 * @since 1.39
	 * @param string[] $dirs a map of namespace (ends with \) to path (ends with /)
	 */
	public static function registerNamespaces( array $dirs ): void {
		self::$psr4Namespaces += $dirs;
	}

	/**
	 * Register a file to load the given class from.
	 * @since 1.39
	 *
	 * @param string[] $files a map of qualified class names to file names
	 */
	public static function registerClasses( array $files ): void {
		self::$classFiles += $files;
	}

	/**
	 * Load a file that declares classes, functions, or constants.
	 * The file will be loaded immediately using require_once in function scope.
	 *
	 * @note The file to be loaded MUST NOT set global variables or otherwise
	 * affect the global state. It MAY however use conditionals to determine
	 * what to declare and how, e.g. to provide polyfills.
	 *
	 * @note The file to be loaded MUST NOT assume that MediaWiki has been
	 * initialized. In particular, it MUST NOT access configuration variables
	 * or MediaWikiServices.
	 *
	 * @since 1.39
	 *
	 * @param string $file the path of the file to load.
	 */
	public static function loadFile( string $file ): void {
		require_once $file;
	}

	/**
	 * Batch version of loadFile()
	 *
	 * @see loadFile()
	 *
	 * @since 1.39
	 *
	 * @param string[] $files the paths of the files to load.
	 */
	public static function loadFiles( array $files ): void {
		foreach ( $files as $f ) {
			self::loadFile( $f );
		}
	}

	/**
	 * Find the file containing the given class.
	 *
	 * @param class-string $className Name of class we're looking for.
	 * @return string|null The path containing the class, not null if not found
	 */
	public static function find( $className ): ?string {
		global $wgAutoloadLocalClasses, $wgAutoloadClasses;

		// NOTE: $wgAutoloadClasses is supported for compatibility with old-style extension
		//       registration files.

		$filename = $wgAutoloadLocalClasses[$className] ??
			self::$classFiles[$className] ??
			$wgAutoloadClasses[$className] ??
			false;

		if ( !$filename && str_contains( $className, '\\' ) ) {
			// This class is namespaced, so look in the namespace map
			$prefix = $className;
			// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
			while ( ( $pos = strrpos( $prefix, '\\' ) ) !== false ) {
				// Check to see if this namespace prefix is in the map
				$prefix = substr( $className, 0, $pos + 1 );
				if ( isset( self::$psr4Namespaces[$prefix] ) ) {
					$relativeClass = substr( $className, $pos + 1 );
					// Build the expected filename, and see if it exists
					$file = self::$psr4Namespaces[$prefix] .
						strtr( $relativeClass, '\\', '/' ) .
						'.php';
					if ( is_file( $file ) ) {
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
			return null;
		}

		// Make an absolute path, this improves performance by avoiding some stat calls
		// Optimisation: use string offset access instead of substr
		if ( $filename[0] !== '/' && $filename[1] !== ':' ) {
			$filename = __DIR__ . '/../' . $filename;
		}

		return $filename;
	}

	/**
	 * autoload - take a class name and attempt to load it
	 *
	 * @param class-string $className Name of class we're looking for.
	 */
	public static function autoload( $className ) {
		$filename = self::find( $className );

		if ( $filename !== null ) {
			require_once $filename;
		}
	}

	///// Methods used during testing //////////////////////////////////////////////
	private static function assertTesting( string $method ): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( "$method is not supported outside phpunit tests!" );
		}
	}

	/**
	 * Returns a map of class names to file paths for testing.
	 * @note Will throw if called outside of phpunit tests!
	 * @return string[]
	 */
	public static function getClassFiles(): array {
		global $wgAutoloadLocalClasses, $wgAutoloadClasses;

		self::assertTesting( __METHOD__ );

		// NOTE: ensure the order of preference is the same as used by find().
		return array_merge(
			$wgAutoloadClasses,
			self::$classFiles,
			$wgAutoloadLocalClasses
		);
	}

	/**
	 * Returns a map of namespace names to directories, per PSR4.
	 * @note Will throw if called outside of phpunit tests!
	 * @return string[]
	 */
	public static function getNamespaceDirectories(): array {
		self::assertTesting( __METHOD__ );
		return self::$psr4Namespaces;
	}

	/**
	 * Returns an array representing the internal state of Autoloader,
	 * so it can be remembered and later restored during testing.
	 * @internal
	 * @note Will throw if called outside of phpunit tests!
	 * @return array
	 */
	public static function getState(): array {
		self::assertTesting( __METHOD__ );
		return [
			'classFiles' => self::$classFiles,
			'psr4Namespaces' => self::$psr4Namespaces,
		];
	}

	/**
	 * Returns an array representing the internal state of Autoloader,
	 * so it can be remembered and later restored during testing.
	 * @internal
	 * @note Will throw if called outside of phpunit tests!
	 *
	 * @param array $state A state array returned by getState().
	 */
	public static function restoreState( $state ): void {
		self::assertTesting( __METHOD__ );

		self::$classFiles = $state['classFiles'];
		self::$psr4Namespaces = $state['psr4Namespaces'];
	}

}

spl_autoload_register( [ 'AutoLoader', 'autoload' ] );

// Load composer's autoloader if present
if ( is_readable( __DIR__ . '/../vendor/autoload.php' ) ) {
	require_once __DIR__ . '/../vendor/autoload.php';
} elseif ( file_exists( __DIR__ . '/../vendor/autoload.php' ) ) {
	die( __DIR__ . '/../vendor/autoload.php exists but is not readable' );
}
