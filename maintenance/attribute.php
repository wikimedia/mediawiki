<?php
# Script for re-attributing edits
require_once( "commandLine.inc" );

# Parameters
if ( count( $args ) < 2 ) {
	print "Not enough parameters\n";
	if ( $wgWikiFarm ) {
		print "Usage: php attribute.php <language> <site> <source> <destination>\n";
	} else {
		print "Usage: php attribute.php <source> <destination>\n";
	}
	exit;
}

$source = $args[0];
$dest = $args[1];

$eSource = wfStrencode( $source );
$eDest = wfStrencode( $dest );

# Get user id
$res = wfQuery( "SELECT user_id FROM user WHERE user_name='$eDest'", DB_READ );
$row = wfFetchObject( $res );
if ( !$row ) {
	print "Warning: the target name \"$dest\" does not exist";
	$uid = 0;
} else {
	$uid = $row->user_id;
}

# Initialise files
$logfile = fopen( "attribute.log", "a" );
$sqlfile = fopen( "attribute.sql", "a" );

fwrite( $logfile, "* $source &rarr; $dest\n" );

fwrite( $sqlfile, 
"-- Changing attribution SQL file
-- Generated with attribute.php
-- $source -> $dest ($uid)
");

$omitTitle = "Wikipedia:Changing_attribution_for_an_edit";

# Get old entries
print "\nOld entries\n\n";

$res = wfQuery( "SELECT old_namespace, old_title, old_id, old_timestamp FROM old WHERE old_user_text='$eSource'", DB_READ );
$row = wfFetchObject( $res );

if ( $row ) {
/*
	if ( $row->old_title=='Votes_for_deletion' && $row->old_namespace == 4 ) {
		# We don't have that long
		break;
	}
*/
	fwrite( $logfile, "**Old IDs: " );
	fwrite( $sqlfile, "UPDATE old SET old_user=$uid, old_user_text='$eDest' WHERE old_id IN (\n" );
	
	for ( $first=true; $row; $row = wfFetchObject( $res ) ) {
		$ns = $wgLang->getNsText( $row->old_namespace );
		if ( $ns ) {
			$fullTitle = "$ns:{$row->old_title}";
		} else {
			$fullTitle = $row->old_title;
		}
		if ( $fullTitle == $omitTitle ) {
			continue;
		}

		print "$fullTitle\n";
		$url = "http://$lang.wikipedia.org/w/wiki.phtml?title=" . urlencode( $fullTitle );
		$eTitle = wfStrencode( $row->old_title );
/*		
		# Find previous entry
		$lastres = wfQuery( "SELECT old_id FROM old WHERE
			old_title='$eTitle' AND old_namespace={$row->old_namespace} AND
			old_timestamp<'{$row->old_timestamp}' ORDER BY inverse_timestamp LIMIT 1", DB_READ );
		$lastrow = wfFetchObject( $lastres );		
		if ( $lastrow ) {
			$last = $lastrow->old_id;
			$url .= "&diff={$row->old_id}&oldid=$last";
		} else {*/
			$url .= "&oldid={$row->old_id}";
#		}
		
		# Output
		fwrite( $sqlfile, "      " );
		if ( $first ) {
			$first = false;
		} else {
			fwrite( $sqlfile, ", " );
			fwrite( $logfile, ", " );
		}

		fwrite( $sqlfile, "{$row->old_id} -- $url\n" );
		fwrite( $logfile, "[$url {$row->old_id}]" );

	}
	fwrite( $sqlfile, ");\n" );
	fwrite( $logfile, "\n" );
}

# Get cur entries
print "\n\nCur entries\n\n";

$res = wfQuery( "SELECT cur_title, cur_namespace, cur_timestamp, cur_id FROM cur WHERE cur_user_text='$eSource'",
	DB_READ );
$row = wfFetchObject( $res );
if ( $row ) {
	fwrite( $sqlfile, "\n\nUPDATE cur SET cur_user=$uid, cur_user_text='$eDest' WHERE cur_id IN(\n" );
	fwrite( $logfile, "**Cur entries:\n" );
	for ( $first=true; $row; $row = wfFetchObject( $res ) ) {
		$ns = $wgLang->getNsText( $row->cur_namespace );
		if ( $ns ) {
			$fullTitle = "$ns:{$row->cur_title}";
		} else {
			$fullTitle = $row->cur_title;
		}
		if ( $fullTitle == $omitTitle ) {
			continue;
		}
		$url = "http://$lang.wikipedia.org/wiki/" . urlencode($fullTitle);
		if ( $first ) {
			fwrite( $sqlfile, "      " );
			$first = false;
		} else {
			fwrite( $sqlfile, "    , " );
		}
		fwrite( $sqlfile, "{$row->cur_id} -- $url\n" );
		fwrite( $logfile, "***[[$fullTitle]] {$row->cur_timestamp}\n" );
		print "$fullTitle\n";
	}
	fwrite( $sqlfile, ");\n" );
}
print "\n";

fclose( $sqlfile );
fclose( $logfile );

?>
