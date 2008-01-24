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

// Initialise common code
require (dirname(__FILE__) . '/includes/WebStart.php');

wfProfileIn('api.php');

// URL safety checks
//
// See RawPage.php for details; summary is that MSIE can override the
// Content-Type if it sees a recognized extension on the URL, such as
// might be appended via PATH_INFO after 'api.php'.
//
// Some data formats can end up containing unfiltered user-provided data
// which will end up triggering HTML detection and execution, hence
// XSS injection and all that entails.
//
// Ensure that all access is through the canonical entry point...
//
if( isset( $_SERVER['SCRIPT_URL'] ) ) {
	$url = $_SERVER['SCRIPT_URL'];
} else {
	$url = $_SERVER['PHP_SELF'];
}
if( strcmp( "$wgScriptPath/api$wgScriptExtension", $url ) ) {
	wfHttpError( 403, 'Forbidden',
		'API must be accessed through the primary script entry point.' );
	return;
}

// Verify that the API has not been disabled
if (!$wgEnableAPI) {
	echo 'MediaWiki API is not enabled for this site. Add the following line to your LocalSettings.php';
	echo '<pre><b>$wgEnableAPI=true;</b></pre>';
	die(-1);
}

$processor = new ApiMain($wgRequest, $wgEnableWriteAPI);
$processor->execute();

wfProfileOut('api.php');
wfLogProfilingData();
?>
