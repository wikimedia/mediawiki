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
		global $wgUseBetterLinksUpdate, $wgLinkCache, $wgDBtransactions;
		
		/* Update link tables with outgoing links from an updated article */
		/* Relies on the 'link cache' to be filled out */

		if ( !$wgUseBetterLinksUpdate ) {
			$this->doDumbUpdate();
			return;
		}

		$fname = "LinksUpdate::doUpdate";
		wfProfileIn( $fname );

		$del = array();
		$add = array();

		if( $wgDBtransactions ) {
			$sql = "BEGIN";
			wfQuery( $sql, $fname );
		}
		
		#------------------------------------------------------------------------------
		# Good links

		if ( $wgLinkCache->incrementalSetup( LINKCACHE_GOOD, $del, $add ) ) {
			# Delete where necessary
			$baseSql = "DELETE FROM links WHERE l_from='{$this->mTitleEnc}'";
			foreach ($del as $title => $id ) {
				wfDebug( "Incremental deletion  from {$this->mTitleEnc} to $title\n" );
				$sql = $baseSql . " AND l_to={$id}";
				wfQuery( $sql, $fname );
			}
		} else {
			# Delete everything
			wfDebug( "Complete deletion from {$this->mTitleEnc}\n" );
			$sql = "DELETE FROM links WHERE l_from='{$this->mTitleEnc}'";
			wfQuery( $sql, $fname );
			
			# Get the addition list
			$add = $wgLinkCache->getGoodLinks();
		}

		# Do the insertion
		$sql = "";
		if ( 0 != count( $add ) ) {
			$sql = "INSERT INTO links (l_from,l_to) VALUES ";
			$first = true;
			foreach( $add as $lt => $lid ) {
				wfDebug( "Inserting from {$this->mTitleEnc} to $lt\n" );
				
				if ( ! $first ) { $sql .= ","; }
				$first = false;

				$sql .= "('{$this->mTitleEnc}',{$lid})";
			}
		}
		if ( "" != $sql ) { wfQuery( $sql, $fname ); }

		#------------------------------------------------------------------------------
		# Bad links

		if ( $wgLinkCache->incrementalSetup( LINKCACHE_BAD, $del, $add ) ) {
			# Delete where necessary
			$baseSql = "DELETE FROM brokenlinks WHERE bl_from={$this->mId}";
			foreach ( $del as $title ) {
				$sql = $baseSql . " AND bl_to={$title}";
				wfQuery( $sql, $fname );
			}
		} else {
			# Delete all
			$sql = "DELETE FROM brokenlinks WHERE bl_from={$this->mId}";
			wfQuery( $sql, $fname );
			
			# Get addition list
			$add = $wgLinkCache->getBadLinks();
		}

		# Do additions
		$sql = "";
		if ( 0 != count ( $add ) ) {
			$sql = "INSERT INTO brokenlinks (bl_from,bl_to) VALUES ";
			$first = true;
			foreach( $add as $blt ) {
				$blt = wfStrencode( $blt );
				if ( ! $first ) { $sql .= ","; }
				$first = false;

				$sql .= "({$this->mId},'{$blt}')";
			}
		}
		if ( "" != $sql ) { wfQuery( $sql, $fname ); }

		#------------------------------------------------------------------------------
		# Image links
		if ( $wgLinkCache->incrementalSetup( LINKCACHE_IMAGE, $del, $add ) ) {
			# Delete where necessary
			$sql = "DELETE FROM imagelinks WHERE il_from='{$this->mTitleEnc}'";
			foreach ($del as $title ) {
				$sql = $baseSql . " AND il_to={$title}";
				wfQuery( $sql, $fname );
			}
		} else {
			# Delete all
			$sql = "DELETE FROM imagelinks WHERE il_from='{$this->mTitleEnc}'";
			wfQuery( $sql, $fname );
			
			# Get addition list
			$add = $wgLinkCache->getImageLinks();
		}
		
		# Do the insertion
		$sql = "";
		if ( 0 != count ( $add ) ) {
			$sql = "INSERT INTO imagelinks (il_from,il_to) VALUES ";
			$first = true;
			foreach( $add as $iname => $val ) {
				$iname = wfStrencode( $iname );
				if ( ! $first ) { $sql .= ","; }
				$first = false;

				$sql .= "('{$this->mTitleEnc}','{$iname}')";
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

	function doDumbUpdate()
	{
		# Old update function. This can probably be removed eventually, if the new one
		# proves to be stable
		global $wgLinkCache, $wgDBtransactions;
		$fname = "LinksUpdate::doDumbUpdate";
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
		
		$sql = "DELETE FROM imagelinks WHERE il_from='{$this->mTitleEnc}'";
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

				$sql .= "('{$this->mTitleEnc}','{$iname}')";
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
