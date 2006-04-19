<?php
/**
 * Script to easily generate the mediawiki documentation using doxygen.
 *
 * By default it will generate the whole documentation but you will be able to
 * generate just some parts.
 *
 * Usage:
 *   php mwdocgen.php
 *
 * Then make a selection from the menu
 *
 * KNOWN BUGS:
 *
 * - pass_thru seems to always use buffering (even with ob_implicit_flush()),
 * that make output slow when doxygen parses language files.
 * - the menu doesnt work, got disabled at revision 13740. Need to code it.
 *
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 *
 * @author Ashar Voultoiz <thoane@altern.org>
 * @version first release
 */

#
# Variables / Configuration
#

if( php_sapi_name() != 'cli' ) {
	echo 'Run me from the command line.';
	die( -1 );
}

/** Figure out the base directory for MediaWiki location */
$mwPath = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR;

/** Global variable: temporary directory */
$tmpPath = '/tmp/';

/** doxygen binary script */
$doxygenBin = 'doxygen';

/** doxygen configuration template for mediawiki */
$doxygenTemplate = $mwPath . 'maintenance/Doxyfile';

/** where Phpdoc should output documentation */
#$doxyOutput = '/var/www/mwdoc/';
$doxyOutput = $mwPath . 'docs' . DIRECTORY_SEPARATOR ;

/** MediaWiki subpaths */
$mwPathI = $mwPath.'includes/';
$mwPathL = $mwPath.'languages/';
$mwPathM = $mwPath.'maintenance/';
$mwPathS = $mwPath.'skins/';

/** Variable to get user input */
$input = '';

/** shell command that will be run */
$command = $doxygenBin;

#
# Functions
#

function readaline( $prompt = '') {
	print $prompt;
	$fp = fopen( "php://stdin", "r" );
	$resp = trim( fgets( $fp, 1024 ) );
	fclose( $fp );
	return $resp;
	}

/**
 * Generate a configuration file given user parameters and return the temporary filename.
 * @param $doxygenTemplate String: full path for the template.
 * @param $outputDirectory String: directory where the stuff will be output.
 * @param $stripFromPath String: path that should be stripped out (usually mediawiki base path).
 * @param $input String: Path to analyze.
 */
function generateConfigFile($doxygenTemplate, $outputDirectory, $stripFromPath, $input) {
	global $tmpPath ;

	$template = file_get_contents($doxygenTemplate);

	// Replace template placeholders by correct values.	
	$tmpCfg = str_replace(
			array(
				'{{OUTPUT_DIRECTORY}}',
				'{{STRIP_FROM_PATH}}',
				'{{INPUT}}',
			),
			array(
				$outputDirectory,
				$stripFromPath,
				$input,
			),
			$template
		);
	$tmpFileName = $tmpPath . 'mwdocgen'. rand() .'.tmp';
	file_put_contents( $tmpFileName , $tmpCfg ) or die("Could not write doxygen configuration to file $tmpFileName\n");

	return $tmpFileName;
}

#
# Main !
#

unset( $file );

if( is_array( $argv ) && isset( $argv[1] ) ) {
	switch( $argv[1] ) {
	case '--all':         $input = 0; break;
	case '--includes':    $input = 1; break;
	case '--languages':   $input = 2; break;
	case '--maintenance': $input = 3; break;
	case '--skins':       $input = 4; break;
	case '--file':
		$input = 5;
		if( isset( $argv[2] ) ) {
			$file = $argv[2];
		}
		break;
	}
}

if( $input === '' ) {
?>Several documentation possibilities:
 0 : whole documentation (1 + 2 + 3 + 4)
 1 : only includes
 2 : only languages
 3 : only maintenance
 4 : only skins
 5 : only a given file<?php
	while ( !is_numeric($input) )
	{
		$input = readaline( "\nEnter your choice [0]:" );
		if($input == '') {
			$input = 0;
		}
	}
}
/*
switch ($input) {
case 0:
	$command .= " -f $mwBaseFiles -d $mwPathI,$mwPathL,$mwPathM,$mwPathS";
	break;
case 1:
	$command .= "-d $mwPathI";
	break;
case 2:
	$command .= "-d $mwPathL";
	break;
case 3:
	$command .= "-d $mwPathM";
	break;
case 4:
	$command .= "-d $mwPathS";
	break;
case 5:
	if( !isset( $file ) ) {
		$file = readaline("Enter file name $mwPath");
	}
	$command .= ' -f '.$mwPath.$file;
}

$command .= " -t $pdOutput ".$pdOthers;

*/

// TODO : generate a list of paths ))
$input = $mwPath;

$generatedConf = generateConfigFile($doxygenTemplate, $doxyOutput, $mwPath, $input );
$command = $doxygenBin . ' ' . $generatedConf ;

?>
---------------------------------------------------
Launching the command:

<?php echo $command ?>

---------------------------------------------------
<?php

passthru($command);

?>
---------------------------------------------------
Doxygen execution finished.
Check above for possible errors.

You might want to deleted the temporary file <?php echo $generatedConf; ?>

<?php

# phpdoc -d ./mediawiki/includes/ ./mediawiki/maintenance/ -f ./mediawiki/*php -t ./mwdoc/ -dn 'MediaWiki' --title 'MediaWiki generated documentation' -o 'HTML:frames:DOM/earthli'

# phpdoc -f ./mediawiki/includes/GlobalFunctions.php -t ./mwdoc/ -dn 'MediaWiki' --title 'MediaWiki generated documentation' -o 'HTML:frames:DOM/earthli'

?>
