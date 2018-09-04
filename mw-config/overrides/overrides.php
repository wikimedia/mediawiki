<?php

$GLOBALS['wgAutoloadClasses']['BsWebInstaller'] = __DIR__ . '/includes/BsWebInstaller.php';
$GLOBALS['wgAutoloadClasses']['BsWebInstallerOptions'] = __DIR__ . '/includes/BsWebInstallerOptions.php';
$GLOBALS['wgAutoloadClasses']['BsWebInstallerOutput'] = __DIR__ . '/includes/BsWebInstallerOutput.php';
$GLOBALS['wgAutoloadClasses']['BsWebInstallerDBConnect'] = __DIR__ . '/includes/BsWebInstallerDBConnect.php';
$GLOBALS['wgAutoloadClasses']['BSWebInstallerDBSettings'] = __DIR__ . '/includes/BSWebInstallerDBSettings.php';
//$GLOBALS['wgAutoloadClasses']['BsLocalSettingsGenerator'] = __DIR__ . '/includes/BsLocalSettingsGenerator.php';
require_once( __DIR__ . '/includes/BsLocalSettingsGenerator.php');

$overrides['LocalSettingsGenerator'] = 'BsLocalSettingsGenerator';
$overrides['WebInstaller'] = 'BsWebInstaller';

$GLOBALS['wgDefaultSkin'] = strtolower( 'BlueSpiceCalumma' );

// for Echo:
$GLOBALS['wgEchoCluster'] = false;
