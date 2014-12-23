<?php

if ( PHP_SAPI !== "cli" ) {
	die( "This is a command line script, it cannot be run in " . PHP_SAPI . " mode\n" );
}

if ( count( $argv ) < 3 ) {
	print( "USAGE: trimI18n.php <base> <target>\n" );
	print( "SYNOPSIS: Given <base> and <target> are JSON files, this will remove any keys from <target>\n" );
	print( "          which are not in <base>.\n" );
	die();
}

class TrimI18n {

	private $jsonFlags = 0;
	private $tab = "\t";

	/**
	 * @param int $jsonFlags
	 */
	public function setJsonFlags( $jsonFlags ) {
		$this->jsonFlags = $jsonFlags;
	}

	/**
	 * @return int
	 */
	public function getJsonFlags() {
		return $this->jsonFlags;
	}

	private function load( $path ) {
		$json = file_get_contents( $path );

		if ( $json === false ) {
			throw new RuntimeException( "Can't load JSON data from $path." );
		}

		$data = json_decode( $json, true );

		if ( !is_array( $data ) ) {
			throw new RuntimeException( "Can't decode JSON data found in $path." );
		}

		return $data;
	}

	private function prettify( $json ) {
		if ( ( $this->jsonFlags & JSON_PRETTY_PRINT ) === 0 ) {
			return $json;
		}

		//NOTE: json_encode uses four spaces for indenting in pretty-print mode.

		if ( $this->tab === '    ' ) {
			return $json;
		}

		$tab = $this->tab;
		return preg_replace_callback( '/^(?: )+/m', function( $matches ) use ( $tab ) {
			return str_repeat( $tab, strlen( $matches[0] ) / 4 );
		}, $json );
	}

	private function save( $path, array $data ) {
		$json = json_encode( $data, $this->jsonFlags );

		if ( $json === false ) {
			throw new RuntimeException( "Can't encode data as JSON!" );
		}

		$json = $this->prettify( $json );

		$ok = file_put_contents( $path, $json );

		if ( $ok === false ) {
			throw new RuntimeException( "Failed to save JSON data to $path!" );
		}

		return $data;
	}

	public function run( $basePath, $targetPath ) {
		$baseData = $this->load( $basePath );
		$targetData = $this->load( $targetPath );

		$targetData = array_intersect_key( $targetData, $baseData );
		$this->save( $targetPath, $targetData );
	}

}

$basePath = $argv[1];
$targetPath = $argv[2];

try {
	$trim = new TrimI18n();

	// Flags are set for consistency with the flags used by EncodeJson::encode54()
	$trim->setJsonFlags( JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

	$trim->run( $basePath, $targetPath );

	print "Updated $targetPath, retaining all keys found in $basePath.\n";
} catch ( RuntimeException $ex ) {
	die( $ex->getMessage() . "\n" );
}

