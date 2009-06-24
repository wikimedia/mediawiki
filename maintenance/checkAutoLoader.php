<?php
if ( php_sapi_name() != 'cli' ) exit;

$IP = dirname(__FILE__) .'/..';
require( "$IP/includes/AutoLoader.php" );
$files = array_unique( $wgAutoloadLocalClasses );

foreach ( $files as $file ) {
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
			echo "		'$class' => '$file',\n";
		} elseif ( $wgAutoloadLocalClasses[$class] !== $file ) {
			echo "$class: Wrong file: found in $file, listed in " . $wgAutoloadLocalClasses[$class] . "\n";
		}
	}

}


