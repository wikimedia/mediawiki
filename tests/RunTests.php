<?php
error_reporting( E_ALL );
define( "MEDIAWIKI", true );

require_once( 'PHPUnit.php' );

$tests = array(
	'GlobalTest',
	'DatabaseTest',
	);
foreach( $tests as $test ) {
	require_once( $test . '.php' );
	$suite = new PHPUnit_TestSuite( $test );
	$result = PHPUnit::run( $suite );
	echo $result->toString();
}

?>