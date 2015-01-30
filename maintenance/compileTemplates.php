<?php
# This script compiles mustache templates into PHP templates for use by MediaWiki.
#
# It is dependent on the lightncandy library (https://github.com/zordius/lightncandy).
#
# If this script is run without any arguments, it compiles all the templates in the source
# directory. If a filename is passed as an arguement, it will look for that file in the
# source directory and compile it. For example:
# php ./compileTemplates.php NoLocalSettings.mustache

if ( PHP_SAPI !== 'cli' ) {
	die( "This script can only be run from the command line.\n" );
}

$sourcePath = __DIR__ . "/../includes/templates/src";
$compiledPath = __DIR__ . "/../includes/templates/compiled";

require_once( __DIR__ . "/../vendor/zordius/lightncandy/src/lightncandy.php" );

function compileTemplate( $file ) {
	global $sourcePath, $compiledPath;
	echo "Compiling " . $file . "... ";
	$templateFile = fopen( $sourcePath . "/" . $file, "r" ) or die( "Unable to open file!\n" );
	$template = fread( $templateFile, filesize( $sourcePath . "/" . $file ) );
	// Compile mustache template into PHP template
	$phpStr = LightnCandy::compile( $template );
	// Store the compiled template in the compiled directory.
	$destFileName = preg_replace( '/\.(html|mustache)$/', '.php', $file );
	file_put_contents( $compiledPath . "/" . $destFileName, $phpStr );
	echo "done.\n";
}

// If a filename is passed as a argument, compile only that template.
if ( isset( $argv[1] ) ) {
	compileTemplate( $argv[1] );
// Otherwise, compile all files in the source directory.
} else {
	$count = 0;
	foreach( scandir( $sourcePath ) as $file ) {
		if ( $file !== "." && $file !== ".." && $file !== "README" ) {
			compileTemplate( $file );
			$count++;
		}
	}
	if ( $count === 1 ) {
		echo $count . " template compiled.\n";
	} else {
		echo $count . " templates compiled.\n";
	}
}
