<?php

# delete a batch of pages
# Usage: php deleteBatch.php [-u <user>] [-r <reason>] [-i <interval>] <listfile>
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
$user = 'Delete page script';
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
	$line = trim( fgets( $file ) );
	if ( $line === false ) {
		break;
	}
	$page = Title::newFromText( $line );
	if ( is_null( $page ) ) {
		print "Invalid title '$line' on line $linenum\n";
		continue;
	}
	if( !$page->exists() ) {
		print "Skipping nonexistent page '$line'\n";
		continue;
	}


	print $page->getPrefixedText();
	$dbw->begin();
	if( $page->getNamespace() == NS_IMAGE ) {
		$art = new ImagePage( $page );
	} else {
		$art = new Article( $page );
	}
	$success = $art->doDeleteArticle( $reason );
	$dbw->immediateCommit();
	if ( $success ) {
		print "\n";
	} else {
		print " FAILED\n";
	}

	if ( $interval ) {
		sleep( $interval );
	}
	wfWaitForSlaves( 5 );
}


?>
