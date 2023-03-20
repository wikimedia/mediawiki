<?php

wfLoadSkin( 'BlueSpiceDiscovery' );
$GLOBALS['wgDefaultSkin'] = "bluespicediscovery";
$GLOBALS['wgSkipSkins'] = [
    'minerva',
    'monobook',
    'timeless',
    'vector'
];

$GLOBALS['bsgDiscoveryMetaItemsHeader'] = [ "page-sentence" ];
$GLOBALS['bsgDiscoveryMetaItemsFooter'] = [ "categories", "rating", "recommendations" ];
