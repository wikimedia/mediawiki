<?php

global $wgCommandLineMode, $IP;
$wgCommandLineMode = true;
$IP = dirname( dirname( __FILE__ ) );
define('MEDIAWIKI', 1);
ini_set( 'include_path', "$IP:" .ini_get( 'include_path' ) );

require ( "$IP/includes/Defines.php" );
require ( "$IP/includes/DefaultSettings.php" );
require ( "$IP/LocalSettings.php" );

require 'ProfilerStub.php';
require 'GlobalFunctions.php';
require 'Hooks.php';
require 'AutoLoader.php';
