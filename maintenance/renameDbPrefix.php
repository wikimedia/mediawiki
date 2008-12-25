<?php
/**
 * Run this script to after changing $wgDBPrefix on a wiki.
 * The wiki will have to get downtime to do this correctly.
 *
 * @file
 * @ingroup Maintenance
 */
$optionsWithArgs = array('old','new','help');

require_once( 'commandLine.inc' );

if( @$options['help'] || !isset($options['old']) || !isset($options['new']) ) {
	print "usage:updateSpecialPages.php [--help] [--old x] [new y]\n";
	print "  --help      : this help message\n";
	print "  --old x       : old db prefix x\n";
	print "  --old 0       : EMPTY old db prefix x\n";
	print "  --new y       : new db prefix y\n";
	print "  --new 0       : EMPTY new db prefix\n";
	wfDie();
}

// Allow for no old prefix
if( $options['old'] === '0' ) {
	$old = '';
} else {
	// Use nice safe, sane, prefixes
	preg_match( '/^[a-zA-Z]+_$/', $options['old'], $m );
	$old = isset($m[0]) ? $m[0] : false;
}
// Allow for no new prefix
if( $options['new'] === '0' ) {
	$new = '';
} else {
	// Use nice safe, sane, prefixes
	preg_match( '/^[a-zA-Z]+_$/', $options['new'], $m );
	$new = isset($m[0]) ? $m[0] : false;
}

if( $old===false || $new===false ) {
	print "Invalid prefix!\n";
	wfDie();
}
if( $old === $new ) {
	print "Same prefix. Nothing to rename!\n";
	wfDie();
}

print "Renaming DB prefix for tables of $wgDBname from '$old' to '$new'\n";
$count = 0;

$dbw = wfGetDB( DB_MASTER );
$res = $dbw->query( "SHOW TABLES LIKE '".$dbw->escapeLike($old)."%'" );
foreach( $res as $row ) {
	// XXX: odd syntax. MySQL outputs an oddly cased "Tables of X"
	// sort of message. Best not to try $row->x stuff...
	$fields = get_object_vars( $row );
	// Silly for loop over one field...
	foreach( $fields as $resName => $table ) {
		// $old should be regexp safe ([a-zA-Z_])
		$newTable = preg_replace( '/^'.$old.'/',$new,$table);
		print "Renaming table $table to $newTable\n";
		$dbw->query( "RENAME TABLE $table TO $newTable" );
	}
	$count++;
}
print "Done! [$count tables]\n";

