<?php

# Rebuild the fulltext search indexes. This may take a while
# depending on the database size and server configuration.

# Rebuilding is faster if you drop the index and recreate it,
# but that will prevent searches from working while it runs.

define( "RTI_CHUNK_SIZE", 500 );

function dropTextIndex( &$database )
{
	if ( wfIndexExists( "searchindex", "si_title" ) ) {
		echo "Dropping index...\n";
		$sql = "ALTER TABLE searchindex DROP INDEX si_title, DROP INDEX si_text";
		$database->query($sql, "dropTextIndex" );
	}
	# Truncate table, in an attempt to bring the slaves to a consistent state
	# (zwinger was accidentally written to)
	$database->query( "TRUNCATE TABLE searchindex", "dropTextIndex" );
}

function createTextIndex( &$database )
{
	echo "Rebuild the index...\n";
	$sql = "ALTER TABLE searchindex ADD FULLTEXT si_title (si_title), " .
	  "ADD FULLTEXT si_text (si_text)";
	$database->query($sql, "createTextIndex" );
}

function rebuildTextIndex( &$database )
{
	global $wgDisableSearchUpdate;
	$wgDisableSearchUpdate = false;

	$sql = "SELECT MAX(cur_id) AS count FROM cur";
	$res = $database->query($sql, "rebuildTextIndex" );
	$s = wfFetchObject($res);
	$count = $s->count;
	echo "Rebuilding index fields for {$count} pages...\n";
	$n = 0;

	while ( $n < $count ) {
		print "$n\n";
		$end = $n + RTI_CHUNK_SIZE - 1;
		$sql = "SELECT cur_id, cur_namespace, cur_title, cur_text FROM cur WHERE cur_id BETWEEN $n AND $end";
		$res = $database->query($sql, "rebuildTextIndex" );

		while( $s = wfFetchObject($res) ) {
			$u = new SearchUpdate( $s->cur_id, $s->cur_title, $s->cur_text );
			$u->doUpdate();
		}
		wfFreeResult( $res );
		$n += RTI_CHUNK_SIZE;
	}
}

?>
