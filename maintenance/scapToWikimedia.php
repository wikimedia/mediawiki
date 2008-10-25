<?php

require_once( dirname( __FILE__ ) . '/commandLine.inc');

$url = 'http://en.wikipedia.org/w/api.php?action=query&meta=siteinfo&format=php';
$data = @unserialize( Http::get( $url ) );
$rev = @$data['query']['general']['rev'];
if ( !$rev )
	die( "Unable to fetch latest SVN revision from {$url}\n" );

print "Updating to revision {$rev}\n";
chdir( dirname( __FILE__ ) . '/..' );
system( "svn up -r {$rev}" );
