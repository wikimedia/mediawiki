<?php

wfLoadSkin( 'BlueSpiceDiscovery' );
$GLOBALS['wgDefaultSkin'] = "bluespicediscovery";

if ( $GLOBALS['wgLogos'] === false  ){
	$GLOBALS['wgLogos'] = [
		'1x' => $GLOBALS['wgResourceBasePath'] . '/skins/BlueSpiceDiscovery/resources/images/bs_logo.png'
	];
}
