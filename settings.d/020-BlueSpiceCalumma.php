<?php
wfLoadSkin( 'BlueSpiceCalumma' );
$GLOBALS['wgSkipSkins'] = [ 'chameleon' ];
$GLOBALS['wgDefaultSkin'] = "bluespicecalumma";
$GLOBALS['egChameleonLayoutFile'] = "$IP/skins/BlueSpiceCalumma/layouts/calumma-master.xml";

global $wgLogo, $wgResourceBasePath;
if( $wgLogo == "$wgResourceBasePath/resources/assets/wiki.png" ){
     $wgLogo = "$wgResourceBasePath/extensions/BlueSpiceFoundation/resources/bluespice/images/bs-logo.png";
}
