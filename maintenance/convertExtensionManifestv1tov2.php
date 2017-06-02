<?php
/**
 * Converts extensions with manifest v1 to v2.
 *
 * @author		Alexia E. Smith
 * @license		GPLv2
**/

require_once( __DIR__."/Maintenance.php" );

class convertExtensionManifestv1tov2 extends Maintenance {
	/**
	 * Main Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Convert Extension Manifest from v1 to v2.";
		$this->addArg( 'path', 'Location to the extension manifest you wish to convert.', true );
	}

	/**
	 * Main Execution
	 *
	 * @access	public
	 * @return	void
	 */
	public function execute() {
		$file = realpath( $this->getArg( 0 ) );
		if ( !is_file( $file ) ) {
			$this->error( "{$file} is not a file.", true );
		}
		$json = json_decode( file_get_contents( $file ), true );
		if ( empty( $json ) || !isset( $json[ 'manifest_version' ] ) ) {
			$this->error( "{$file} is not a valid extension manifest.", true );
		}
		if ( $json[ 'manifest_version' ] > 1 ) {
			$this->error( "{$file} is already greater than version 1.", true );
		}

		$this->output( "All config settings will be marked path => false and public => false.  Please make corrections manually.\n" );

		$descriptionPrefix = str_replace( ' ', '', ( trim( mb_strtolower( $json[ 'name' ], 'UTF-8' ) ) ) );
		foreach ( $json[ 'config' ] as $key => $value ) {
			$json[ 'config' ][ $key ] = $this->configV2( $key, $value, $descriptionPrefix );
		}
		if ( isset( $json[ 'config' ][ '_prefix' ] ) ) {
			$json[ 'config_prefix' ] = $json[ 'config' ][ '_prefix' ];
			unset( $json[ 'config' ][ '_prefix' ] );
		}
		$json[ 'manifest_version' ] = 2;
		$json = FormatJson::encode( $json, "\t", FormatJson::ALL_OK );
		file_put_contents( $file, $json );
		$this->output( "Wrote updated manifest.\n" );
	}

	/**
	 * Convert configuration to version 2.
	 *
	 * @access	private
	 * @param	string	Variable Name
	 * @param	string	Variable Value
	 * @param	string	Prefix for descriptionmsg.
	 * @return	array	Configuration
	 */
	private function configV2( $key, $value, $descriptionPrefix ) {
		$mergeStrategy = false;
		if ( is_array( $value ) && isset( $value[ '_merge_strategy' ] ) ) {
			$mergeStrategy = $value[ '_merge_strategy' ];
			unset( $value[ '_merge_strategy' ] );
		}

		$config = [
			'value' => $value,
			'path' => false,
			'descriptionmsg' => $descriptionPrefix.'-config-'.str_replace( ' ', '-', ( trim( mb_strtolower( $key, 'UTF-8' ) ) ) ),
			'public' => false,
		];

		if ( $mergeStrategy !== false ) {
			$config[ 'merge_strategy' ] = $mergeStrategy;
		}
		return $config;
	}
}

$maintClass = 'convertExtensionManifestv1tov2';
require_once( RUN_MAINTENANCE_IF_MAIN );
