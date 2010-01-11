<?php

global $wgCommandLineMode, $IP, $wgMemc;
$wgCommandLineMode = true;
define('MEDIAWIKI', 1);

require dirname( dirname( __FILE__ ) ).implode( DIRECTORY_SEPARATOR, array( "", "includes", "Defines.php" ) );

require dirname( dirname( __FILE__ ) ).DIRECTORY_SEPARATOR."LocalSettings.php";

require "ProfilerStub.php";
require 'GlobalFunctions.php';
require 'Hooks.php';
require "AutoLoader.php";
require 'ProxyTools.php';
require 'ObjectCache.php';
require 'ImageFunctions.php';

$wgMemc =& wfGetMainCache();
