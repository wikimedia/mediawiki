<?php

# Rebuild the fulltext search indexes. This may take a while
# depending on the database size and server configuration.

# Rebuilding is faster if you drop the index and recreate it,
# but that will prevent searches from working while it runs.

function dropTextIndex()
{
	if ( wfIndexExists( "searchindex", "si_title" ) ) {
		echo "Dropping index...\n";
		$sql = "ALTER TABLE searchindex DROP INDEX si_title, DROP INDEX si_text";
		$res = wfQuery($sql, DB_WRITE, "dropTextIndex" );
	}
}

function createTextIndex()
{
	echo "Rebuild the index...\n";
	$sql = "ALTER TABLE searchindex ADD FULLTEXT si_title (si_title), " .
	  "ADD FULLTEXT si_text (si_text)";
	$res = wfQuery($sql, DB_WRITE, "createTextIndex" );
}

function rebuildTextIndex()
{
	$sql = "SELECT COUNT(*) AS count FROM cur";
	$res = wfQuery($sql, DB_READ, "rebuildTextIndex" );
	$s = wfFetchObject($res);
	echo "Rebuilding index fields for {$s->count} pages...\n";
	$n = 0;

	$sql = "SELECT cur_id, cur_namespace, cur_title, cur_text FROM cur";
	$res = wfQuery($sql, DB_READ, "rebuildTextIndex" );

	while( $s = wfFetchObject($res) ) {
		$u = new SearchUpdate( $s->cur_id, $s->cur_title, $s->cur_text );
		$u->doUpdate();
		if ( ( (++$n) % 500) == 0) { echo "$n\n"; }
	}
	wfFreeResult( $res );
}

?>
