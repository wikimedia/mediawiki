<?php
/*
 (c) Hallo Welt! Medienwerkstatt GmbH, 2011 GPL
 
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 http://www.gnu.org/copyleft/gpl.html
*/

if ( !defined( 'MEDIAWIKI' ) ) {
	echo 'To install WindowsAzureSDK, put the following line in LocalSettings.php: include_once( "$IP/extensions/WindowsAzureSDK/WindowsAzureSDK.php" );'."\n";
	exit( 1 );
}

$wgExtensionCredits['other'][] = array(
	'path'           => __FILE__,
	'name'           => 'WindowsAzureSDK',
	'author'         => array( 'REALDOLMEN', 'Hallo Welt! Medienwerkstatt GmbH' ),
	'url'            => 'http://www.hallowelt.biz',
	'version'        => '4.1.0',
	'descriptionmsg' => 'windowswzuresdk-desc',
);

$dir = dirname(__FILE__) . '/';
$wgExtensionMessagesFiles['WindowsAzureSDK'] = $dir . 'WindowsAzureSDK.i18n.php'; 

if(!class_exists('Microsoft_WindowsAzure_Diagnostics_Manager')) {
	require_once( $dir.'lib/PHPAzure/library/Microsoft/AutoLoader.php' );
}

if (!defined( 'MICROSOFT_WINDOWS_AZURE_SDK_VERSION' )) {
	define( 'MICROSOFT_WINDOWS_AZURE_SDK_VERSION', '4.1.0' );
}