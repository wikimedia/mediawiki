<?

# Functions for rebuilding the link tracking tables; must
# be included within a script that also includes the Setup.
# See rebuildlinks.php, for example.
#

# Turn this on if you've got memory to burn
$wgUseMemoryTables = false;

# Buffer this many rows before inserting them all in one sweep. More
# than about 1000 will probably not increase speed significantly on
# most setups.
/* private */ $rowbuf_size = 2000; // 2000 rows ~ 80 kB

function rebuildLinkTables()
{
	global $wgLang, $wgUseMemoryTables, $rowbuf_size;

	print "This script may take many hours to complete. If you abort during that time,\n";
	print "your wiki will be in an inconsistent state and you may have problems running\n";
	print "this script again. If you are going to abort, this is the time to do it.\n\n";
	print "Press control-c to abort (will proceed automatically in 15 seconds)\n";
	sleep(15);

	$count = 0;
	print "Rebuilding link tables.\n";

	print "Setting AUTOCOMMIT=1\n";
	wfQuery("SET SESSION AUTOCOMMIT=1", DB_WRITE);

	print "Deleting old data in links table.\n";
	$sql = "DELETE FROM links";
	wfQuery( $sql, DB_WRITE );

	print "Deleting old data in brokenlinks table.\n";
	$sql = "DELETE FROM brokenlinks";
	wfQuery( $sql, DB_WRITE );

	print "Deleting old data in imagelinks table.\n";
	$sql = "DELETE FROM imagelinks";
	wfQuery( $sql, DB_WRITE );

	print "\nAdding temporary unique index on links, brokenlinks and imagelinks.\n";
	print "->If build aborts now, you probably aborted a previous build. If that is\n";
	print "  the case, you can clean up the remains with the following SQL commands,\n";
	print "  and then try again.\n";
	print "  ALTER TABLE links DROP INDEX tmp_unique;\n";
	print "  ALTER TABLE brokenlinks DROP INDEX tmp_unique;\n";
	print "  ALTER TABLE imagelinks DROP INDEX tmp_unique;\n\n";

	$sql = "ALTER TABLE links ADD UNIQUE tmp_unique (l_from, l_to)";
	wfQuery( $sql, DB_WRITE );
	$sql = "ALTER TABLE brokenlinks ADD UNIQUE tmp_unique (bl_from, bl_to)";
	wfQuery( $sql, DB_WRITE );
	$sql = "ALTER TABLE imagelinks ADD UNIQUE tmp_unique (il_from, il_to(244))";
	wfQuery( $sql, DB_WRITE );
	print "Temporary unique index added ok. Forget what I said.\n\n";

	print "Locking tables\n";
	$sql = "LOCK TABLES cur READ, interwiki READ, user_newtalk READ, " .
		"links WRITE, brokenlinks WRITE, imagelinks WRITE";
	wfQuery( $sql, DB_WRITE );

	print "Finding number of articles to process\n";
	$sql = "SELECT COUNT(*) as count FROM cur";
	$res = wfQuery( $sql, DB_READ );
	$obj = wfFetchObject( $res );
	$total = $obj->count;

	print "Finding highest article id\n";
	$sql = "SELECT MIN(cur_id) AS min, MAX(cur_id) AS max FROM cur";
	$res = wfQuery( $sql, DB_READ );
	$obj = wfFetchObject( $res );

	$cur_pulser = new SelectPulser("SELECT cur_id,cur_namespace,cur_title,cur_text " .
		"FROM cur WHERE cur_id ", 
		$obj->min, $obj->max, $rowbuf_size);

	$brokenlinks_inserter = new InsertBuffer(
		"INSERT IGNORE INTO brokenlinks (bl_from,bl_to) VALUES " , $rowbuf_size);

	$links_inserter = new InsertBuffer(
		"INSERT IGNORE INTO links (l_from,l_to) VALUES ", $rowbuf_size);

	$imagelinks_inserter = new InsertBuffer("INSERT IGNORE INTO imagelinks ".
		"(il_from,il_to) VALUES ", $rowbuf_size);

	print "Starting processing\n";

	$ins = $wgLang->getNsText( Namespace::getImage() );
	$inslen = strlen($ins)+1;

	$tc = Title::legalChars();
	
	$start_time = time();
	while ( $row = $cur_pulser->next() ) {
		$from_id = $row->cur_id;
		$ns = $wgLang->getNsText( $row->cur_namespace );

		$raw_title = $row->cur_title;
		if ( "" != $ns ) {
			$raw_title = "$ns:{$raw_title}";
		}
		$title = addslashes( $raw_title );
		$text = $row->cur_text;

		$numlinks = preg_match_all( "/\\[\\[([{$tc}]+)(]|\\|)/", $text,
		  $m, PREG_PATTERN_ORDER );

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

			$dest = addslashes( $nt->getPrefixedDBkey() );
			$dest_id = $nt->getArticleID();

			if ( 0 == strncmp( "$ins:", $raw_title, $inslen ) ) { 
				$iname = addslashes( substr( $raw_title, $inslen ) );
				$imagelinks_inserter->insert( "('{$title}','{$iname}')" );
			} else if ( 0 == $dest_id ) {
				$brokenlinks_inserter->insert( "({$from_id},'{$dest}')" );
			} else {
				$links_inserter->insert( "('{$title}',{$dest_id})" );
			}
		}

		if ( ( $count % 10 ) == 0 )
			print "."; 

		if ( ( ++$count % 1000 ) == 0 ) {
			$dt = time() - $start_time;
			$start_time = time();
			$rps = ($dt == 0 ? "lots of" : intval(1000/$dt));
			print "\n$count of $total articles scanned ({$rps} articles per second)\n";
		}

	}

	$imagelinks_inserter->flush();
	$links_inserter->flush();
	$brokenlinks_inserter->flush();

	print "$total articles scanned.\n";

	print "Removing temporary unique indexes from tables links, brokenlinks and imagelinks.\n";
	$sql = "ALTER TABLE links DROP INDEX tmp_unique";
	wfQuery( $sql, DB_WRITE );
	$sql = "ALTER TABLE brokenlinks DROP INDEX tmp_unique";
	wfQuery( $sql, DB_WRITE );
	$sql = "ALTER TABLE imagelinks DROP INDEX tmp_unique";
	wfQuery( $sql, DB_WRITE );

	$sql = "UNLOCK TABLES";
	wfQuery( $sql, DB_WRITE );
	print "Done\n";
}

# InsertBuffer increases performance slightly by inserting many rows
# at once. The gain is small (<5%) when running against a local, idle
# database, but may be significant in other circumstances. It also
# limits the number of inserted rows uppwards, which should avoid
# problems with huge articles and certain mysql settings that limits
# the size of queries. It's also convenient.

class InsertBuffer {
	/* private */ var $mBuf, $mSql, $mBufcount, $mMaxsize;
	
	function InsertBuffer( $sql, $bufsize ){
		$this->mSql = $sql;
		$this->mBuf = array();
		$this->mBufcount = 0;
		$this->mMaxsize = $bufsize;
	}

	function insert( $value ){
		// print $this->mSql . " -> " . $value . "\n";
		$this->mBuf[] = $value;
		$this->mBufcount++;
		if($this->mBufcount > $this->mMaxsize){
			$this->flush();
		}
	}

	function flush(){
		if( $this->mBufcount > 0 ){
			$sql = $this->mSql . implode(",", $this->mBuf);
			wfQuery( $sql, DB_WRITE );
			$this->mBuf = array();
			$this->mBufcount = 0;
			// print "Wrote query of size " . strlen( $sql ) . "\n";
		}
	}
	
}

# Select parts from a large table by using the "BETWEEN X AND Y"
# operator on the id column. Avoids buffering the whole thing in
# RAM. It's also convenient.  
class SelectPulser {
	/* private */ var $mSql, $mSetsize, $mPos, $mMax, $mSet;

	function SelectPulser( $sql, $min, $max, $setsize) {
		$this->mSql = $sql;
		$this->mSet = array();
		$this->mPos = $min;
		$this->mMax = $max;
		$this->mSetsize = $setsize;
	}
	
	function next(){
		$result = current( $this->mSet );
		next( $this->mSet );
		if( false !== $result ){
			return $result;
		}
		while( $this->mPos <= $this->mMax ){
			$this->mSet = array();
			$sql = $this->mSql . " BETWEEN " . $this->mPos .
				" AND " . ($this->mPos + $this->mSetsize - 1);
			$this->mPos += $this->mSetsize;

			$res = wfQuery( $sql, DB_READ );
			while ( $row = wfFetchObject( $res ) ) {
				$this->mSet[] = $row;
			}

			if( count( $this->mSet ) > 0 ){
				return $this->next();
			} 
		}
		return false;
	}
}

?>
