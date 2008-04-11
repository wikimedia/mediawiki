#!/usr/bin/env php
<?php

require 't/Search.inc';

$db = buildTestDatabase( array( 'page', 'revision', 'text', 'searchindex' ) );
if( is_null( $db ) ){
	fail( 'no db' );
	exit();
}
$t = new SearchEngineTest( new SearchMySQL( $db ) );
$t->run();

/* vim: set filetype=php: */
