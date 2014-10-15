<?php

require_once __DIR__ . '/Maintenance.php';

class ConvertExtensionToRegistration extends Maintenance {

	protected $custom = array(
		'MessagesDirs' => 'removeAbsolutePath',
		'ExtensionMessagesFiles' => 'removeAbsolutePath',
		'AutoloadClasses' => 'removeAbsolutePath',
		'ExtensionCredits' => 'handleCredits',
		'ResourceModules' => 'handleResourceModules',
		'Hooks' => 'handleHooks',
		'ExtensionFunctions' => 'handleExtensionFunctions',
	);

	private $json, $dir;

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Converts extension entry points to the new JSON registration format';
	}

	public function execute() {
		require $this->getArg( 0 );
		// Try not to create any local variables before this line
		$vars = get_defined_vars();
		unset( $vars['this'] );
		$this->dir = dirname( realpath( $this->getArg( 0 ) ) );
		$this->json = array();
		$processor = new ReflectionClass( 'ExtensionProcessor' );
		$settings = $processor->getProperty( 'globalSettings' );
		$settings->setAccessible( true );
		$globalSettings = $settings->getValue();
		foreach ( $vars as $name => $value ) {
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

		$fname = "{$this->dir}/extension.json";
		$prettyJSON = FormatJson::encode( $this->json, "\t" );
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

	private function stripPath( $val, $dir ) {
		if ( strpos( $val, $dir ) === 0 ) {
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
