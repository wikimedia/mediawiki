<?php
/**
 * Script to easily generate the mediawiki documentation.
 *
 * By default it will generate the whole documentation but you will be able to
 * generate just some parts.
 *
 * Usage:
 *   php mwdocgen.php
 *
 * Then make a selection from the menu
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
	die( "Run me from the command line." );
}

/** Phpdoc script with full path */
#$pdExec	= '/usr/bin/phpdoc';
$pdExec = 'phpdoc';

/** Figure out the base directory. This is harder than it should be. */
/** Since we're on the command line, don't trust the PWD! */
$here = null;
$self = $_SERVER['SCRIPT_FILENAME'];
$sep = DIRECTORY_SEPARATOR;
foreach( get_included_files() as $f ) {
	if( preg_match( "!^(.*)maintenance$sep$self\$!", $f, $matches ) ) {
		$here = $matches[1];
	}
}
if( is_null( $here ) ) {
	die( "Couldn't determine current directory.\n" );
}

/** where Phpdoc should output documentation */
#$pdOutput = '/var/www/mwdoc/';
$pdOutput = "{$here}{$sep}docs{$sep}html";

/** Some more Phpdoc settings */
$pdOthers = '';
//$pdOthers = ' -dn \'MediaWiki\' ';
$pdOthers .= ' --title \'MediaWiki generated documentation\' -o \'HTML:frames:DOM/earthli\' ';

/** MediaWiki location */
#$mwPath = '/var/www/mediawiki/';
$mwPath = "{$here}{$sep}";

/** MediaWiki subpaths */
$mwPathI = $mwPath.'includes/';
$mwPathM = $mwPath.'maintenance/';
$mwPathS = $mwPath.'skins/';
$mwBaseFiles = $mwPath.'*php ';


/** Variable to get user input */
$input = '';
/** shell command that will be run */
$command = '';

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

#
# Main !
#

unset( $file );

if( is_array( $argv ) && isset( $argv[1] ) ) {
	switch( $argv[1] ) {
	case '--all':         $input = 0; break;
	case '--includes':    $input = 1; break;
	case '--maintenance': $input = 2; break;
	case '--skins':       $input = 3; break;
	case '--file':
		$input = 4;
		if( isset( $argv[2] ) ) {
			$file = $argv[2];
		}
		break;
	}
}

if( $input === '' ) {
	print <<<END
Several documentation possibilities:
 0 : whole documentation (1 + 2 + 3)
 1 : only includes
 2 : only maintenance
 3 : only skins
 4 : only a given file
END;

	while ( !is_numeric($input) )
	{
		$input = readaline( "\nEnter your choice [0]:" );
		if($input == '') {
			$input = 0;
		}
	}
}

$command = 'phpdoc ';
switch ($input) {
case 0:
	$command .= " -f $mwBaseFiles -d $mwPathI,$mwPathM,$mwPathS ";
	break;
case 1:
	$command .= "-d $mwPathI ";
	break;
case 2:
	$command .= "-d $mwPathM ";
	break;
case 3:
	$command .= "-d $mwPathS ";
	break;
case 4:
	if( !isset( $file ) ) {
		$file = readaline("\Enter file name $mwPath");
	}
	$command .= ' -f '.$mwPath.$file;
}

$command .= " -t $pdOutput ".$pdOthers;

print <<<END
---------------------------------------------------
Launching the command:
$command
---------------------------------------------------
END;

passthru( $command);

print <<<END
---------------------------------------------------
Phpdoc execution finished.
Check above for possible errors.

END;


# phpdoc -d ./mediawiki/includes/ ./mediawiki/maintenance/ -f ./mediawiki/*php -t ./mwdoc/ -dn 'MediaWiki' --title 'MediaWiki generated documentation' -o 'HTML:frames:DOM/earthli'

# phpdoc -f ./mediawiki/includes/GlobalFunctions.php -t ./mwdoc/ -dn 'MediaWiki' --title 'MediaWiki generated documentation' -o 'HTML:frames:DOM/earthli'

?>
