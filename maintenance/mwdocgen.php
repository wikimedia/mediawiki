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

/** Figure out the base directory. */
$here = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR;

/** where Phpdoc should output documentation */
#$pdOutput = '/var/www/mwdoc/';
$pdOutput = "{$here}docs" . DIRECTORY_SEPARATOR . 'html';

/** Some more Phpdoc settings */
# This will be used as the default for all files that don't have a package,
# it's useful to set it to something like 'untagged' to hunt down and fix files
# that don't have a package name declared.
$pdOthers = " -dn MediaWiki"; 
$pdOthers .= ' --title "MediaWiki generated documentation"'; 
$pdOthers .= ' --output "HTML:Smarty:HandS"'; #,HTML:Smarty:HandS"'; ###### HTML:frames:DOM/earthli
$pdOthers .= ' --ignore AdminSettings.php,LocalSettings.php,tests/LocalTestSettings.php';
$pdOthers .= ' --parseprivate on';
$pdOthers .= ' --sourcecode on';

/** MediaWiki location */
#$mwPath = '/var/www/mediawiki/';
$mwPath = "{$here}";

/** MediaWiki subpaths */
$mwPathI = $mwPath.'includes/';
$mwPathL = $mwPath.'languages/';
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
 0 : whole documentation (1 + 2 + 3)
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

$command = 'phpdoc ';
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

?>
---------------------------------------------------
Launching the command:

<?php echo $command ?>

---------------------------------------------------
<?php

passthru($command);

?>
---------------------------------------------------
Phpdoc execution finished.
Check above for possible errors.
<?php

# phpdoc -d ./mediawiki/includes/ ./mediawiki/maintenance/ -f ./mediawiki/*php -t ./mwdoc/ -dn 'MediaWiki' --title 'MediaWiki generated documentation' -o 'HTML:frames:DOM/earthli'

# phpdoc -f ./mediawiki/includes/GlobalFunctions.php -t ./mwdoc/ -dn 'MediaWiki' --title 'MediaWiki generated documentation' -o 'HTML:frames:DOM/earthli'

?>
