<?

# Functions for rebuilding the link tracking tables; must
# be included within a script that also includes the Setup.
# See rebuildlinks.php, for example.
#

# Turn this on if you've got memory to burn
$wgUseMemoryTables = false;

function rebuildLinkTablesPass1()
{
	global $wgLang, $wgUseMemoryTables;
	$count = 0;
	print "Rebuilding link tables (pass 1).\n";

	$sql = "DROP TABLE IF EXISTS rebuildlinks";
	wfQuery( $sql, DB_WRITE );

	$sql = "CREATE TABLE rebuildlinks (
  rl_f_id int(8) unsigned NOT NULL default 0,
  rl_f_title varchar(255) binary NOT NULL default '',
  rl_to varchar(255) binary NOT NULL default '',
  INDEX rl_to (rl_to) )";
	if( $wgUseMemoryTables ) $sql .= " TYPE=heap";
	wfQuery( $sql, DB_WRITE );

	$sql = "LOCK TABLES cur READ, rebuildlinks WRITE, interwiki READ, user_newtalk READ";
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
			$first = true;
			$sql = "INSERT INTO rebuildlinks (rl_f_id,rl_f_title,rl_to) VALUES ";
			for ( $i = 0; $i < $numlinks; ++$i ) {
				if( preg_match( '/^(http|https|ftp|mailto|news):/', $m[1][$i] ) ) {
					# an URL link; not for us!
					continue;
				}
				# FIXME: Handle subpage links
				$nt = Title::newFromText( $m[1][$i] );
				if (! $nt)
				{
					$txt = $m[1][$i];
					print "error in '$ns:{$row->cur_title}' :\t'$txt'\n";
					continue;
				}
				if( $nt->getInterwiki() != "" ) {
					# Interwiki links are not stored in the link tables
					continue;
				}
				if( $nt->getNamespace() == Namespace::getSpecial() ) {
					# Special links not stored in link tables
					continue;
				}
				if( $nt->getNamespace() == Namespace::getMedia() ) {
					# treat media: links as image: links
					$nt = Title::makeTitle( Namespace::getImage(), $nt->getDBkey() );
				}

				if (!$first)
					$sql .= ",";
				else
					$first = false;

				$dest = addslashes( $nt->getPrefixedDBkey() );
				$sql .= "({$id},'{$title}','{$dest}')";
			}

			if (! $first) { wfQuery( $sql, DB_WRITE  ); }
		}
		if ( ( ++$count % 1000 ) == 0 ) {
			print "$count of $total articles scanned.\n";
		}
	}
	print "$total articles scanned.\n";
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

	$sql = "DELETE FROM imagelinks";
	wfQuery( $sql, DB_WRITE );

	$ins = $wgLang->getNsText( Namespace::getImage() );
	$inslen = strlen($ins)+1;
	$sql = "SELECT rl_f_title,rl_to FROM rebuildlinks " .
	  "WHERE rl_to LIKE '$ins:%'";
	$res = wfQuery( $sql, DB_WRITE );

	$sql = "INSERT INTO imagelinks (il_from,il_to) VALUES ";
	$first = true;
	while ( $row = wfFetchObject( $res ) )
	{
		$iname = addslashes( substr( $row->rl_to, $inslen ) );
		$pname = addslashes( $row->rl_f_title );

		if ( ! $first )
			$sql .= ",";
		else
			$first = false;

		$sql .= "('{$pname}','{$iname}')";
	}
	wfFreeResult( $res );
	if ( ! $first ) { wfQuery( $sql, DB_WRITE ); }

	$sql = "SELECT DISTINCT rl_to FROM rebuildlinks ORDER BY rl_to";
	$res = wfQuery( $sql, DB_WRITE );
	$count = 0;
	$total = wfNumRows( $res );

	while ( $row = wfFetchObject( $res ) ) {
		if ( 0 == strncmp( "$ins:", $row->rl_to, $inslen ) ) { continue; }

		$nt = Title::newFromDBkey( $row->rl_to );
		if (! $nt)
		{
			print "error pass2: '{$row->rl_to}'\n";
			continue;
		}
		$id = $nt->getArticleID();
		$to = addslashes( $row->rl_to );

		if ( 0 == $id ) {
			$sql = "SELECT DISTINCT rl_f_id FROM rebuildlinks WHERE rl_to='{$to}'";
			$res2 = wfQuery( $sql, DB_WRITE );

			$sql = "INSERT INTO brokenlinks (bl_from,bl_to) VALUES ";
			$first = true;
			while ( $row2 = wfFetchObject( $res2 ) )
			{
				if (! $first)
					$sql .= ",";
				else
					$first = false;

				$from = $row2->rl_f_id;
				$sql .= "({$from},'{$to}')";
			}
			wfFreeResult( $res2 );
			if ( ! $first ) { wfQuery( $sql, DB_WRITE ); }
		} else {
			$sql = "SELECT DISTINCT rl_f_title FROM rebuildlinks WHERE rl_to='{$to}'";
			$res2 = wfQuery( $sql, DB_WRITE );

			$sql = "INSERT INTO links (l_from,l_to) VALUES ";
			$first = true;
			while ( $row2 = wfFetchObject( $res2 ) )
			{
				if (! $first)
					$sql .= ",";
				else
					$first = false;

				$from = addslashes( $row2->rl_f_title );
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
