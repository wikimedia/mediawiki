<?php

//
// This file includes automatically all default BlueSpice settings
//

if ( !defined( 'MEDIAWIKI' ) ) {
    exit;
}

if ( isset( $bsgSettingsDir ) ) {
	$settingsDir = $bsgSettingsDir;
}
else {
	$settingsDir = "$IP/settings.d";
}

foreach ( glob( $settingsDir . "/*.php" ) as $conffile ) {
	include_once $conffile;
}
