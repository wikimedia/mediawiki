<?


/*

  TODO: Links cache will be very unhappy without a table like this //e23

	CREATE TABLE linkscc (lcc_pageid INT UNSIGNED NOT NULL UNIQUE KEY,
                             lcc_title VARCHAR(255) NOT NULL UNIQUE KEY,
                             lcc_cacheobj MEDIUMBLOB NOT NULL);

*/



# Update already-installed software
#

if ( ! ( is_readable( "./LocalSettings.php" )
  && is_readable( "./AdminSettings.php" ) ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "and AdminSettings.php must exist in this source directory.\n";
	exit();
}

$IP = "./includes";
include_once( "./LocalSettings.php" );
include_once( "./AdminSettings.php" );

if ( $wgUseTeX && ( ! is_executable( "./math/texvc" ) ) ) {
	print "To use math functions, you must first compile texvc by\n" .
	  "running \"make\" in the math directory.\n";
	exit();
}

umask( 000 );
set_time_limit( 0 );

include_once( "Version.php" );
include_once( "{$IP}/Setup.php" );
include_once( "./maintenance/InitialiseMessages.inc" );

$wgTitle = Title::newFromText( "Update script" );
$wgCommandLineMode = true;
$wgAlterSpecs = array();

do_revision_updates();
alter_ipblocks();
initialiseMessages();

#
# Run ALTER TABLE queries.
#
if ( count( $wgAlterSpecs ) ) {
	$rconn = mysql_connect( $wgDBserver, $wgDBadminuser, $wgDBadminpassword );
	mysql_select_db( $wgDBname );
	print "\n";
	foreach ( $wgAlterSpecs as $table => $specs ) {
		$sql = "ALTER TABLE $table $specs";
		print "$sql;\n";
		$res = mysql_query( $sql, $rconn );
		if ( $res === false ) {
			print "MySQL error: " . mysql_error( $rconn ) . "\n";
		}
	}
	mysql_close( $rconn );
}


#
# Copy files into installation directories
#
print "Copying files...\n";

copyfile( ".", "wiki.phtml", $IP );
copyfile( ".", "redirect.phtml", $IP );
copyfile( ".", "texvc.phtml", $IP );

copydirectory( "./includes", $IP );
copydirectory( "./stylesheets", $wgStyleSheetDirectory );

copyfile( "./images", "wiki.png", $wgUploadDirectory );
copyfile( "./languages", "Language.php", $IP );
copyfile( "./languages", "Language" . ucfirst( $wgLanguageCode ) . ".php", $IP );

$fp = fopen( $wgDebugLogFile, "w" );
if ( false === $fp ) {
	print "Could not create log file \"{$wgDebugLogFile}\".\n";
	exit();
}
$d = date( "Y-m-d H:i:s" );
fwrite( $fp, "Wiki debug log file created {$d}\n\n" );
fclose( $fp );

if ( $wgUseTeX ) {
	copyfile( "./math", "texvc", "{$IP}/math", 0775 );
	copyfile( "./math", "texvc_test", "{$IP}/math", 0775 );
	copyfile( "./math", "texvc_tex", "{$IP}/math", 0775 );
}

copyfile( ".", "Version.php", $IP );

print "Done.\n";
exit();

#
#
#

function copyfile( $sdir, $name, $ddir, $perms = 0664 ) {
	global $wgInstallOwner, $wgInstallGroup;

	$d = "{$ddir}/{$name}";
	if ( copy( "{$sdir}/{$name}", $d ) ) {
		if ( isset( $wgInstallOwner ) ) { chown( $d, $wgInstallOwner ); }
		if ( isset( $wgInstallGroup ) ) { chgrp( $d, $wgInstallGroup ); }
		chmod( $d, $perms );
		# print "Copied \"{$name}\" to \"{$ddir}\".\n";
	} else {
		print "Failed to copy file \"{$name}\" to \"{$ddir}\".\n";
		exit();
	}
}

function copydirectory( $source, $dest ) {
	$handle = opendir( $source );
	while ( false !== ( $f = readdir( $handle ) ) ) {
		if ( "." == $f{0} ) continue;
		# Windows turned all my CVS->cvs :(
		if ( !strcasecmp ( "CVS", $f ) ) continue;
		copyfile( $source, $f, $dest );
	}
}

function readconsole() {
	$fp = fopen( "php://stdin", "r" );
	$resp = trim( fgets( $fp ) );
	fclose( $fp );
	return $resp;
}

function do_revision_updates() {
	global $wgSoftwareRevision;
	if ( $wgSoftwareRevision < 1001 ) { update_passwords(); }
}

function update_passwords() {
	$fname = "Update script: update_passwords()";
	print "\nIt appears that you need to update the user passwords in your\n" .
	  "database. If you have already done this (if you've run this update\n" .
	  "script once before, for example), doing so again will make all your\n" .
	  "user accounts inaccessible, so be sure you only do this once.\n" .
	  "Update user passwords? (yes/no)";

	$resp = readconsole();
    if ( ! ( "Y" == $resp{0} || "y" == $resp{0} ) ) { return; }

	$sql = "SELECT user_id,user_password FROM user";
	$source = wfQuery( $sql, DB_READ, fname );

	while ( $row = mysql_fetch_object( $source ) ) {
		$id = $row->user_id;
		$oldpass = $row->user_password;
		$newpass = md5( "{$id}-{$oldpass}" );

		$sql = "UPDATE user SET user_password='{$newpass}' " .
		  "WHERE user_id={$id}";
		wfQuery( $sql, DB_WRITE, $fname );
	}
}

function alter_ipblocks() {
	global $wgAlterSpecs;
	
	if ( wfFieldExists( "ipblocks", "ipb_id" ) ) {
		return;
	}
	
	if ( array_key_exists( "ipblocks", $wgAlterSpecs ) ) {
		$wgAlterSpecs["ipblocks"] .= ",";
	}

	$wgAlterSpecs["ipblocks"] .=
		"ADD ipb_auto tinyint(1) NOT NULL default '0', ".
		"ADD ipb_id int(8) NOT NULL auto_increment,".
		"ADD PRIMARY KEY (ipb_id)";
}

?>
