<?php
/**
 * Check the autoloader
 */

require_once( "Maintenance.php" );

class CheckAutoLoader extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "AutoLoader sanity checks";
	}
	public function execute() {
		global $wgAutoloadLocalClasses, $IP;
		$files = array_unique( $wgAutoloadLocalClasses );

		foreach( $files as $file ) {
			if( function_exists( 'parsekit_compile_file' ) ){
				$parseInfo = parsekit_compile_file( "$IP/$file" );
				$classes = array_keys( $parseInfo['class_table'] );
			} else {
				$contents = file_get_contents( "$IP/$file" );
				$m = array();
				preg_match_all( '/\n\s*class\s+([a-zA-Z0-9_]+)/', $contents, $m, PREG_PATTERN_ORDER );
				$classes = $m[1];
			}
			foreach ( $classes as $class ) {
				if ( !isset( $wgAutoloadLocalClasses[$class] ) ) {
					//printf( "%-50s Unlisted, in %s\n", $class, $file );
					$this->output( "\t'$class' => '$file',\n" );
				} elseif ( $wgAutoloadLocalClasses[$class] !== $file ) {
					$this->output( "$class: Wrong file: found in $file, listed in " . $wgAutoloadLocalClasses[$class] . "\n" );
				}
			}
		}
	}
}

$maintClass = "CheckAutoLoader";
require_once( DO_MAINTENANCE );
