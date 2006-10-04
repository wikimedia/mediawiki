<?php

/**
 * Maintenance script to create an account and grant it administrator rights
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */
 
require_once( 'commandLine.inc' );

if( !count( $args ) == 2 ) {
	echo( "Please provide a username and password for the new account.\n" );
	die( 1 );
}

$username = $args[0];
$password = $args[1];

echo( wfWikiID() . ": Creating and promoting User:{$username}..." );

# Validate username and check it doesn't exist
$user = User::newFromName( $username );
if( !is_object( $user ) ) {
	echo( "invalid username.\n" );
	die( 1 );
} elseif( 0 != $user->idForName() ) {
	echo( "account exists.\n" );
	die( 1 );
}

# Insert the account into the database
$user->addToDatabase();
$user->setPassword( $password );
$user->setToken();

# Promote user
$user->addGroup( 'sysop' );

# Increment site_stats.ss_users
$ssu = new SiteStatsUpdate( 0, 0, 0, 0, 1 );
$ssu->doUpdate();

echo( "done.\n" );

?>
