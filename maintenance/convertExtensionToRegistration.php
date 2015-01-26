<?php

require_once __DIR__ . '/Maintenance.php';

class ConvertExtensionToRegistration extends Maintenance {

	protected $custom = array(
		'MessagesDirs' => 'handleMessagesDirs',
		'ExtensionMessagesFiles' => 'removeAbsolutePath',
		'AutoloadClasses' => 'removeAbsolutePath',
		'ExtensionCredits' => 'handleCredits',
		'ResourceModules' => 'handleResourceModules',
		'Hooks' => 'handleHooks',
		'ExtensionFunctions' => 'handleExtensionFunctions',
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

	private $json, $dir;

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Converts extension entry points to the new JSON registration format';
		$this->addArg( 'path', 'Location to the PHP entry point you wish to convert', /* $required = */ true );
	}

	protected function getAllGlobals() {
		$processor = new ReflectionClass( 'ExtensionProcessor' );
		$settings = $processor->getProperty( 'globalSettings' );
		$settings->setAccessible( true );
		return $settings->getValue();
	}

	public function execute() {
		// Extensions will do stuff like $wgResourceModules += array(...) which is a
		// fatal unless an array is already set. So set an empty value.
		foreach ( array_merge( $this->getAllGlobals(), array_keys( $this->custom ) ) as $var ) {
			$var = 'wg' . $var;
			$$var = array();
		}
		unset( $var );
		require $this->getArg( 0 );
		// Try not to create any local variables before this line
		$vars = get_defined_vars();
		unset( $vars['this'] );
		$this->dir = dirname( realpath( $this->getArg( 0 ) ) );
		$this->json = array();
		$globalSettings = $this->getAllGlobals();
		foreach ( $vars as $name => $value ) {
			// If an empty array, assume it's the default we set, so skip it
			if ( is_array( $value ) && count( $value ) === 0 ) {
				continue;
			}
			$realName = substr( $name, 2 ); // Strip 'wg'
			if ( isset( $this->custom[$realName] ) ) {
				call_user_func_array( array( $this, $this->custom[$realName] ), array( $realName, $value ) );
			} elseif ( in_array( $realName, $globalSettings ) ) {
				$this->json[$realName] = $value;
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

		$fname = "{$this->dir}/extension.json";
		$prettyJSON = FormatJson::encode( $out, "\t", FormatJson::ALL_OK );
		file_put_contents( $fname, $prettyJSON . "\n" );
		$this->output( "Wrote output to $fname.\n" );
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
		foreach ( $value as $name => $data ) {
			if ( isset( $data['localBasePath'] ) ) {
				$data['localBasePath'] = $this->stripPath( $data['localBasePath'], $this->dir );
			}
			$this->json[$realName][$name] = $data;
		}
	}
}

$maintClass = 'ConvertExtensionToRegistration';
require_once RUN_MAINTENANCE_IF_MAIN;
