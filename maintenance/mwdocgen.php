<?php
/**
 * Generate class and file reference documentation for MediaWiki using doxygen.
 *
 * If the dot DOT language processor is available, attempt call graph
 * generation.
 *
 * Usage:
 *   php mwdocgen.php
 *
 * KNOWN BUGS:
 *
 * - pass_thru seems to always use buffering (even with ob_implicit_flush()),
 * that make output slow when doxygen parses language files.
 * - the menu doesnt work, got disabled at revision 13740. Need to code it.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @todo document
 * @ingroup Maintenance
 *
 * @author Antoine Musso <hashar at free dot fr>
 * @author Brion Vibber
 * @author Alexandre Emsenhuber
 * @version first release
 */

#
# Variables / Configuration
#

if ( php_sapi_name() != 'cli' ) {
	echo 'Run "' . __FILE__ . '" from the command line.';
	die( -1 );
}

/** Figure out the base directory for MediaWiki location */
$mwPath = dirname( __DIR__ ) . DIRECTORY_SEPARATOR;

/** doxygen binary script */
$doxygenBin = 'doxygen';

/** doxygen configuration template for mediawiki */
$doxygenTemplate = $mwPath . 'maintenance/Doxyfile';

/** doxygen input filter to tweak source file before they are parsed */
$doxygenInputFilter = "php {$mwPath}maintenance/mwdoc-filter.php";

/** where Phpdoc should output documentation */
$doxyOutput = $mwPath . 'docs' . DIRECTORY_SEPARATOR ;

/** MediaWiki subpaths */
$mwPathI = $mwPath . 'includes/';
$mwPathL = $mwPath . 'languages/';
$mwPathM = $mwPath . 'maintenance/';
$mwPathS = $mwPath . 'skins/';

/** Ignored paths relative to $mwPath */
$mwExcludePaths = array(
	'images',
	'static',
);

/** Variable to get user input */
$input = '';
$excludePatterns = '';
/** Whether to generates man pages: */
$doxyGenerateMan = false;

#
# Functions
#

define( 'MEDIAWIKI', true );
require_once( "$mwPath/includes/GlobalFunctions.php" );

/**
 * Read a line from the shell
 * @param $prompt String
 * @return string
 */
function readaline( $prompt = '' ) {
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
 * @param $currentVersion String: Version number of the software
 * @param $input String: Path to analyze.
 * @param $exclude String: Additionals path regex to exclude
 * @param $excludePatterns String: Additionals path regex to exclude
 *                 (LocalSettings.php, AdminSettings.php, .svn and .git directories are always excluded)
 * @param $doxyGenerateMan Boolean
 * @return string
 */
function generateConfigFile( $doxygenTemplate, $outputDirectory, $stripFromPath, $currentVersion, $input, $exclude, $excludePatterns, $doxyGenerateMan ) {
	global $doxygenInputFilter;

	$template = file_get_contents( $doxygenTemplate );
	// Replace template placeholders by correct values.
	$replacements = array(
		'{{OUTPUT_DIRECTORY}}' => $outputDirectory,
		'{{STRIP_FROM_PATH}}'  => $stripFromPath,
		'{{CURRENT_VERSION}}'  => $currentVersion,
		'{{INPUT}}'            => $input,
		'{{EXCLUDE}}'          => $exclude,
		'{{EXCLUDE_PATTERNS}}' => $excludePatterns,
		'{{HAVE_DOT}}'         => `which dot` ? 'YES' : 'NO',
		'{{GENERATE_MAN}}'     => $doxyGenerateMan ? 'YES' : 'NO',
		'{{INPUT_FILTER}}'     => $doxygenInputFilter,
	);
	$tmpCfg = str_replace( array_keys( $replacements ), array_values( $replacements ), $template );
	$tmpFileName = tempnam( wfTempDir(), 'mwdocgen-' );
	file_put_contents( $tmpFileName , $tmpCfg ) or die( "Could not write doxygen configuration to file $tmpFileName\n" );

	return $tmpFileName;
}

#
# Main !
#

unset( $file );

if ( is_array( $argv ) ) {
	for ($i = 0; $i < count($argv); $i++ ) {
		switch( $argv[$i] ) {
		case '--all':         $input = 0; break;
		case '--includes':    $input = 1; break;
		case '--languages':   $input = 2; break;
		case '--maintenance': $input = 3; break;
		case '--skins':       $input = 4; break;
		case '--file':
			$input = 5;
			$i++;
			if ( isset( $argv[$i] ) ) {
				$file = $argv[$i];
			}
			break;
		case '--no-extensions': $input = 6; break;
		case '--output':
			$i++;
			if ( isset( $argv[$i] ) ) {
				$doxyOutput = realpath( $argv[$i] );
			}
			break;
		case '--generate-man':
			$doxyGenerateMan = true;
			break;
		case '--help':
			print <<<END
Usage: php mwdocgen.php [<command>] [<options>]

Commands:
    --all           Process entire codebase
    --includes      Process only files in includes/ dir
    --languages     Process only files in languages/ dir
    --maintenance   Process only files in maintenance/ dir
    --skins         Process only files in skins/ dir
    --file <file>   Process only the given file
    --no-extensions Process everything but extensions directorys

If no command is given, you will be prompted.

Other options:
    --output <dir>  Set output directory (default $doxyOutput)
    --generate-man  Generates man page documentation
    --help          Show this help and exit.


END;
			exit(0);
			break;
		}
	}
}

// TODO : generate a list of paths ))

if ( $input === '' ) {
	echo <<<OPTIONS
Several documentation possibilities:
 0 : whole documentation (1 + 2 + 3 + 4)
 1 : only includes
 2 : only languages
 3 : only maintenance
 4 : only skins
 5 : only a given file
 6 : all but the extensions directory
OPTIONS;
	while ( !is_numeric( $input ) )
	{
		$input = readaline( "\nEnter your choice [0]:" );
		if ( $input == '' ) {
			$input = 0;
		}
	}
}

switch ( $input ) {
case 0: $input = $mwPath;  break;
case 1: $input = $mwPathI; break;
case 2: $input = $mwPathL; break;
case 3: $input = $mwPathM; break;
case 4: $input = $mwPathS; break;
case 5:
	if ( !isset( $file ) ) {
		$file = readaline( "Enter file name $mwPath" );
	}
	$input = $mwPath . $file;
	break;
case 6:
	$input = $mwPath;
	$excludePatterns = 'extensions';
}

// @todo FIXME to work on git
$version = 'master';

// Generate path exclusions
$excludedPaths = $mwPath . join( " $mwPath", $mwExcludePaths );
print "EXCLUDE: $excludedPaths\n\n";

$generatedConf = generateConfigFile( $doxygenTemplate, $doxyOutput, $mwPath, $version, $input, $excludedPaths, $excludePatterns, $doxyGenerateMan );
$command = $doxygenBin . ' ' . $generatedConf;

echo <<<TEXT
---------------------------------------------------
Launching the command:

$command

---------------------------------------------------

TEXT;

passthru( $command );

echo <<<TEXT
---------------------------------------------------
Doxygen execution finished.
Check above for possible errors.

You might want to delete the temporary file $generatedConf

TEXT;
