<?php

/*
* API for MediaWiki 1.8+
*
* Copyright (C) 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

/** 
 * This file is the entry point for all API queries. It begins by checking 
 * whether the API is enabled on this wiki; if not, it informs the user that
 * s/he should set $wgEnableAPI to true and exits. Otherwise, it constructs
 * a new ApiMain using the parameter passed to it as an argument in the URL
 * ('?action=') and with write-enabled set to the value of $wgEnableWriteAPI
 * as specified in LocalSettings.php. It then invokes "execute()" on the
 * ApiMain object instance, which produces output in the format sepecified
 * in the URL.
 */

// Initialise common code
require (dirname(__FILE__) . '/includes/WebStart.php');

wfProfileIn('api.php');

// Verify that the API has not been disabled
if (!$wgEnableAPI) {
	echo 'MediaWiki API is not enabled for this site. Add the following line to your LocalSettings.php';
	echo '<pre><b>$wgEnableAPI=true;</b></pre>';
	die(-1);
}

/* Construct an ApiMain with the arguments passed via the URL. What we get back
 * is some form of an ApiMain, possibly even one that produces an error message,
 * but we don't care here, as that is handled by the ctor.
 */
$processor = new ApiMain($wgRequest, $wgEnableWriteAPI);

// Process data & print results
$processor->execute();

// Log what the user did, for book-keeping purposes.
wfProfileOut('api.php');
wfLogProfilingData();

