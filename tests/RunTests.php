<?php
error_reporting( E_ALL );
define( "MEDIAWIKI", true );

require_once( 'PHPUnit.php' );
require_once( 'DatabaseTest.php' );

$suite = new PHPUnit_TestSuite( "DatabaseTest" );
$result = PHPUnit::run( $suite );
echo $result->toString();

?>