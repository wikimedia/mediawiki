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

$wgApiStartTime = microtime(true);

/**
 * When no format parameter is given, this format will be used
 */
define('API_DEFAULT_FORMAT', 'xmlfm');

/**
 * Location of all api-related files (must end with a slash '/')
 */
define('API_DIR', 'includes/api/');

/**
 * List of classes and containing files.
 */
$wgApiAutoloadClasses = array (

	'ApiMain' => API_DIR . 'ApiMain.php',

		// Utility classes
	'ApiBase' => API_DIR . 'ApiBase.php',
	'ApiQueryBase' => API_DIR . 'ApiQueryBase.php',
	'ApiResult' => API_DIR . 'ApiResult.php',
	'ApiPageSet' => API_DIR . 'ApiPageSet.php',

		// Formats
	'ApiFormatBase' => API_DIR . 'ApiFormatBase.php',
	'ApiFormatYaml' => API_DIR . 'ApiFormatYaml.php',
	'ApiFormatXml' => API_DIR . 'ApiFormatXml.php',
	'ApiFormatJson' => API_DIR . 'ApiFormatJson.php',

		// Modules (action=...) - should match the $apiModules list
	'ApiHelp' => API_DIR . 'ApiHelp.php',
	'ApiLogin' => API_DIR . 'ApiLogin.php',
	'ApiQuery' => API_DIR . 'ApiQuery.php',

		// Query items (meta/prop/list=...)
	'ApiQuerySiteinfo' => API_DIR . 'ApiQuerySiteinfo.php',
	'ApiQueryInfo' => API_DIR . 'ApiQueryInfo.php',
	'ApiQueryRevisions' => API_DIR . 'ApiQueryRevisions.php',
	'ApiQueryAllpages' => API_DIR . 'ApiQueryAllpages.php'
);

/**
 * List of available modules: action name => module class
 * The class must also be listed in the $wgApiAutoloadClasses array. 
 */
$wgApiModules = array (
	'help' => 'ApiHelp',
	'login' => 'ApiLogin',
	'query' => 'ApiQuery'
);

/**
 * List of available formats: format name => format class
 * The class must also be listed in the $wgApiAutoloadClasses array. 
 */
$wgApiFormats = array (
	'json' => 'ApiFormatJson',
	'jsonfm' => 'ApiFormatJson',
	'xml' => 'ApiFormatXml',
	'xmlfm' => 'ApiFormatXml',
	'yaml' => 'ApiFormatYaml',
	'yamlfm' => 'ApiFormatYaml'
);

// Initialise common code
require (dirname(__FILE__) . '/includes/WebStart.php');
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

$wgAutoloadClasses = array_merge($wgAutoloadClasses, $wgApiAutoloadClasses);
$processor = new ApiMain($wgApiStartTime, $wgApiModules, $wgApiFormats);
$processor->execute();

wfProfileOut('api.php');
wfLogProfilingData();
exit; // Done!
?>
