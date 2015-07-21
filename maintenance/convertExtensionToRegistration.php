<?php

require_once __DIR__ . '/Maintenance.php';

class ConvertExtensionToRegistration extends Maintenance {

	protected $custom = array(
		'MessagesDirs' => 'handleMessagesDirs',
		'ExtensionMessagesFiles' => 'removeAbsolutePath',
		'AutoloadClasses' => 'removeAbsolutePath',
		'ExtensionCredits' => 'handleCredits',
		'ResourceModules' => 'handleResourceModules',
		'ResourceModuleSkinStyles' => 'handleResourceModules',
		'Hooks' => 'handleHooks',
		'ExtensionFunctions' => 'handleExtensionFunctions',
		'ParserTestFiles' => 'removeAbsolutePath',
	);

	/**
	 * Things that were formerly globals and should still be converted
	 *
	 * @var array
	 */
	protected $formerGlobals = array(
		'TrackingCategories',
	);

	/**
	 * No longer supported globals (with reason) should not be converted and emit a warning
	 *
	 * @var array
	 */
	protected $noLongerSupportedGlobals = array(
		'SpecialPageGroups' => 'deprecated',
	);

	/**
	 * Keys that should be put at the top of the generated JSON file (T86608)
	 *
	 * @var array
	 */
	protected $promote = array(
		'name',
		'version',
		'author',
		'url',
		'description',
		'descriptionmsg',
		'namemsg',
		'license-name',
		'type',
	);

	private $json, $dir, $hasWarning = false;

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Converts extension entry points to the new JSON registration format';
		$this->addArg( 'path', 'Location to the PHP entry point you wish to convert', /* $required = */ true );
		$this->addOption( 'skin', 'Whether to write to skin.json', false, false );
	}

	protected function getAllGlobals() {
		$processor = new ReflectionClass( 'ExtensionProcessor' );
		$settings = $processor->getProperty( 'globalSettings' );
		$settings->setAccessible( true );
		return $settings->getValue() + $this->formerGlobals;
	}

	public function execute() {
		// Extensions will do stuff like $wgResourceModules += array(...) which is a
		// fatal unless an array is already set. So set an empty value.
		// And use the weird $__settings name to avoid any conflicts
		// with real poorly named settings.
		$__settings = array_merge( $this->getAllGlobals(), array_keys( $this->custom ) );
		foreach ( $__settings as $var ) {
			$var = 'wg' . $var;
			$$var = array();
		}
		unset( $var );
		require $this->getArg( 0 );
		// Try not to create any local variables before this line
		$vars = get_defined_vars();
		unset( $vars['this'] );
		unset( $vars['__settings'] );
		$this->dir = dirname( realpath( $this->getArg( 0 ) ) );
		$this->json = array();
		$globalSettings = $this->getAllGlobals();
		foreach ( $vars as $name => $value ) {
			$realName = substr( $name, 2 ); // Strip 'wg'

			// If it's an empty array that we likely set, skip it
			if ( is_array( $value ) && count( $value ) === 0 && in_array( $realName, $__settings ) ) {
				continue;
			}

			if ( isset( $this->custom[$realName] ) ) {
				call_user_func_array( array( $this, $this->custom[$realName] ), array( $realName, $value ) );
			} elseif ( in_array( $realName, $globalSettings ) ) {
				$this->json[$realName] = $value;
			} elseif ( array_key_exists( $realName, $this->noLongerSupportedGlobals ) ) {
				$this->output( 'Warning: Skipped global "' . $name . '" (' .
					$this->noLongerSupportedGlobals[$realName] . '). ' .
					"Please update the entry point before convert to registration.\n" );
				$this->hasWarning = true;
			} elseif ( strpos( $name, 'wg' ) === 0 ) {
				// Most likely a config setting
				$this->json['config'][$realName] = $value;
			}
		}

		// Move some keys to the top
		$out = array();
		foreach ( $this->promote as $key ) {
			if ( isset( $this->json[$key] ) ) {
				$out[$key] = $this->json[$key];
				unset( $this->json[$key] );
			}
		}
		$out += $this->json;

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
				$this->error( "Error: Closures cannot be converted to JSON. Please move your extension function somewhere else.", 1 );
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
		$out = array();
		foreach ( $value as $key => $val ) {
			$out[$key] = $this->stripPath( $val, $this->dir );
		}
		$this->json[$realName] = $out;
	}

	protected function handleCredits( $realName, $value) {
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
		foreach ( $value as $hookName => $handlers ) {
			foreach ( $handlers as $func ) {
				if ( $func instanceof Closure ) {
					$this->error( "Error: Closures cannot be converted to JSON. Please move the handler for $hookName somewhere else.", 1 );
				}
			}
		}
		$this->json[$realName] = $value;
	}

	protected function handleResourceModules( $realName, $value ) {
		$defaults = array();
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
}

$maintClass = 'ConvertExtensionToRegistration';
require_once RUN_MAINTENANCE_IF_MAIN;
