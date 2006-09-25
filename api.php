<?php


/**
* API for MediaWiki 1.8+
*
* Copyright (C) 2006 Yuri Astrakhan <FirstnameLastname@gmail.com>
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
* 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
* http://www.gnu.org/copyleft/gpl.html
*/

$apiStartTime = microtime(true);

/**
 * When no format parameter is given, this format will be used
 */
define('API_DEFAULT_FORMAT', 'xmlfm');

$apidir = 'includes/api';
/**
 * List of classes and containing files.
 */
$apiAutoloadClasses = array (

	'ApiMain' => "$apidir/ApiMain.php",

		// Utility classes
	'ApiBase' => "$apidir/ApiBase.php",
	'ApiQueryBase' => "$apidir/ApiQueryBase.php",
	'ApiResult' => "$apidir/ApiResult.php",

		// Formats
	'ApiFormatBase' => "$apidir/ApiFormatBase.php",
	'ApiFormatYaml' => "$apidir/ApiFormatYaml.php",
	'ApiFormatXml' => "$apidir/ApiFormatXml.php",
	'ApiFormatJson' => "$apidir/ApiFormatJson.php",

		// Modules (action=...) - should match the $apiModules list
	'ApiHelp' => "$apidir/ApiHelp.php",
	'ApiLogin' => "$apidir/ApiLogin.php",
	'ApiQuery' => "$apidir/ApiQuery.php",

		// Query items (what/list=...)
	'ApiQueryContent' => "$apidir/ApiQueryContent.php",

	'ApiPageSet' => "$apidir/ApiPageSet.php"
);

/**
 * List of available modules: action name => module class
 * The class must also be listed in the $apiAutoloadClasses array. 
 */
$apiModules = array (
	'help' => 'ApiHelp',
	'login' => 'ApiLogin',
	'query' => 'ApiQuery'
);

/**
 * List of available formats: format name => format class
 * The class must also be listed in the $apiAutoloadClasses array. 
 */
$apiFormats = array (
	'json' => 'ApiFormatJson',
	'jsonfm' => 'ApiFormatJson',
	'xml' => 'ApiFormatXml',
	'xmlfm' => 'ApiFormatXml',
	'yaml' => 'ApiFormatYaml',
	'yamlfm' => 'ApiFormatYaml'
);

// Initialise common code
require_once ('./includes/WebStart.php');
wfProfileIn('api.php');

// Verify that the API has not been disabled
// The next line should be 
//      if (isset ($wgEnableAPI) && !$wgEnableAPI) {
// but will be in a safe mode until api is stabler
if (!isset ($wgEnableAPI) || !$wgEnableAPI) {
	echo 'MediaWiki API is not enabled for this site. Add the following line to your LocalSettings.php';
	echo '<pre><b>$wgEnableAPI=true;</b></pre>';
	die(-1);
}

ApiInitAutoloadClasses($apiAutoloadClasses);
$processor = new ApiMain($apiStartTime, $apiModules, $apiFormats);
$processor->Execute();

wfProfileOut('api.php');
wfLogProfilingData();
exit; // Done!

function ApiInitAutoloadClasses($apiAutoloadClasses) {

	// Append $apiAutoloadClasses to $wgAutoloadClasses
	global $wgAutoloadClasses;
	if (isset ($wgAutoloadClasses)) {
		$wgAutoloadClasses = array_merge($wgAutoloadClasses, $apiAutoloadClasses);
	} else {
		$wgAutoloadClasses = $apiAutoloadClasses;
	}
}
?>