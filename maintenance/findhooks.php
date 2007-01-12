<?php
/**
 * Simple script that try to find documented hook and hooks actually
 * in the code and show what's missing.
 * 
 * This script assumes that:
 * - hooks names in hooks.txt are at the beginning of a line and single quoted.
 * - hooks names in code are the first parameter of wfRunHooks.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 *
 * @author Ashar Voultoiz <hashar@altern.org>
 * @copyright Copyright © Ashar voultoiz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public Licence 2.0 or later
 */

/** This is a command line script*/
include('commandLine.inc');


# GLOBALS

$doc = $IP . '/docs/hooks.txt';
$pathinc = $IP . '/includes/';


# FUNCTIONS

/**
 * @return array of documented hooks
 */
function getHooksFromDoc() {
	global $doc;
	$content = file_get_contents( $doc );
	$m = array();
	preg_match_all( "/\n'(.*?)'/", $content, $m);
	return $m[1];
}

/**
 * Get hooks from a php file
 * @param $file Full filename to the PHP file.
 * @return array of hooks found.
 */
function getHooksFromFile( $file ) {
	$content = file_get_contents( $file );
	$m = array();
	preg_match_all( "/wfRunHooks\(\s*\'(.*?)\'/", $content, $m);
	return $m[1];
}

/**
 * Get hooks from the source code.
 * @param $path Directory where the include files can be found
 * @return array of hooks found.
 */
function getHooksFromPath( $path ) {
	$hooks = array();
	if( $dh = opendir($path) ) {
		while(($file = readdir($dh)) !== false) {
			if( filetype($path.$file) == 'file' ) {
				$hooks = array_merge( $hooks, getHooksFromFile($path.$file) );
			}
		}
		closedir($dh);
	}
	return $hooks;
}

/**
 * Nicely output the array
 * @param $msg A message to show before the value
 * @param $arr An array
 * @param $sort Boolean : wheter to sort the array (Default: true)
 */
function printArray( $msg, $arr, $sort = true ) {
	if($sort) asort($arr); 
	foreach($arr as $v) print "$msg: $v\n";
}


# MAIN

$documented = getHooksFromDoc($doc);
$potential = getHooksFromPath($pathinc);

$todo = array_diff($potential, $documented);
$deprecated = array_diff($documented, $potential);

// let's show the results:
printArray('undocumented', $todo );
printArray('not found', $deprecated );

?>
