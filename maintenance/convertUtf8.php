<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 */

die("This file is not complete; it's checked in so I don't forget it.");

/*
UTF-8 conversion of DOOOOOOOM

1. Lock the wiki
2. Make a convertlist of all pages
3. Enable CONVERTLOCK mode and switch to UTF-8
4. As quick as possible, convert the cur, images, *links, user, etc tables. Clear cache tables.
5. Unlock the wiki. Attempts to access pages on the convertlist will be trapped to read-only.
6. Go through the list, fixing up old revisions. Remove pages from the convertlist.
*/


if(function_exists("iconv")) {
	# There are likely to be Windows code page 1252 chars in there.
	# Convert them to the proper UTF-8 chars if possible.
	function toUtf8($string) {
		return wfStrencode(iconv("CP1252", "UTF-8", $string));
	}
} else {
	# Will work from plain iso 8859-1 and may corrupt these chars
	function toUtf8($string) {
		return wfStrencode(utf8_encode($string));
	}
}



# user table
$sql = "SELECT user_id,user_name,user_real_name,user_options FROM user";
$res = wfQuery( $sql, DB_WRITE );
print "Converting " . wfNumResults( $res ) . " user accounts:\n";
$n = 0;
while( $s = wfFetchObject( $res ) ) {
	$uname = toUtf8( $s->user_name );
	$ureal = toUtf8( $s->user_real_name );
	$uoptions = toUtf8( $s->user_options );
	if( $uname != wfStrencode( $s->user_name ) ||
		$ureal != wfStrencode( $s->user_real_name ) ||
		$uoptions != wfStrencode( $s->user_options ) ) {
		$now = wfTimestampNow();
		$sql = "UPDATE user
			SET user_name='$uname',user_real_name='$ureal',
				user_options='$uoptions',user_touched='$now'
			WHERE user_id={$s->user_id}";
		wfQuery( $sql, DB_WRITE );
		$wgMemc->delete( "$wgDBname:user:id:{$s->user_id}" );
		$u++;
	}
	if( ++$n % 100 == 0 ) print "$n\n";
}
wfFreeResult( $res );
if( $n ) {
	printf("%2.02%% required conversion.\n\n", $u / $n);
} else {
	print "None?\n\n";
}

# ipblocks
$sql = "SELECT DISTINCT ipb_reason FROM ipblocks";
$res = wfQuery( $sql, DB_WRITE );
print "Converting " . wfNumResults( $res ) . " IP block comments:\n";
$n = 0;
while( $s = wfFetchObject( $res ) ) {
	$ucomment = toUtf8($s->ipb_reason);
	$ocomment = wfStrencode( $s->ipb_reason );
	if( $u != $o ) {
		$sql = "UPDATE ipblocks SET ipb_reason='$ucomment' WHERE ipb_reason='$ocomment'";
		wfQuery( $sql, DB_WRITE );
		$u++;
	}
	if( ++$n % 100 == 0 ) print "$n\n";
}
wfFreeResult( $res );
if( $n ) {
	printf("%2.02%% required conversion.\n\n", $u / $n);
} else {
	print "None?\n\n";
}

# image
$sql = "SELECT img_name,img_description,img_user_text FROM image";
	img_name --> also need to rename files
	img_description
	img_user_text

oldimage
	oi_name
	oi_archive_name --> also need to rename files
	oi_user_text

recentchanges
	rc_user_text
	rc_title
	rc_comment

# searchindex
print "Clearing searchindex... don't forget to rebuild it.\n";
$sql = "DELETE FROM searchindex";
wfQuery( $sql, DB_WRITE );

# linkscc
print "Clearing linkscc...\n";
$sql = "DELETE FROM linkscc";
wfQuery( $sql, DB_WRITE );

# querycache: just rebuild these
print "Clearing querycache...\n";
$sql = "DELETE FROM querycache";
wfQuery( $sql, DB_WRITE );

# objectcache
print "Clearing objectcache...\n";
$sql = "DELETE FROM objectcache";
wfQuery( $sql, DB_WRITE );


function unicodeLinks( $table, $field ) {
	$sql = "SELECT DISTINCT $field FROM $table WHERE $field RLIKE '[\x80-\xff]'";
	$res = wfQuery( $sql, DB_WRITE );
	print "Converting " . wfNumResults( $res ) . " from $table:\n";
	$n = 0;
	while( $s = wfFetchObject( $res ) ) {
		$ulink = toUtf8( $s->$field );
		$olink = wfStrencode( $s->$field );
		$sql = "UPDATE $table SET $field='$ulink' WHERE $field='$olink'";
		wfQuery( $sql, DB_WRITE );
		if( ++$n % 100 == 0 ) print "$n\n";
	}
	wfFreeResult( $res );
	print "Done.\n\n";
}
unicodeLinks( "brokenlinks", "bl_to" );
unicodeLinks( "imagelinks", "il_to" );
unicodeLinks( "categorylinks", "cl_to" );


# The big guys...
$sql = "SELECT cur_id,cur_namespace,cur_title,cur_text,cur_user_text FROM cur
WHERE cur_title rlike '[\x80-\xff]' OR cur_comment rlike '[\x80-\xff]'
OR cur_user_text rlike '[\x80-\xff]' OR cur_text rlike '[\x80-\xff]'";
$res = wfQuery( $sql, DB_WRITE );
print "Converting " . wfNumResults( $res ) . " cur pages:\n";
$n = 0;
while( $s = wfFetchObject( $res ) ) {
	$utitle = toUtf8( $s->cur_title );
	$uuser = toUtf8( $s->cur_user_text );
	$ucomment = toUtf8( $s->cur_comment );
	$utext = toUtf8( $s->cur_text );
	$now = wfTimestampNow();
	
	$sql = "UPDATE cur
		SET cur_title='$utitle',cur_user_text='$uuser',
			cur_comment='$ucomment',cur_text='$utext'
		WHERE cur_id={$s->cur_id}";
	wfQuery( $sql, DB_WRITE );
	#$wgMemc->delete( "$wgDBname:user:id:{$s->user_id}" );
	
	$otitle = wfStrencode( $s->cur_title );
	if( $otitle != $utitle ) {
		# Also update titles in watchlist and old
		$sql = "UPDATE old SET old_title='$utitle'
			WHERE old_namespace={$s->cur_namespace} AND old_title='$otitle'";
		wfQuery( $sql, DB_WRITE );
		
		$ns = IntVal( $s->cur_namespace) & ~1;
		$sql = "UPDATE watchlist SET wl_title='$utitle'
			WHERE wl_namespace=$ns AND wl_title='$otitle'";
		wfQuery( $sql, DB_WRITE );
		$u++;
	}
	
	if( ++$n % 100 == 0 ) print "$n\n";
}
wfFreeResult( $res );
if( $n ) {
	printf("Updated old/watchlist titles on %2.02%%.\n\n", $u / $n);
} else {
	print "Didn't update any old/watchlist titles.\n\n";
}

/*
old
	old_title
	old_text -> may be gzipped
	old_comment
	old_user_text

archive
	ar_title
	ar_text -> may be gzipped
	ar_comment
	ar_user_text
*/

?>
