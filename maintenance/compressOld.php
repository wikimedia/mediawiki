<?php

# Compress the old table, old_flags=gzip

require_once( "commandLine.inc" );
require_once( "compressOld.inc" );

if( !function_exists( "gzdeflate" ) ) {
	print "You must enable zlib support in PHP to compress old revisions!\n";
	print "Please see http://www.php.net/manual/en/ref.zlib.php\n\n";
	die();
}

print "Depending on the size of your database this may take a while!\n";
print "If you abort the script while it's running it shouldn't harm anything,\n";
print "but if you haven't backed up your data, you SHOULD abort now!\n\n";
print "Press control-c to abort first (will proceed automatically in 5 seconds)\n";
sleep(5);

$n = 0;
if( !empty( $argv[1] ) ) {
	$n = intval( $argv[1] );
}
compressOldPages( $n );

print "Done.\n";
exit();

?>
