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

/** Phpdoc script with full path */
$pdExec	= '/usr/bin/phpdoc';
/** where Phpdoc should output documentation */
$pdOutput = '/var/www/mwdoc/';

/** Some more Phpdoc settings */
//$pdOthers = ' -dn \'MediaWiki\' ';
$pdOthers .= ' --title \'Mediawiki generated documentation\' -o \'HTML:frames:DOM/earthli\' ';

/** Mediawiki location */
$mwPath = '/var/www/mediawiki/';

/** Mediawiki subpaths */
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
	$file = readaline("\Enter file name $mwPath");
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


# phpdoc -d ./mediawiki/includes/ ./mediawiki/maintenance/ -f ./mediawiki/*php -t ./mwdoc/ -dn 'MediaWiki' --title 'Mediawiki generated documentation' -o 'HTML:frames:DOM/earthli'

# phpdoc -f ./mediawiki/includes/GlobalFunctions.php -t ./mwdoc/ -dn 'MediaWiki' --title 'Mediawiki generated documentation' -o 'HTML:frames:DOM/earthli'

?>
