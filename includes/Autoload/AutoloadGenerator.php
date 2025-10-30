<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Json\FormatJson;

/**
 * Scan given directories and files and create an autoload class map.
 *
 * Accepts a list of files and directories to search for
 * php files and generates $wgAutoloadLocalClasses or $wgAutoloadClasses
 * lines for all detected classes. These lines are written out
 * to an autoload.php file in the projects provided basedir.
 *
 * Usage:
 *
 *     $gen = new AutoloadGenerator( __DIR__ );
 *     $gen->readDir( __DIR__ . '/includes' );
 *     $gen->readFile( __DIR__ . '/foo.php' )
 *     $gen->getAutoload();
 *
 * @see \MediaWiki\Maintenance\Maintenance\GenerateAutoload
 * @since 1.25
 * @ingroup Autoload
 */
class AutoloadGenerator {
	private const FILETYPE_JSON = 'json';
	private const FILETYPE_PHP = 'php';

	/**
	 * @var string Root path of the project being scanned for classes
	 */
	protected $basepath;

	/**
	 * @var ClassCollector Helper class extracts class names from php files
	 */
	protected $collector;

	/**
	 * @var array Map of file shortpath to list of FQCN detected within file
	 */
	protected $classes = [];

	/**
	 * @var string The global variable to write output to
	 */
	protected $variableName = 'wgAutoloadClasses';

	/**
	 * @var array Map of FQCN to relative path(from self::$basepath)
	 */
	protected $overrides = [];

	/**
	 * Directories that should be excluded
	 *
	 * @var string[]
	 */
	protected $excludePaths = [];

	/**
	 * Configured PSR4 namespaces
	 *
	 * @var string[] namespace => path
	 */
	protected $psr4Namespaces = [];

	/**
	 * @param string $basepath Root path of the project being scanned for classes
	 * @param array|string $flags
	 *
	 *  local - If this flag is set $wgAutoloadLocalClasses will be build instead
	 *          of $wgAutoloadClasses
	 */
	public function __construct( $basepath, $flags = [] ) {
		if ( !is_array( $flags ) ) {
			$flags = [ $flags ];
		}
		$this->basepath = self::normalizePathSeparator( realpath( $basepath ) );
		$this->collector = new ClassCollector;
		if ( in_array( 'local', $flags ) ) {
			$this->variableName = 'wgAutoloadLocalClasses';
		}
	}

	/**
	 * Directories that should be excluded
	 *
	 * @since 1.31
	 * @param string[] $paths
	 */
	public function setExcludePaths( array $paths ) {
		foreach ( $paths as $path ) {
			$this->excludePaths[] = self::normalizePathSeparator( $path );
		}
	}

	/**
	 * Unlike self::setExcludePaths(), this will only skip outputting the
	 * autoloader entry when the namespace matches the path.
	 *
	 * @since 1.32
	 * @deprecated since 1.40 - PSR-4 classes are now included in the generated classmap, hard-deprecated since 1.45
	 * @param string[] $namespaces Associative array mapping namespace to path
	 */
	public function setPsr4Namespaces( array $namespaces ) {
		wfDeprecated( __METHOD__, '1.40' );
		foreach ( $namespaces as $ns => $path ) {
			$ns = rtrim( $ns, '\\' ) . '\\';
			$this->psr4Namespaces[$ns] = rtrim( self::normalizePathSeparator( $path ), '/' );
		}
	}

	/**
	 * Whether the file should be excluded
	 *
	 * @param string $path File path
	 * @return bool
	 */
	private function shouldExclude( $path ) {
		foreach ( $this->excludePaths as $dir ) {
			if ( str_starts_with( $path, $dir ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Force a class to be autoloaded from a specific path, regardless of where
	 * or if it was detected.
	 *
	 * @param string $fqcn FQCN to force the location of
	 * @param string $inputPath Full path to the file containing the class
	 * @throws InvalidArgumentException
	 */
	public function forceClassPath( $fqcn, $inputPath ) {
		$path = self::normalizePathSeparator( realpath( $inputPath ) );
		if ( !$path ) {
			throw new InvalidArgumentException( "Invalid path: $inputPath" );
		}
		if ( !str_starts_with( $path, $this->basepath ) ) {
			throw new InvalidArgumentException( "Path is not within basepath: $inputPath" );
		}
		$shortpath = substr( $path, strlen( $this->basepath ) );
		$this->overrides[$fqcn] = $shortpath;
	}

	/**
	 * @param string $inputPath Path to a php file to find classes within
	 * @throws InvalidArgumentException
	 */
	public function readFile( $inputPath ) {
		// NOTE: do NOT expand $inputPath using realpath(). It is perfectly
		// reasonable for LocalSettings.php and similar files to be symlinks
		// to files that are outside of $this->basepath.
		$inputPath = self::normalizePathSeparator( $inputPath );
		$len = strlen( $this->basepath );
		if ( !str_starts_with( $inputPath, $this->basepath ) ) {
			throw new InvalidArgumentException( "Path is not within basepath: $inputPath" );
		}
		if ( $this->shouldExclude( $inputPath ) ) {
			return;
		}
		$fileContents = file_get_contents( $inputPath );

		// Skip files that declare themselves excluded
		if ( preg_match( '!^// *NO_AUTOLOAD!m', $fileContents ) ) {
			return;
		}
		// Skip files that use CommandLineInc since these execute file-scope
		// code when included
		if ( preg_match(
			'/(require|require_once)[ (].*(CommandLineInc.php|commandLine.inc)/',
			$fileContents )
		) {
			return;
		}

		$result = $this->collector->getClasses( $fileContents );

		if ( $result ) {
			$shortpath = substr( $inputPath, $len );
			$this->classes[$shortpath] = $result;
		}
	}

	/**
	 * @param string $dir Path to a directory to recursively search for php files
	 */
	public function readDir( $dir ) {
		$it = new RecursiveDirectoryIterator(
			self::normalizePathSeparator( realpath( $dir ) ) );
		$it = new RecursiveIteratorIterator( $it );

		foreach ( $it as $path => $file ) {
			if ( pathinfo( $path, PATHINFO_EXTENSION ) === 'php' ) {
				$this->readFile( $path );
			}
		}
	}

	/**
	 * Updates the AutoloadClasses field at the given
	 * filename.
	 *
	 * @param string $filename Filename of JSON
	 *  extension/skin registration file
	 * @return string Updated Json of the file given as the $filename parameter
	 */
	protected function generateJsonAutoload( $filename ) {
		$key = 'AutoloadClasses';
		$json = FormatJson::decode( file_get_contents( $filename ), true );
		unset( $json[$key] );
		// Inverting the key-value pairs so that they become of the
		// format class-name : path when they get converted into json.
		foreach ( $this->classes as $path => $contained ) {
			foreach ( $contained as $fqcn ) {
				// Using substr to remove the leading '/'
				$json[$key][$fqcn] = substr( $path, 1 );
			}
		}
		foreach ( $this->overrides as $path => $fqcn ) {
			// Using substr to remove the leading '/'
			$json[$key][$fqcn] = substr( $path, 1 );
		}

		// Sorting the list of autoload classes.
		ksort( $json[$key] );

		// Return the whole JSON file
		return FormatJson::encode( $json, "\t", FormatJson::ALL_OK ) . "\n";
	}

	/**
	 * Generates a PHP file setting up autoload information.
	 *
	 * @param string $commandName Command name to include in comment
	 * @param string $filename of PHP file to put autoload information in.
	 * @return string
	 */
	protected function generatePHPAutoload( $commandName, $filename ) {
		// No existing JSON file found; update/generate PHP file
		$content = [];

		// We need to generate a line each rather than exporting the
		// full array so __DIR__ can be prepended to all the paths
		$format = "%s => __DIR__ . %s,";
		foreach ( $this->classes as $path => $contained ) {
			$exportedPath = var_export( $path, true );
			foreach ( $contained as $fqcn ) {
				$content[$fqcn] = sprintf(
					$format,
					var_export( $fqcn, true ),
					$exportedPath
				);
			}
		}

		foreach ( $this->overrides as $fqcn => $path ) {
			$content[$fqcn] = sprintf(
				$format,
				var_export( $fqcn, true ),
				var_export( $path, true )
			);
		}

		// sort for stable output
		ksort( $content );

		// extensions using this generator are appending to the existing
		// autoload.
		if ( $this->variableName === 'wgAutoloadClasses' ) {
			$op = '+=';
		} else {
			$op = '=';
		}

		$output = implode( "\n\t", $content );
		return <<<EOD
<?php
// This file is generated by $commandName, do not adjust manually
// phpcs:disable Generic.Files.LineLength
global \${$this->variableName};

\${$this->variableName} {$op} [
	{$output}
];

EOD;
	}

	/**
	 * Returns all known classes as a string, which can be used to put into a target
	 * file (e.g. extension.json, skin.json or autoload.php)
	 *
	 * @param string $commandName Value used in file comment to direct
	 *  developers towards the appropriate way to update the autoload.
	 * @return string
	 */
	public function getAutoload( $commandName = 'AutoloadGenerator' ) {
		// We need to check whether an extension.json or skin.json exists or not, and
		// incase it doesn't, update the autoload.php file.

		$fileinfo = $this->getTargetFileinfo();

		if ( $fileinfo['type'] === self::FILETYPE_JSON ) {
			return $this->generateJsonAutoload( $fileinfo['filename'] );
		}

		return $this->generatePHPAutoload( $commandName, $fileinfo['filename'] );
	}

	/**
	 * Returns the filename of the extension.json of skin.json, if there's any, or
	 * otherwise the path to the autoload.php file in an array as the "filename"
	 * key and with the type (AutoloadGenerator::FILETYPE_JSON or AutoloadGenerator::FILETYPE_PHP)
	 * of the file as the "type" key.
	 *
	 * @return array
	 */
	public function getTargetFileinfo() {
		if ( file_exists( $this->basepath . '/extension.json' ) ) {
			return [
				'filename' => $this->basepath . '/extension.json',
				'type' => self::FILETYPE_JSON
			];
		}
		if ( file_exists( $this->basepath . '/skin.json' ) ) {
			return [
				'filename' => $this->basepath . '/skin.json',
				'type' => self::FILETYPE_JSON
			];
		}

		return [
			'filename' => $this->basepath . '/autoload.php',
			'type' => self::FILETYPE_PHP
		];
	}

	/**
	 * Ensure that Unix-style path separators ("/") are used in the path.
	 *
	 * @param string $path
	 * @return string
	 */
	protected static function normalizePathSeparator( $path ) {
		return str_replace( '\\', '/', $path );
	}

	/**
	 * Initialize the source files and directories which are used for the MediaWiki default
	 * autoloader in {mw-base-dir}/autoload.php including:
	 *  * includes/
	 *  * languages/
	 *  * maintenance/
	 *  * mw-config/
	 *  * any `*.php` file in the base directory
	 */
	public function initMediaWikiDefault() {
		foreach ( [ 'includes', 'languages', 'maintenance', 'mw-config' ] as $dir ) {
			$this->readDir( $this->basepath . '/' . $dir );
		}
		foreach ( glob( $this->basepath . '/*.php' ) as $file ) {
			$this->readFile( $file );
		}
	}
}
