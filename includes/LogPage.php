<?
# Class to simplify the use of log pages

class LogPage {
	/* private */ var $mTitle, $mContent, $mContentLoaded, $mId, $mComment;
	var $mUpdateRecentChanges ;

	function LogPage( $title, $defaulttext = "<ul>\n</ul>"  )
	{
		# For now, assume title is correct dbkey
		# and log pages always go in Wikipedia namespace
		$this->mTitle = str_replace( " ", "_", $title );
		$this->mId = 0;
		$this->mUpdateRecentChanges = true ;
		$this->mContentLoaded = false;
		$this->getContent( $defaulttext );
	}

	function getContent( $defaulttext = "<ul>\n</ul>" )
	{
		$sql = "SELECT cur_id,cur_text,cur_timestamp FROM cur " .
			"WHERE cur_namespace=" . Namespace::getWikipedia() . " AND " .
			"cur_title='" . wfStrencode($this->mTitle ) . "'";
		$res = wfQuery( $sql, "LogPage::getContent" );

		if( wfNumRows( $res ) > 0 ) {
			$s = wfFetchObject( $res );
			$this->mId = $s->cur_id;
			$this->mContent = $s->cur_text;
			$this->mTimestamp = $s->cur_timestamp;
		} else {
			$this->mId = 0;
			$this->mContent = $defaulttext;
			$this->mTimestamp = wfTimestampNow();
		}
		$this->mContentLoaded = true; # Well, sort of
		
		return $this->mContent;
	}
	
	function getTimestamp()
	{
		if( !$this->mContentLoaded ) {
			$this->getContent();
		}
		return $this->mTimestamp;
	}

	function saveContent()
	{
		if( wfReadOnly() ) return;

		global $wgUser;
		$fname = "LogPage::saveContent";
		$uid = $wgUser->getID();
		$ut = wfStrencode( $wgUser->getName() );

		if( !$this->mContentLoaded ) return false;
		$this->mTimestamp = $now = wfTimestampNow();
		$won = wfInvertTimestamp( $now );
		if($this->mId == 0) {
			$sql = "INSERT INTO cur (cur_timestamp,cur_user,cur_user_text,
				cur_namespace,cur_title,cur_text,cur_comment,cur_restrictions,inverse_timestamp)
				VALUES ('{$now}', {$uid}, '{$ut}', " .
				Namespace::getWikipedia() . ", '" .
				wfStrencode( $this->mTitle ) . "', '" .
				wfStrencode( $this->mContent ) . "', '" .
				wfStrencode( $this->mComment ) . "', 'sysop', '{$won}')";
			wfQuery( $sql, $fname );
			$this->mId = wfInsertId();
		} else {
			$sql = "UPDATE cur SET cur_timestamp='{$now}', " .
			  "cur_user={$uid}, cur_user_text='{$ut}', " .
			  "cur_text='" . wfStrencode( $this->mContent ) . "', " .
			  "cur_comment='" . wfStrencode( $this->mComment ) . "', " .
			  "cur_restrictions='sysop', inverse_timestamp='{$won}' " .
			  "WHERE cur_id={$this->mId}";
			wfQuery( $sql, $fname );
		}
		
		# And update recentchanges
		if ( $this->mUpdateRecentChanges ) {
			$sql = "INSERT INTO recentchanges (rc_timestamp,rc_cur_time,
        	        rc_user,rc_user_text,rc_namespace,rc_title,rc_comment,
                	rc_cur_id) VALUES ('{$now}','{$now}',{$uid},'{$ut}',4,'" .
				wfStrencode( $this->mTitle ) . "','" .
				wfStrencode( $this->mComment ) . "',{$this->mId})";
		        wfQuery( $sql, $fname );
			}
		return true;
	}

	function addEntry( $action, $comment, $textaction = "" )
	{
		global $wgLang, $wgUser;
		$this->getContent();

		$ut = $wgUser->getName();
		$uid = $wgUser->getID();
		if( $uid ) {
			$ul = "[[" .
			  $wgLang->getNsText( Namespace::getUser() ) .
			  ":{$ut}|{$ut}]]";
		} else {
			$ul = $ut;
		}
		$d = $wgLang->timeanddate( wfTimestampNow(), false );

		preg_match( "/^(.*?)<ul>(.*)$/sD", $this->mContent, $m );

		if($textaction)
			$this->mComment = $textaction;
		else
			$this->mComment = $action;
		
		if ( "" == $comment ) {
			$inline = "";
		} else {
			$inline = " <em>({$comment})</em>";
			$this->mComment .= ": {$comment}";
		}
		$this->mContent = "{$m[1]}<ul><li>{$d} {$ul} {$action}{$inline}</li>\n{$m[2]}";
		
		# TODO: automatic log rotation...
		
		return $this->saveContent();
	}
	
	function replaceContent( $text, $comment = "" )
	{
		$this->mContent = $text;
		$this->mComment = $comment;
		$this->mTimestamp = wfTimestampNow();
		return $this->saveContent();
	}

	function showAsDisabledPage( $rawhtml = true )
	{
		global $wgLang, $wgOut;
		$wgOut->checkLastModified( $this->getTimestamp() );
		$func = ( $rawhtml ? "addHTML" : "addWikiText" );
		$wgOut->$func(
			"<p>" . wfMsg( "perfdisabled" ) . "</p>\n\n" .
			"<p>" . wfMsg( "perfdisabledsub", $wgLang->timeanddate( $this->getTimestamp() ) ) . "</p>\n\n" .
			"<hr />\n\n" .
			$this->getContent()
			);
		return;
		
	}
}

?>
