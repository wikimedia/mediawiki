<?php
/**
 * mwScriptLoader.php
 * Script Loading Library for MediaWiki
 *
 * @file
 * @author Michael Dale mdale@wikimedia.org
 * @date  feb, 2009
 * @link http://www.mediawiki.org/wiki/ScriptLoader Documentation
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

// include WebStart.php
require_once('includes/WebStart.php');

wfProfileIn( 'mwScriptLoader.php' );


if( $wgRequest->isPathInfoBad() ){
	wfHttpError( 403, 'Forbidden',
		'Invalid file extension found in PATH_INFO. ' . 
		'mwScriptLoader must be accessed through the primary script entry point.' );
	return;
}
// Verify the script loader is on:
if ( !$wgEnableScriptLoader ) {
	echo '/*ScriptLoader is not enabled for this site. To enable add the following line to your LocalSettings.php';
	echo '<pre><b>$wgEnableScriptLoader=true;</b></pre>*/';
	echo 'alert(\'Script loader is disabled\');';
	die( 1 );
}

//moved to setup.php
// load the mwEmbed language file:
//$wgExtensionMessagesFiles['mwEmbed'] = "{$IP}/js2/mwEmbed/php/languages/mwEmbed.i18n.php";

// run jsScriptLoader action:
$myScriptLoader = new jsScriptLoader();
$myScriptLoader->doScriptLoader();

wfProfileOut( 'mwScriptLoader.php' );
