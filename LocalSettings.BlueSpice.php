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
	$settingsDir = __DIR__ . "/settings.d";
}

foreach ( glob( $settingsDir . "/*.php" ) as $conffile ) {
  $localConfFile = preg_replace( '/\\.[^.\\s]{3,4}$/', '', $conffile ) . ".local.php";

  if ( file_exists( $localConfFile ) ){
    include_once $localConfFile;
  } else {
    include_once $conffile;
  }

}
