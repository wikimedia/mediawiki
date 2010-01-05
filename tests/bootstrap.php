<?php

$IP = realpath(dirname( __FILE__ ) . '/..');
define('MEDIAWIKI', 1);
global $optionsWithArgs;
$optionsWithArgs = array();

require_once( '../maintenance/commandLine.inc' );
