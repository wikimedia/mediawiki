<?php
/**
 * Functions for rebuilding the link tracking tables; must
 * be included within a script that also includes the Setup.
 * See @see rebuildlinks.php, for example.
 *
 * @deprecated
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
die( "rebuildLinks.inc needs to be updated for the new schema\n" );

#

# Buffer this many rows before inserting them all in one sweep. More
# than about 1000 will probably not increase speed significantly on
# most setups.
/* private */ $rowbuf_size = 1000; // 1000 rows ~40 kB

function rebuildLinkTables()
{
	error_reporting (E_ALL);
	global $wgLang, $wgLinkCache, $rowbuf_size;

	print "This script may take several hours to complete. If you abort during that time,\n";
	print "your wiki will be in an inconsistent state. If you are going to abort, this is\n";
	print "the time to do it.\n\n";
	print "Press control-c to abort (will proceed automatically in 15 seconds)\n";
	sleep(15);

	$count = 0;
	print "Rebuilding link tables.\n";

	print "Setting AUTOCOMMIT=1\n";
	wfQuery("SET SESSION AUTOCOMMIT=1", DB_MASTER);

	print "Extracting often used data from cur (may take a few minutes)\n";
	$sql = "CREATE TEMPORARY TABLE cur_fast SELECT cur_namespace, cur_title, cur_id FROM cur";
	wfQuery( $sql, DB_MASTER );
	$sql = "ALTER TABLE cur_fast ADD INDEX(cur_namespace, cur_title)";
	wfQuery( $sql, DB_MASTER );

	print "Locking tables\n";
	$sql = "LOCK TABLES cur READ, cur_fast READ, interwiki READ, user_newtalk READ, " .
		"links WRITE, brokenlinks WRITE, imagelinks WRITE";
	wfQuery( $sql, DB_MASTER );


	print "Deleting old data in links table.\n";
	$sql = "DELETE FROM links";
	wfQuery( $sql, DB_MASTER );

	print "Deleting old data in brokenlinks table.\n";
	$sql = "DELETE FROM brokenlinks";
	wfQuery( $sql, DB_MASTER );

	print "Deleting old data in imagelinks table.\n";
	$sql = "DELETE FROM imagelinks";
	wfQuery( $sql, DB_MASTER );

	print "Finding number of articles to process... ";
	$sql = "SELECT COUNT(*) as count FROM cur";
	$res = wfQuery( $sql, DB_SLAVE );
	$obj = wfFetchObject( $res );
	$total = $obj->count;
	print "$total\n";

	print "Finding highest article id\n";
	$sql = "SELECT MIN(cur_id) AS min, MAX(cur_id) AS max FROM cur";
	$res = wfQuery( $sql, DB_SLAVE );
	$obj = wfFetchObject( $res );
 
	$cur_pulser = new SelectPulser("SELECT cur_id,cur_namespace,cur_title,cur_text " .
		"FROM cur WHERE cur_id ", 
		$obj->min, $obj->max, 100);

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
	
	$titleCache = new MRUCache( 10000 );
	$titlecount = 0;
	$start_time = time();

	while ( $row = $cur_pulser->next() ) {

		$from_id = intval($row->cur_id);
		$ns = $wgLang->getNsText( $row->cur_namespace );	
		$from_full_title = $row->cur_title;		
		if ( "" != $ns ) {
			$from_full_title = "$ns:{$from_full_title}";
		}
		$from_full_title_with_slashes = addslashes( $from_full_title );
		$text = $row->cur_text;

		$numlinks = preg_match_all( "/\\[\\[([{$tc}]+)(]|\\|)/", $text,
		  $m, PREG_PATTERN_ORDER );

		$seen_dbtitles = array(); // seen links (normalized and with ns, see below) 
		$titles_ready_for_insertion = array();
		$titles_needing_curdata = array();
		$titles_needing_curdata_pos = array();
		$links_corresponding_to_titles = array();

		for ( $i = 0 ; $i < $numlinks; ++$i ) {
			$link = $m[1][$i];
			if( preg_match( '/^(http|https|ftp|mailto|news):/', $m[1][$i] ) ) {
				# an URL link; not for us!
				continue;
			}
			
			# FIXME: Handle subpage links
			$nt = $titleCache->get( $link );
			if( $nt != false ){
				// Only process each unique link once per page
				$nt_key = $nt->getDBkey() . $nt->getNamespace();
				if( isset( $seen_dbtitles[$nt_key] ) )
					continue;
				$seen_dbtitles[$nt_key] = 1;

				$titles_ready_for_insertion[] = $nt;
			} else {
				$nt = Title::newFromText( $link );
				if (! $nt) {
					// Invalid link, probably something like "[[  ]]"
					continue;
				}
				
				// Only process each unique link once per page
				$nt_key = $nt->getDBkey() . $nt->getNamespace();
				if( isset( $seen_dbtitles[$nt_key] ) )
					continue;
				$seen_dbtitles[$nt_key] = 1;

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
				$nt->mArticleID = 0; // assume broken link until proven otherwise

				$pos = array_push($titles_needing_curdata, $nt) - 1;
				$titles_needing_curdata_pos[$nt->getDBkey() . $nt->getNamespace()] = $pos;
				$links_corresponding_to_titles[] = $link;
				unset( $link ); // useless outside this loop, but tempting 
			}
		}


		if ( count( $titles_needing_curdata ) > 0 ){
			$parts = array();
			foreach ($titles_needing_curdata as $nt ) {
				$parts[] = " (cur_namespace = " . $nt->getNamespace() . " AND " .
					"cur_title='" . wfStrencode( $nt->getDBkey() ) . "')";
			}
			$sql = "SELECT cur_namespace, cur_title, cur_id FROM cur_fast WHERE " . 
				implode(" OR ", $parts);
			$res = wfQuery( $sql, DB_MASTER );
			while($row = wfFetchObject( $res ) ){
				$pos = $titles_needing_curdata_pos[$row->cur_title . $row->cur_namespace];
				$titles_needing_curdata[$pos]->mArticleID = intval($row->cur_id);
			}
			for( $k = 0; $k < count( $titles_needing_curdata ) ; $k++) {
				$tmplink = $links_corresponding_to_titles[$k];
				$titleCache->set( $tmplink, $titles_needing_curdata[$k] );
				$titles_ready_for_insertion[] = $titles_needing_curdata[$k];
			}
		}

		foreach ( $titles_ready_for_insertion as $nt ) {
			$dest_noslashes = $nt->getPrefixedDBkey();
			$dest = addslashes( $dest_noslashes ); 
			$dest_id = $nt->getArticleID();
			$from = $from_full_title_with_slashes;

			# print "\nLINK '$from_full_title' ($from_id) -> '$dest' ($dest_id)\n";

			if ( 0 == strncmp( "$ins:", $dest_noslashes, $inslen ) ) { 
				$iname = addslashes( substr( $dest_noslashes, $inslen ) );
				$imagelinks_inserter->insert( "('{$from}','{$iname}')" );
			} else if ( 0 == $dest_id ) {
				$brokenlinks_inserter->insert( "({$from_id},'{$dest}')" );
			} else {
				$links_inserter->insert( "('{$from}',{$dest_id})" );
			}
			$titlecount++;
		}

		if ( ( $count % 20 ) == 0 )
			print "."; 

		if ( ( ++$count % 1000 ) == 0 ) {
			$dt = time() - $start_time;
			$start_time = time();
			$rps = persec(1000, $dt);
			$tps = persec($titlecount, $dt);
			$titlecount = 0;
			print "\n$count of $total articles scanned ({$rps} articles ".
				"and {$tps} titles per second)\n";
			print "Title cache hits: " . $titleCache->getPerformance() . "%\n";

		}

	}

	print "\nFlushing insertion buffers...";
	$imagelinks_inserter->flush();
	$links_inserter->flush();
	$brokenlinks_inserter->flush();
	print "ok\n";

	print "$count articles scanned.\n";

	$sql = "UNLOCK TABLES";
	wfQuery( $sql, DB_MASTER );
	print "Done\n";
}

/* private */ function persec($n, $t){
	if($n == 0)
		return "zero";
	if($t == 0)
		return "lots of";
	return intval($n/$t);
}

/**
 * InsertBuffer increases performance slightly by inserting many rows
 * at once. The gain is small (<5%) when running against a local, idle
 * database, but may be significant in other circumstances. It also
 * limits the number of inserted rows uppwards, which should avoid
 * problems with huge articles and certain mysql settings that limits
 * the size of queries. It's also convenient.
 *
 * @deprecated
 * @package MediaWiki
 * @subpackage Maintenance
 */
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
			wfQuery( $sql, DB_MASTER );
			$this->mBuf = array();
			$this->mBufcount = 0;
			// print "Wrote query of size " . strlen( $sql ) . "\n";
		}
	}
	
}

/**
 * Select parts from a large table by using the "BETWEEN X AND Y"
 * operator on the id column. Avoids buffering the whole thing in
 * RAM. It's also convenient.
 *
 * @deprecated
 * @package MediaWiki
 * @subpackage Maintenance
 */
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

			$res = wfQuery( $sql, DB_SLAVE );
			while ( $row = wfFetchObject( $res ) ) {
				$this->mSet[] = $row;
			}
			wfFreeResult( $res );
			if( count( $this->mSet ) > 0 ){
				return $this->next();
			} 
		}
		return false;
	}
}

/**
 * A simple MRU for general cacheing.
 * @deprecated
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */
class MRUCache {
	/* private */ var $mMru, $mCache, $mSize, $mPurgefreq, $nexti;
	/* private */ var $hits, $misses;

	function MRUCache( $size, $purgefreq = -1 ) {
		// purgefreq is 1/10 of $size if not stated		
		$purgefreq = ($purgefreq == -1 ? intval($size/10) : $purgefreq);
		$purgefreq = ($purgefreq <= 0 ? 1 : $purgefreq);

		$this->mSize = $size;
		$this->mMru = array();
		$this->mCache = array();
		$this->mPurgefreq = $purgefreq;
		$this->nexti = 1;
		print "purgefreq = " . $this->mPurgefreq . "\n";
	}

	function get( $key ){
		if ( ! array_key_exists( $key, $this->mCache) ){
			$this->misses++;
			return false;
		}
		$this->hits++;
		$this->mMru[$key] = $this->nexti++;
		return $this->mCache[$key];
	}
	
	function set( $key, $value ){	
		$this->mMru[$key] = $this->nexti++;
		$this->mCache[$key] = $value;

		if($this->nexti % $this->mPurgefreq == 0)
			$this->purge();
	}

	function purge(){
		$to_remove = count( $this->mMru ) - $this->mSize;
		if( $to_remove <= 0 ){
			return;
		}		
		asort( $this->mMru );
		$removed = array_splice( $this->mMru, 0, $to_remove );
		foreach( array_keys( $removed ) as $key ){
			unset( $this->mCache[$key] );
		}
	}
	
	function getPerformance(){
		$tot = $this->hits + $this->misses;
		if($tot > 0)
			return intval(100.0 * $this->hits / $tot);
		else
			return 0;
	}
}	

?>
