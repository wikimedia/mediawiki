<?php

# Convert from the old links schema (string->ID) to the new schema (ID->ID)
# This hasn't been tested yet, so expect bugs

# The wiki should be put into read-only mode while this script executes

include_once( "commandLine.inc" );

# Check if it's already done

$res = wfQuery( "SELECT * FROM links LIMIT 1", DB_WRITE );
if ( !wfNumRows( $res ) ) {
	print "No rows to convert. Updating schema...\n";
	createTable();
} else {
	$row = wfFetchObject( $res );
	if ( is_numeric( $row->l_from ) ) {
		print "Schema already converted\n";
		exit;
	}
	
	# Create a title -> cur_id map

	print "Loading IDs...";

	wfBufferSQLResults( false );
	$res = wfQuery( "SELECT cur_namespace,cur_title,cur_id FROM cur", DB_WRITE );
	$ids = array();

	while ( $row = wfFetchObject( $res ) ) {
		$title = $row->cur_title;
		if ( $row->cur_namespace ) {
			$title = $wgLang->getNsText( $row->cur_namespace ) . ":$title";
		}
		$ids[$title] = $row->cur_id;
	}
	wfFreeResult( $res );

	print "done\n";

	# Now, load in all the links and create a links table in RAM
	print "Processing links...\n";
	$res = wfQuery( "SELECT * FROM links", DB_WRITE );
	$links = array();
	$numBad = 0;

	while ( $row = wfFetchObject( $res ) ) {
		if ( array_key_exists( $row->l_from, $ids ) ) {
			$links[$ids[$row->l_from]][$row->l_to] = 1;
		} else {
			$numBad ++;
		}
	}

	print "Done, $numBad invalid titles\n";

	# Save it to a new table
	createTable();
	$sql = "INSERT INTO links_temp(l_from,l_to) VALUES ";

	$first = true;
	foreach( $links as $from => $toArray ) {
		foreach ( $toArray as $to => $one ) {
			if ( $first ) {
				$first = false;
			} else {
				$sql .= ",";
			}
			$sql .= "($from,$to)";
		}
	}

	wfQuery( $sql, DB_WRITE );
}

# Swap in the new table
wfQuery( "RENAME TABLE links TO links_backup, links_temp TO links", DB_WRITE );

print "Conversion complete. The old table remains at links_backup, delete at your leisure.\n";

function createTable() {
	wfQuery( "CREATE TABLE links_temp (
	  l_from int(8) unsigned NOT NULL default '0',
	  l_to int(8) unsigned NOT NULL default '0',
	  UNIQUE KEY l_from(l_from,l_to),
	  KEY (l_to))", DB_WRITE);
}

?>
