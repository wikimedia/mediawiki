<?php

# Move a batch of pages
# Usage: php moveBatch.php [-u <user>] [-r <reason>] [-i <interval>] <listfile>
# where
# 	<listfile> is a file where each line has two titles separated by a pipe
# character. The first title is the source, the second is the destination.
#	<user> is the username
#	<reason> is the move reason
#	<interval> is the number of seconds to sleep for after each move

$oldCwd = getcwd();
$optionsWithArgs = array( 'u', 'r', 'i' );
require_once( 'commandLine.inc' );

chdir( $oldCwd );

# Options processing

$filename = 'php://stdin';
$user = 'Move page script';
$reason = '';
$interval = 0;

if ( isset( $args[0] ) ) {
	$filename = $args[0];
}
if ( isset( $options['u'] ) ) {
	$user = $options['u'];
}
if ( isset( $options['r'] ) ) {
	$reason = $options['r'];
}
if ( isset( $options['i'] ) ) {
	$interval = $options['i'];
}

$wgUser = User::newFromName( $user );


# Setup complete, now start

$file = fopen( $filename, 'r' );
if ( !$file ) {
	print "Unable to read file, exiting\n";
	exit;
}

$dbw =& wfGetDB( DB_MASTER );

for ( $linenum = 1; !feof( $file ); $linenum++ ) {
	$line = fgets( $file );
	if ( $line === false ) {
		break;
	}
	$parts = array_map( 'trim', explode( '|', $line ) );
	if ( count( $parts ) != 2 ) {
		print "Error on line $linenum, no pipe character\n";
		continue;
	}
	$source = Title::newFromText( $parts[0] );
	$dest = Title::newFromText( $parts[1] );
	if ( is_null( $source ) || is_null( $dest ) ) {
		print "Invalid title on line $linenum\n";
		continue;
	}


	print $source->getPrefixedText();
	$dbw->begin();
	$err = $source->moveTo( $dest, false, $reason );
	if( $err !== true ) {
		print "\nFAILED: $err";
	}
	$dbw->immediateCommit();
	print "\n";

	if ( $interval ) {
		sleep( $interval );
	}
	wfWaitForSlaves( 5 );
}


?>
