<?

# Functions for rebuilding the link tracking tables; must
# be included within a script that also includes the Setup.
# See rebuildlinks.php, for example.
#

function rebuildLinkTablesPass1()
{
	global $wgLang;
	$count = 0;
	print "Rebuilding link tables (pass 1).\n";

	$sql = "DROP TABLE IF EXISTS rebuildlinks";
	wfQuery( $sql, DB_WRITE );

	$sql = "CREATE TABLE rebuildlinks (
  rl_f_id int(8) unsigned NOT NULL default 0,
  rl_f_title varchar(255) binary NOT NULL default '',
  rl_to varchar(255) binary NOT NULL default '',
  INDEX rl_to (rl_to) ) TYPE=MyISAM";
	wfQuery( $sql, DB_WRITE );

	$sql = "LOCK TABLES cur READ, rebuildlinks WRITE, interwiki READ";
	wfQuery( $sql, DB_WRITE );

	$sql = "DELETE FROM rebuildlinks";
	wfQuery( $sql, DB_WRITE );

	$sql = "SELECT cur_id,cur_namespace,cur_title,cur_text FROM cur";
	$res = wfQuery( $sql, DB_WRITE );
	$total = wfNumRows( $res );

	$tc = Title::legalChars();
	while ( $row = wfFetchObject( $res ) ) {
		$id = $row->cur_id;
		$ns = $wgLang->getNsText( $row->cur_namespace );
		if ( "" == $ns ) {
			$title = addslashes( $row->cur_title );
		} else {
			$title = addslashes( "$ns:{$row->cur_title}" );
		}
		$text = $row->cur_text;
		$numlinks = preg_match_all( "/\\[\\[([{$tc}]+)(]|\\|)/", $text,
		  $m, PREG_PATTERN_ORDER );

		if ( 0 != $numlinks ) {
			$sql = "INSERT INTO rebuildlinks (rl_f_id,rl_f_title,rl_to) VALUES ";
			for ( $i = 0; $i < $numlinks; ++$i ) {
				$nt = Title::newFromText( $m[1][$i] );
				$dest = addslashes( $nt->getPrefixedDBkey() );

				if ( 0 != $i ) { $sql .= ","; }
				$sql .= "({$id},'{$title}','{$dest}')";
			}
			wfQuery( $sql, DB_WRITE );
		}
		if ( ( ++$count % 1000 ) == 0 ) {
			print "$count of $total articles scanned.\n";
		}
	}
	print "$count articles scanned.\n";
	mysql_free_result( $res );

	$sql = "UNLOCK TABLES";
	wfQuery( $sql, DB_WRITE );
}

function rebuildLinkTablesPass2()
{
	global $wgLang;
	$count = 0;
	print "Rebuilding link tables (pass 2).\n";

	$sql = "LOCK TABLES cur READ, rebuildlinks READ, interwiki READ, " .
	  "links WRITE, brokenlinks WRITE, imagelinks WRITE";
	wfQuery( $sql, DB_WRITE );

	$sql = "DELETE FROM links";
	wfQuery( $sql, DB_WRITE );

	$sql = "DELETE FROM brokenlinks";
	wfQuery( $sql, DB_WRITE );

	$sql = "DELETE FROM links";
	wfQuery( $sql, DB_WRITE );

	$ins = $wgLang->getNsText( Namespace::getImage() );
	$inslen = strlen($ins)+1;
	$sql = "SELECT rl_f_title,rl_to FROM rebuildlinks " .
	  "WHERE rl_to LIKE '$ins:%'";
	$res = wfQuery( $sql, DB_WRITE );

	$sql = "INSERT INTO imagelinks (il_from,il_to) VALUES ";
	$sqlX = "";
	$first = true;
	while ( $row = wfFetchObject( $res ) ) {
		$iname = addslashes( substr( $row->rl_to, $inslen ) );
		$pname = addslashes( $row->rl_f_title );

		if ( ! $first ) { $sqlX .= ","; }
		$first = false;

		$sqlX .= "('{$pname}','{$iname}')";
	}
	if ($sqlX != "") {
	  $sql .= $sqlX;
	  wfFreeResult( $res );
	  wfQuery( $sql, DB_WRITE );
	}

	$sql = "SELECT DISTINCT rl_to FROM rebuildlinks " .
	  "ORDER BY rl_to";
	$res = wfQuery( $sql, DB_WRITE );
	$count = 0;
	$total = wfNumRows( $res );

	while ( $row = wfFetchObject( $res ) ) {
		if ( 0 == strncmp( "$ins:", $row->rl_to, $inslen ) ) { continue; }

		$nt = Title::newFromDBkey( $row->rl_to );
		$id = $nt->getArticleID();
		$to = addslashes( $row->rl_to );

		if ( 0 == $id ) {
			$sql = "SELECT rl_f_id FROM rebuildlinks WHERE rl_to='{$to}'";
			$res2 = wfQuery( $sql, DB_WRITE );

			$sql = "INSERT INTO brokenlinks (bl_from,bl_to) VALUES ";
			$first = true;
			while ( $row2 = wfFetchObject( $res2 ) ) {
				$from = $row2->rl_f_id;
				if ( ! $first ) { $sql .= ","; }
				$first = false;
				$sql .= "({$from},'{$to}')";
			}
			wfFreeResult( $res2 );
			if ( ! $first ) { wfQuery( $sql, DB_WRITE ); }
		} else {
			$sql = "SELECT rl_f_title FROM rebuildlinks WHERE rl_to='{$to}'";
			$res2 = wfQuery( $sql, DB_WRITE );

			$sql = "INSERT INTO links (l_from,l_to) VALUES ";
			$first = true;
			while ( $row2 = wfFetchObject( $res2 ) ) {
				$from = addslashes( $row2->rl_f_title );
				if ( ! $first ) { $sql .= ","; }
				$first = false;
				$sql .= "('{$from}',{$id})";
			}
			wfFreeResult( $res2 );
			if ( ! $first ) { wfQuery( $sql, DB_WRITE ); }
		}
		if ( ( ++$count % 1000 ) == 0 ) {
			print "$count of $total titles processed.\n";
		}
	}
	wfFreeResult( $res );

	$sql = "UNLOCK TABLES";
	wfQuery( $sql, DB_WRITE );

	$sql = "DROP TABLE rebuildlinks";
	wfQuery( $sql, DB_WRITE );
}
?>
