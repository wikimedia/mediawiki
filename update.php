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

#
# Copy files into installation directories
#
print "Copying files...\n";

copyfile( ".", "wiki.phtml", $IP );
copyfile( ".", "redirect.phtml", $IP );
copyfile( ".", "texvc.phtml", $IP );

$handle = opendir( "./includes" );
while ( false !== ( $f = readdir( $handle ) ) ) {
	if ( "." == $f{0} ) continue;
	copyfile( "./includes", $f, $IP );
}

$handle = opendir( "./stylesheets" );
while ( false !== ( $f = readdir( $handle ) ) ) {
	if ( "." == $f{0} ) continue;
	copyfile( "./stylesheets", $f, $wgStyleSheetDirectory );
}

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

print "Done.\nIf any database changes are necessary, you may have to run\n" .
  "one or more \"patch\" files from the maintenance directory.\n";
exit();

function copyfile( $sdir, $name, $ddir, $perms = 0644 ) {
	global $installOwner, $installGroup;

	$d = "{$ddir}/{$name}";
	if ( copy( "{$sdir}/{$name}", $d ) ) {
		if ( isset( $installOwner ) ) { chown( $d, $installOwner ); }
		if ( isset( $installGroup ) ) { chgrp( $d, $installGroup ); }
		chmod( $d, $perms );
		# print "Copied \"{$name}\" to \"{$ddir}\".\n";
	} else {
		print "Failed to copy file \"{$name}\" to \"{$ddir}\".\n";
		exit();
	}
}

?>
