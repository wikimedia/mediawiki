<?php

if ( isset( $_SERVER ) && array_key_exists( 'REQUEST_METHOD', $_SERVER ) ) {
        print "This script must be run from the command line\n";
        exit();
}


require_once('nusoap.php');

$t = 'Hello, World!';

$s = new soapclient( 'http://mediawiki.mormo.org:80/soap/' );
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
$r = $s->call( 'searchTitles', array( 'Frankfurt', 0 ) );

print_r( $r );
print( "Error: ".$s->getError() . "\n" );

?>

