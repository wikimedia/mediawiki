<?php
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
		$fname = 'LogPage::getContent';

		$dbw =& wfGetDB( DB_MASTER );
		$s = $dbw->getArray( 'cur', 
			array( 'cur_id','cur_text','cur_timestamp' ),
			array( 'cur_namespace' => Namespace::getWikipedia(), 'cur_title' => $this->mTitle ), 
			$fname, 'FOR UPDATE' 
		);

		if( $s !== false ) {
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

		$dbw =& wfGetDB( DB_MASTER );
		$uid = $wgUser->getID();

		if( !$this->mContentLoaded ) return false;
		$this->mTimestamp = $now = wfTimestampNow();
		$won = wfInvertTimestamp( $now );
		if($this->mId == 0) {
			$seqVal = $dbw->nextSequenceValue( 'cur_cur_id_seq' );

			# Note: this query will deadlock if another thread has called getContent(), 
			# at least in MySQL 4.0.17 InnoDB
			$dbw->insertArray( 'cur',
				array(
					'cur_id' => $seqVal,
					'cur_timestamp' => $now,
					'cur_user' => $uid,
					'cur_user_text' => $wgUser->getName(),
					'cur_namespace' => NS_WIKIPEDIA,
					'cur_title' => $this->mTitle, 
					'cur_text' => $this->mContent,
					'cur_comment' => $this->mComment,
					'cur_restrictions' => 'sysop',
					'inverse_timestamp' => $won,
					'cur_touched' => $now,
				), $fname
			);
			$this->mId = $dbw->insertId();
		} else {
			$dbw->updateArray( 'cur',
				array( /* SET */ 
					'cur_timestamp' => $now,
					'cur_user' => $uid, 
					'cur_user_text' => $wgUser->getName(),
					'cur_text' => $this->mContent,
					'cur_comment' => $this->mComment,
					'cur_restrictions' => 'sysop', 
					'inverse_timestamp' => $won,
					'cur_touched' => $now,
				), array( /* WHERE */
					'cur_id' => $this->mId
				), $fname
			);
		}
		
		# And update recentchanges
		if ( $this->mUpdateRecentChanges ) {
			$titleObj = Title::makeTitle( Namespace::getWikipedia(), $this->mTitle );
			RecentChange::notifyLog( $now, $titleObj, $wgUser, $this->mComment );
		}
		return true;
	}

	function addEntry( $action, $comment, $textaction = "" )
	{
		global $wgLang, $wgUser;

		$comment_esc = wfEscapeWikiText( $comment );

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
		
		# Use the wiki-wide default date format instead of the user's setting
		$d = $wgLang->timeanddate( wfTimestampNow(), false, MW_DATE_DEFAULT );

		if( preg_match( "/^(.*?)<ul>(.*)$/sD", $this->mContent, $m ) ) {
			$before = $m[1];
			$after = $m[2];
		} else {
			$before = "";
			$after = "";
		}
		
		if($textaction)
			$this->mComment = $textaction;
		else
			$this->mComment = $action;
		
		if ( "" == $comment ) {
			$inline = "";
		} else {
			$inline = " <em>({$comment_esc})</em>";
			# comment gets escaped again, so we use the unescaped version
			$this->mComment .= ": {$comment}";
		}
		$this->mContent = "{$before}<ul><li>{$d} {$ul} {$action}{$inline}</li>\n{$after}";
		
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
		if( $wgOut->checkLastModified( $this->getTimestamp() ) ){
			# Client cache fresh and headers sent, nothing more to do.
			return;
		}
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
