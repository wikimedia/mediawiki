<?

# Update already-installed software
#

if ( ! ( is_readable( "./LocalSettings.php" )
  && is_readable( "./AdminSettings.php" ) ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "and AdminSettings.php must exist in this source directory.\n";
	exit();
}

$DP = "./includes";
include_once( "./LocalSettings.php" );
include_once( "./AdminSettings.php" );

if ( $wgUseTeX && ( ! is_executable( "./math/texvc" ) ) ) {
	print "To use math functions, you must first compile texvc by\n" .
	  "running \"make\" in the math directory.\n";
	exit();
}

umask( 000 );
set_time_limit( 0 );

include_once( "{$IP}/Version.php" );
include_once( "{$IP}/Setup.php" );
$wgTitle = Title::newFromText( "Update script" );
$wgCommandLineMode = true;

do_revision_updates();

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
		if ( "CVS" == $f ) continue;
		copyfile( $source, $f, $dest );
	}
}

function do_revision_updates() {
	global $wgSoftwareRevision;

	if ( $wgSoftwareRevision < 1001 ) { update_passwords(); }
}

function update_passwords() {
	$fname = "Update scripte: update_passwords()";
	print "Updating passwords...\n";

	$sql = "SELECT user_id,user_password FROM user";
	$source = wfQuery( $sql, fname );

	while ( $row = mysql_fetch_object( $source ) ) {
		$id = $row->user_id;
		$oldpass = $row->user_password;
		$newpass = md5( $oldpass . $id );

		$sql = "UPDATE user SET user_password='{$newpass}' " .
		  "WHERE user_id={$id}";
		wfQuery( $sql, $fname );
	}
}

?>
