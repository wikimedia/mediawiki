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
	echo 'To install WindowsAzureStorage, put the following line in LocalSettings.php: include_once( "$IP/extensions/WindowsAzureStorage/WindowsAzureStorage.php" );'."\n";
	exit( 1 );
}

$wgExtensionCredits['other'][] = array(
	'path'           => __FILE__,
	'name'           => 'WindowsAzureStorage',
	'author'         => array( 'Hallo Welt! Medienwerkstatt GmbH' ),
	'url'            => 'http://www.hallowelt.biz',
	'version'        => '1.0.0',
	'descriptionmsg' => 'windowsazurestorage-desc',
);

$dir = dirname(__FILE__) . '/';
$wgExtensionMessagesFiles['WindowsAzureStorage'] = $dir . 'WindowsAzureStorage.i18n.php';

$wgAutoloadClasses['WindowsAzureFileBackend'] = $dir . 'includes/filerepo/backend/WindowsAzureFileBackend.php';

/* Those are just development values. You may override them or specify your own backend definition in LocalSettings.php */
$wgFileBackends[] = array(
  'name'        => 'azure-backend',
  'class'       => 'WindowsAzureFileBackend',
  //'wikiId'      => 'some_unique_ID',
  'lockManager' => 'nullLockManager',
  'azureHost'      => 'http://127.0.0.1:10000',
  'azureAccount'   => 'devstoreaccount1',
  'azureKey'       => 'Eby8vdM02xNOcqFlqUwJPLlmEtlCDXJ1OUzFT50uSRZ6IFsuFq2UVErCz4I6tq/K1SZFPTOtr/KBHBeksoGMGw==',
  //'azureContainer' => 'developcontainer',
  
  //IMPORTANT: Mind the container naming conventions! http://msdn.microsoft.com/en-us/library/dd135715.aspx
  'containerPaths' => array(
    'media-public'  => 'media-public',
    'media-thumb'   => 'media-thumb',
    'media-deleted' => 'media-deleted',
    'media-temp'    => 'media-temp',

  )
);