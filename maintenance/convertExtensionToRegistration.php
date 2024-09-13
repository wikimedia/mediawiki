<?php

use MediaWiki\Json\FormatJson;
use MediaWiki\Registration\ExtensionProcessor;
use MediaWiki\Registration\ExtensionRegistry;
use Wikimedia\Composer\ComposerJson;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

class ConvertExtensionToRegistration extends Maintenance {

	private const CUSTOM_GLOBALS = [
		'MessagesDirs' => 'handleMessagesDirs',
		'ExtensionMessagesFiles' => 'handleExtensionMessagesFiles',
		'AutoloadClasses' => 'removeAbsolutePath',
		'ExtensionCredits' => 'handleCredits',
		'ResourceModules' => 'handleResourceModules',
		'ResourceModuleSkinStyles' => 'handleResourceModules',
		'Hooks' => 'handleHooks',
		'ExtensionFunctions' => 'handleExtensionFunctions',
		'ParserTestFiles' => 'removeAutodiscoveredParserTestFiles',
	];

	/**
	 * Things that were formerly globals and should still be converted
	 */
	private const FORMER_GLOBALS = [
		'TrackingCategories',
	];

	/**
	 * No longer supported globals (with reason) should not be converted and emit a warning
	 */
	private const NO_LONGER_SUPPORTED_GLOBALS = [
		'SpecialPageGroups' => 'deprecated', // Deprecated 1.21, removed in 1.26
	];

	/**
	 * Keys that should be put at the top of the generated JSON file (T86608)
	 */
	private const PROMOTE_ATTRIBUTES = [
		'name',
		'namemsg',
		'version',
		'author',
		'url',
		'description',
		'descriptionmsg',
		'license-name',
		'type',
	];

	private array $json;
	private string $dir;
	private bool $hasWarning = false;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Converts extension entry points to the new JSON registration format' );
		$this->addArg( 'path', 'Location to the PHP entry point you wish to convert',
			/* $required = */ true );
		$this->addOption( 'skin', 'Whether to write to skin.json', false, false );
		$this->addOption( 'config-prefix', 'Custom prefix for configuration settings', false, true );
	}

	protected function getAllGlobals() {
		$processor = new ReflectionClass( ExtensionProcessor::class );
		$settings = $processor->getProperty( 'globalSettings' );
		$settings->setAccessible( true );
		return array_merge( $settings->getValue(), self::FORMER_GLOBALS );
	}

	public function execute() {
		// Extensions will do stuff like $wgResourceModules += array(...) which is a
		// fatal unless an array is already set. So set an empty value.
		// And use the weird $__settings name to avoid any conflicts
		// with real poorly named settings.
		$__settings = array_merge( $this->getAllGlobals(), array_keys( self::CUSTOM_GLOBALS ) );
		foreach ( $__settings as $var ) {
			$var = 'wg' . $var;
			$$var = [];
		}
		unset( $var );
		$arg = $this->getArg( 0 );
		if ( !is_file( $arg ) ) {
			$this->fatalError( "$arg is not a file." );
		}
		require $arg;
		unset( $arg );
		// Try not to create any local variables before this line
		$vars = get_defined_vars();
		unset( $vars['this'] );
		unset( $vars['__settings'] );
		$this->dir = dirname( realpath( $this->getArg( 0 ) ) );
		$this->json = [];
		$globalSettings = $this->getAllGlobals();
		$configPrefix = $this->getOption( 'config-prefix', 'wg' );
		if ( $configPrefix !== 'wg' ) {
			$this->json['config']['_prefix'] = $configPrefix;
		}

		foreach ( $vars as $name => $value ) {
			$realName = substr( $name, 2 ); // Strip 'wg'
			if ( $realName === false ) {
				continue;
			}

			// If it's an empty array that we likely set, skip it
			if ( is_array( $value ) && count( $value ) === 0 && in_array( $realName, $__settings ) ) {
				continue;
			}

			if ( isset( self::CUSTOM_GLOBALS[$realName] ) ) {
				call_user_func_array( [ $this, self::CUSTOM_GLOBALS[$realName] ],
					[ $realName, $value, $vars ] );
			} elseif ( in_array( $realName, $globalSettings ) ) {
				$this->json[$realName] = $value;
			} elseif ( array_key_exists( $realName, self::NO_LONGER_SUPPORTED_GLOBALS ) ) {
				$this->output( 'Warning: Skipped global "' . $name . '" (' .
					self::NO_LONGER_SUPPORTED_GLOBALS[$realName] . '). ' .
					"Please update the entry point before convert to registration.\n" );
				$this->hasWarning = true;
			} elseif ( strpos( $name, $configPrefix ) === 0 ) {
				$configName = substr( $name, strlen( $configPrefix ) );

				$isPath = false;
				if ( is_array( $value ) ) {
					foreach ( $value as $k => $v ) {
						if ( strpos( $v, $this->dir ) !== false ) {
							$value[$k] = $this->stripPath( $v, $this->dir );
							$isPath = true;
						}
					}
				} elseif ( is_string( $value ) && strpos( $value, $this->dir ) !== false ) {
					$value = $this->stripPath( $value, $this->dir );
					$isPath = true;
				}

				// Most likely a config setting
				$this->json['config'][$configName] = [ 'value' => $value ];

				if ( $isPath ) {
					$this->json['config'][$configName]['path'] = true;
				}
			} elseif ( $configPrefix !== 'wg' && strpos( $name, 'wg' ) === 0 ) {
				// Warn about this
				$this->output( 'Warning: Skipped global "' . $name . '" (' .
					'config prefix is "' . $configPrefix . '"). ' .
					"Please check that this setting isn't needed.\n" );
			}
		}

		// check, if the extension requires composer libraries
		if ( $this->needsComposerAutoloader( dirname( $this->getArg( 0 ) ) ) ) {
			// set the load composer autoloader automatically property
			$this->output( "Detected composer dependencies, setting 'load_composer_autoloader' to true.\n" );
			$this->json['load_composer_autoloader'] = true;
		}

		// Move some keys to the top
		$out = [];
		foreach ( self::PROMOTE_ATTRIBUTES as $key ) {
			if ( isset( $this->json[$key] ) ) {
				$out[$key] = $this->json[$key];
				unset( $this->json[$key] );
			}
		}
		// Set a requirement on the MediaWiki version that the current MANIFEST_VERSION
		// was introduced in.
		$out['requires'] = [
			ExtensionRegistry::MEDIAWIKI_CORE => ExtensionRegistry::MANIFEST_VERSION_MW_VERSION
		];
		$out += $this->json;
		// Put this at the bottom
		$out['manifest_version'] = ExtensionRegistry::MANIFEST_VERSION;
		$type = $this->hasOption( 'skin' ) ? 'skin' : 'extension';
		$fname = "{$this->dir}/$type.json";
		$prettyJSON = FormatJson::encode( $out, "\t", FormatJson::ALL_OK );
		file_put_contents( $fname, $prettyJSON . "\n" );
		$this->output( "Wrote output to $fname.\n" );
		if ( $this->hasWarning ) {
			$this->output( "Found warnings! Please resolve the warnings and rerun this script.\n" );
		}
	}

	protected function handleExtensionFunctions( $realName, $value ) {
		foreach ( $value as $func ) {
			if ( $func instanceof Closure ) {
				$this->fatalError( "Error: Closures cannot be converted to JSON. " .
					"Please move your extension function somewhere else."
				);
			} elseif ( function_exists( $func ) ) {
				// check if $func exists in the global scope
				$this->fatalError( "Error: Global functions cannot be converted to JSON. " .
					"Please move your extension function ($func) into a class."
				);
			}
		}

		$this->json[$realName] = $value;
	}

	protected function handleMessagesDirs( $realName, $value ) {
		foreach ( $value as $key => $dirs ) {
			foreach ( (array)$dirs as $dir ) {
				$this->json[$realName][$key][] = $this->stripPath( $dir, $this->dir );
			}
		}
	}

	protected function handleExtensionMessagesFiles( $realName, $value, $vars ) {
		foreach ( $value as $key => $file ) {
			$strippedFile = $this->stripPath( $file, $this->dir );
			if ( isset( $vars['wgMessagesDirs'][$key] ) ) {
				$this->output(
					"Note: Ignoring PHP shim $strippedFile. " .
					"If your extension no longer supports versions of MediaWiki " .
					"older than 1.23.0, you can safely delete it.\n"
				);
			} else {
				$this->json[$realName][$key] = $strippedFile;
			}
		}
	}

	private function stripPath( $val, $dir ) {
		if ( $val === $dir ) {
			$val = '';
		} elseif ( strpos( $val, $dir ) === 0 ) {
			// +1 is for the trailing / that won't be in $this->dir
			$val = substr( $val, strlen( $dir ) + 1 );
		}

		return $val;
	}

	protected function removeAbsolutePath( $realName, $value ) {
		$out = [];
		foreach ( $value as $key => $val ) {
			$out[$key] = $this->stripPath( $val, $this->dir );
		}
		$this->json[$realName] = $out;
	}

	protected function removeAutodiscoveredParserTestFiles( $realName, $value ) {
		$out = [];
		foreach ( $value as $key => $val ) {
			$path = $this->stripPath( $val, $this->dir );
			// When path starts with tests/parser/ the file would be autodiscovered with
			// extension registry, so no need to add it to extension.json
			if ( !str_starts_with( $path, 'tests/parser/' ) || !str_ends_with( $path, '.txt' ) ) {
				$out[$key] = $path;
			}
		}
		// in the best case all entries are filtered out
		if ( $out ) {
			$this->json[$realName] = $out;
		}
		// FIXME: the ParserTestFiles key was deprecated in
		// MW 1.30 and removed in MW 1.40.  If not all entries were filtered
		// out by the above, we *should* recommend the user move the
		// parser tests under `tests/parser` *not* generate an extension.json
		// with a ParserTestFiles key that will no longer validate.
	}

	protected function handleCredits( $realName, $value ) {
		$keys = array_keys( $value );
		$this->json['type'] = $keys[0];
		$values = array_values( $value );
		foreach ( $values[0][0] as $name => $val ) {
			if ( $name !== 'path' ) {
				$this->json[$name] = $val;
			}
		}
	}

	public function handleHooks( $realName, $value ) {
		foreach ( $value as $hookName => &$handlers ) {
			if ( $hookName === 'UnitTestsList' ) {
				$this->output( "Note: the UnitTestsList hook is no longer necessary as " .
					"long as your tests are located in the \"tests/phpunit/\" directory. " .
					"Please see <https://www.mediawiki.org/wiki/Manual:PHP_unit_testing/" .
					"Writing_unit_tests_for_extensions#Register_your_tests> for more details.\n"
				);
			}
			foreach ( $handlers as $func ) {
				if ( $func instanceof Closure ) {
					$this->fatalError( "Error: Closures cannot be converted to JSON. " .
						"Please move the handler for $hookName somewhere else."
					);
				} elseif ( function_exists( $func ) ) {
					// Check if $func exists in the global scope
					$this->fatalError( "Error: Global functions cannot be converted to JSON. " .
						"Please move the handler for $hookName inside a class."
					);
				}
			}
			if ( count( $handlers ) === 1 ) {
				$handlers = $handlers[0];
			}
		}
		$this->json[$realName] = $value;
	}

	/**
	 * @param string $realName
	 * @param array[] $value
	 */
	protected function handleResourceModules( $realName, $value ) {
		$defaults = [];
		$remote = $this->hasOption( 'skin' ) ? 'remoteSkinPath' : 'remoteExtPath';
		foreach ( $value as $name => $data ) {
			if ( isset( $data['localBasePath'] ) ) {
				$data['localBasePath'] = $this->stripPath( $data['localBasePath'], $this->dir );
				if ( !$defaults ) {
					$defaults['localBasePath'] = $data['localBasePath'];
					unset( $data['localBasePath'] );
					if ( isset( $data[$remote] ) ) {
						$defaults[$remote] = $data[$remote];
						unset( $data[$remote] );
					}
				} else {
					if ( $data['localBasePath'] === $defaults['localBasePath'] ) {
						unset( $data['localBasePath'] );
					}
					if ( isset( $data[$remote] ) && isset( $defaults[$remote] )
						&& $data[$remote] === $defaults[$remote]
					) {
						unset( $data[$remote] );
					}
				}
			}

			$this->json[$realName][$name] = $data;
		}
		if ( $defaults ) {
			$this->json['ResourceFileModulePaths'] = $defaults;
		}
	}

	protected function needsComposerAutoloader( $path ) {
		$path .= '/composer.json';
		if ( file_exists( $path ) ) {
			// assume that the composer.json file is in the root of the extension path
			$composerJson = new ComposerJson( $path );
			// check if there are some dependencies in the require section
			if ( $composerJson->getRequiredDependencies() ) {
				return true;
			}
		}
		return false;
	}
}

// @codeCoverageIgnoreStart
$maintClass = ConvertExtensionToRegistration::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
