<?php
wfLoadSkin( 'BlueSpiceCalumma' );
$wgDefaultSkin = "bluespicecalumma";
$GLOBALS['egChameleonLayoutFile'] = "$IP/skins/BlueSpiceCalumma/layouts/calumma-master.xml";

global $wgLogo;
if( $wgLogo == "$wgResourceBasePath/resources/assets/wiki.png" ){
    $wgLogo = "$IP/extensions/BlueSpiceFoundation/resources/bluespice/images/bs-logo.png";
}
