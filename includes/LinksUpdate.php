<?
# See deferred.doc

class LinksUpdate {

	/* private */ var $mId, $mTitle;

	function LinksUpdate( $id, $title )
	{
		$this->mId = $id;
		$this->mTitle = $title;
		$this->mTitleEnc = wfStrencode( $title );
	}

	function doUpdate()
	{
		/* Update link tables with outgoing links from an updated article */
		/* Currently this is 'dumb', removing all links and putting them back. */
		
		/* Relies on the 'link cache' to be filled out */
		global $wgLinkCache, $wgDBtransactions;
		$fname = "LinksUpdate::doUpdate";
		wfProfileIn( $fname );

		if( $wgDBtransactions ) {
			$sql = "BEGIN";
			wfQuery( $sql, $fname );
		}
		
		$sql = "DELETE FROM links WHERE l_from='{$this->mTitleEnc}'";
		wfQuery( $sql, $fname );

		$a = $wgLinkCache->getGoodLinks();
		$sql = "";
		if ( 0 != count( $a ) ) {
			$sql = "INSERT INTO links (l_from,l_to) VALUES ";
			$first = true;
			foreach( $a as $lt => $lid ) {
				if ( ! $first ) { $sql .= ","; }
				$first = false;

				$sql .= "('{$this->mTitleEnc}',{$lid})";
			}
		}
		if ( "" != $sql ) { wfQuery( $sql, $fname ); }

		$sql = "DELETE FROM brokenlinks WHERE bl_from={$this->mId}";
		wfQuery( $sql, $fname );

		$a = $wgLinkCache->getBadLinks();
		$sql = "";
		if ( 0 != count ( $a ) ) {
			$sql = "INSERT INTO brokenlinks (bl_from,bl_to) VALUES ";
			$first = true;
			foreach( $a as $blt ) {
				$blt = wfStrencode( $blt );
				if ( ! $first ) { $sql .= ","; }
				$first = false;

				$sql .= "({$this->mId},'{$blt}')";
			}
		}
		if ( "" != $sql ) { wfQuery( $sql, $fname ); }

		$sql = "DELETE FROM imagelinks WHERE il_from='{$t}'";
		wfQuery( $sql, $fname );

		$a = $wgLinkCache->getImageLinks();
		$sql = "";
		if ( 0 != count ( $a ) ) {
			$sql = "INSERT INTO imagelinks (il_from,il_to) VALUES ";
			$first = true;
			foreach( $a as $iname => $val ) {
				$iname = wfStrencode( $iname );
				if ( ! $first ) { $sql .= ","; }
				$first = false;

				$sql .= "('{$t}','{$iname}')";
			}
		}
		if ( "" != $sql ) { wfQuery( $sql, $fname ); }

		$this->fixBrokenLinks();

		if( $wgDBtransactions ) {
			$sql = "COMMIT";
			wfQuery( $sql, $fname );
		}
		wfProfileOut();
	}
	
	function fixBrokenLinks() {
		/* Update any brokenlinks *to* this page */
		/* Call for a newly created page, or just to make sure state is consistent */
		
		$sql = "SELECT bl_from FROM brokenlinks WHERE bl_to='{$this->mTitleEnc}'";
		$res = wfQuery( $sql, $fname );
		if ( 0 == wfNumRows( $res ) ) { return; }

		$sql = "INSERT INTO links (l_from,l_to) VALUES ";
		$now = wfTimestampNow();
		$sql2 = "UPDATE cur SET cur_touched='{$now}' WHERE cur_id IN (";
		$first = true;
		while ( $row = wfFetchObject( $res ) ) {
			if ( ! $first ) { $sql .= ","; $sql2 .= ","; }
			$first = false;
			$nl = wfStrencode( Article::nameOf( $row->bl_from ) );

			$sql .= "('{$nl}',{$this->mId})";
			$sql2 .= $row->bl_from;
		}
		$sql2 .= ")";
		wfQuery( $sql, $fname );
		wfQuery( $sql2, $fname );

		$sql = "DELETE FROM brokenlinks WHERE bl_to='{$this->mTitleEnc}'";
		wfQuery( $sql, $fname );
	}
	
}

?>
