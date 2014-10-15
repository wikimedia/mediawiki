<?php

require_once __DIR__ . '/Maintenance.php';

class ConvertExtensionToRegistration extends Maintenance {

	protected $custom = array(
		'MessagesDirs' => 'removeAbsolutePath',
		'ExtensionMessagesFiles' => 'removeAbsolutePath',
		'AutoloadClasses' => 'removeAbsolutePath',
		'ExtensionCredits' => 'handleCredits',
		'ResourceModules' => 'handleResourceModules',
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
		$this->dir = dirname( $this->getArg( 0 ) );
		$this->json = array();
		$processor = new ReflectionClass( 'ExtensionProcessor' );
		$settings = $processor->getProperty( 'globalSettings' );
		$settings->setAccessible( true );
		$globalSettings = $settings->getValue();
		foreach ( $vars as $name => $value ) {
			$realName = substr( $name, 2 ); // Strip 'wg'
			if ( isset( $this->custom[$realName] ) ) {
				call_user_func_array( array( $this, $this->custom[$realName] ), array( $realName, $value ) );
			} elseif ( in_array( $realName, $globalSettings )) {
				$this->json[$realName] = $value;
			} else {
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

	protected function removeAbsolutePath( $realName, $value ) {
		$out = array();
		foreach ( $value as $key => $val ) {
			if ( strpos( $val, $this->dir ) === 0 ) {
				// +1 is for the trailing / that won't be in $this->dir
				$val = substr( $val, strlen( $this->dir ) + 1 );
			}
			$out[$key] = $val;
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

	protected function handleResourceModules( $realName, $value ) {

	}
}

$maintClass = 'ConvertExtensionToRegistration';
require_once RUN_MAINTENANCE_IF_MAIN;