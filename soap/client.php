<?php

require_once('nusoap.php');

$t = 'Hello, World!';

$s = new soapclient( 'http://localhost:80/soap/' );
print "==echoString==\n";
$r = $s->call( 'echoString', array( $t ) );

print( $r . "\n" );
print( "Error: ".$s->getError() . "\n" );

print "\n\n==getArticle==\n";
$r = $s->call( 'getArticle', array( 'Frankfurt' ) );

print_r( $r );
print( "Error: ".$s->getError() . "\n" );

print "\n\n==getArticleByVersion==\n";
$r = $s->call( 'getArticleByVersion', array( 'Frankfurt am Main', 0 ) );

print_r( $r );
print( "Error: ".$s->getError() . "\n" );

print "\n\n==getArticleRevisions==\n";
$r = $s->call( 'getArticleRevisions', array( 'Frankfurt am Main' ) );

print_r( $r );
print( "Error: ".$s->getError() . "\n" );

print "\n\n==searchTitles==\n";
$r = $s->call( 'searchTitles', array( 'furt', 1 ) );

print_r( $r );
print( "Error: ".$s->getError() . "\n" );

?>

