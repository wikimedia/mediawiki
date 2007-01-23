<?php
/**
 * Script to change users skins on the fly.
 * This is for at least MediaWiki 1.10alpha (r19611) and have not been
 * tested with previous versions. It should probably work with 1.7+.
 *
 * Made on an original idea by Fooey (freenode)
 *
 * @author Ashar Voultoiz <hashar@altern.org>
 */

// Options we will use
$options = array( 'quick' );
$optionsWithArgs = array( 'old', 'new' );

// This is a command line script, load tools and parse args
require_once( 'commandLine.inc' );

// Check for mandatory options or print an usage message
if( !(isset($options['old']) && isset($options['new']) ) ) {
print <<<USAGE
This script pass through all users and change their skins from 'oldSkinName'
to 'newSkinName'. There is NO validation about the new skin existence!

Usage: php cleanupSkin.php --old <oldSkinName> --new <newSkinName>
                           [--quick] [--quiet]

Options:
    --old <oldSkinName> : the old skin name
    --new <newSkinName> : new skin name to update users with
    --quick : hides the 5 seconds warning
    --quiet : do not print what is happening


USAGE;
	exit(0);
}

// Load up the arguments:
$oldSkinName = $options['old'];
$newSkinName = $options['new'];
$quick = isset($options['quick']);
$quiet = isset($options['quiet']);

// We list the user by user_id from one of the slave databases
$dbr = wfGetDB( DB_SLAVE );
$result = $dbr->select( 'user',
	array( 'user_id' ),
	array(),
	__FILE__
	);

// The warning message and countdown
if( !$quick ) {
print <<<WARN
The script is about to change the skin for ALL USERS in the database.
Users with skin '$oldSkinName' will be made to use '$newSkinName'.

Abort with control-c in the next five seconds....
WARN;
	require('counter.php');
	for ($i=6;$i>=1;) {
		print_c($i, --$i);
		sleep(1);
	}
	print "\n";
}

// Iterate through the users
while( $id = $dbr->fetchObject( $result ) ) {

	$user = User::newFromId( $id->user_id );

	// We get this users informations
	$curSkinName = $user->getOption( 'skin' );
	$username = $user->getName();

	// Is he using the skin we want to migrate ?
	if( $curSkinName == $oldSkinName ) {

		if(!$quiet) print "Changing skin for $username ('$oldSkinName' -> '$newSkinName'):";

		// Change skin and save it
		$user->setOption( 'skin', $newSkinName );
		$user->saveSettings();

		if(!$quiet) print " OK\n";
	} elseif(!$quiet) {
		print "Not changing '$username' using skin '$curSkinName'\n";
	}
}
print "Done.\n";
?>
