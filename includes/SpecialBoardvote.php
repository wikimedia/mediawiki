<?php

function wfSpecialBoardvote( $par = "" ) {
	global $wgRequest, $wgBoardVoteDB;

	$form = new BoardVoteForm( $wgRequest, $wgBoardVoteDB );
	if ( $par ) {
		$form->mAction = $par;
	}
	
	$form->execute();
}

class BoardVoteForm {
	var $mPosted, $mContributing, $mVolunteer, $mDBname, $mUserDays, $mUserEdits;
	var $mHasVoted, $mAction, $mUserKey;

	function BoardVoteForm( &$request, $dbName ) {
		global $wgUser, $wgDBname, $wgInputEncoding;

		$this->mUserKey = iconv( $wgInputEncoding, "UTF-8", $wgUser->getName() ) . "@$wgDBname";
		$this->mPosted = $request->wasPosted();
		$this->mContributing = $request->getInt( "contributing" );
		$this->mVolunteer = $request->getInt( "volunteer" );
		$this->mDBname = $dbName;
		$this->mHasVoted = $this->hasVoted( $wgUser );
		$this->mAction = $request->getText( "action" );
	}

	function execute() {
		global $wgUser;
		if ( $this->mAction == "list" ) {
			$this->displayList();
		} elseif ( $this->mAction == "dump" ) {
			$this->dump();
		} elseif( $this->mAction == "vote" ) {
			if ( !$wgUser->getID() ) {
				$this->notLoggedIn();
			} else {
				$this->getQualifications( $wgUser );
				if ( $this->mUserDays < 90 ) {
					$this->notQualified();
				} elseif ( $this->mPosted ) {
					$this->logVote();
				} else {
					$this->displayVote();
				}
			}
		} else {
			$this->displayEntry();
		}
	}
	
	function displayEntry() {
		global $wgOut;
		$wgOut->addWikiText( wfMsg( "boardvote_entry" ) );
	}

	function hasVoted( &$user ) {
		global $wgDBname;
		$row = wfGetArray( $this->mDBname . ".vote", array( "1" ), 
		  array( "vote_key" => $this->mUserKey ), "BoardVoteForm::getUserVote" );
		if ( $row === false ) {
			return false;
		} else {
			return true;
		}
	}

	function logVote() {
		global $wgUser, $wgDBname, $wgIP, $wgOut;
		$now = wfTimestampNow();
		$record = $this->encrypt( $this->mContributing, $this->mVolunteer );
		$db = $this->mDBname;

		# Add vote to log
		wfInsertArray( "$db.log", array(
			"log_user" => $wgUser->getID(),
			"log_user_text" => $wgUser->getName(),
			"log_user_key" => $this->mUserKey,
			"log_wiki" => $wgDBname,
			"log_edits" => $this->mUserEdits,
			"log_days" => $this->mUserDays,
			"log_record" => $record,
			"log_ip" => $wgIP,
			"log_xff" => @$_SERVER['HTTP_X_FORWARDED_FOR'],
			"log_ua" => $_SERVER['HTTP_USER_AGENT'],
			"log_timestamp" => $now
		));

		# Record vote in non-duplicating vote table
		$sql = "REPLACE INTO $db.vote (vote_key,vote_record) " .
		  "VALUES ('". wfStrencode( $this->mUserKey ) . "','" . wfStrencode( $record ) . "')";
		wfQuery( $sql, DB_WRITE );

		$wgOut->addWikiText( wfMsg( "boardvote_entered", $record ) );
	}
	
	function displayVote() {
		global $wgContributingCandidates, $wgVolunteerCandidates, $wgOut;
		
		$thisTitle = Title::makeTitle( NS_SPECIAL, "Boardvote" );
		$action = $thisTitle->getLocalURL( "action=vote" );
		if ( $this->mHasVoted ) {
			$intro = wfMsg( "boardvote_intro_change" );
		} else {
			$intro = wfMsg( "boardvote_intro" );
		}
		$contributing = wfMsg( "boardvote_contributing" );
		$volunteer = wfMsg( "boardvote_volunteer" );
		$ok = wfMsg( "ok" );
		
		$candidatesV = $candidatesC = array();
		foreach( $wgContributingCandidates as $i => $candidate ) {
			$candidatesC[] = array( $i, $candidate );
		}
		foreach ( $wgVolunteerCandidates as $i => $candidate ) {
			$candidatesV[] = array( $i, $candidate );
		}

		srand ((float)microtime()*1000000);
		shuffle( $candidatesC );
		shuffle( $candidatesV );

		$text = "
		  $intro
		  <form name=\"boardvote\" id=\"boardvote\" method=\"post\" action=\"$action\">
		  <table border='0'><tr><td colspan=2>
		  <h2>$contributing</h2>
		  </td></tr>";
		$text .= $this->voteEntry( -1, wfMsg( "boardvote_abstain" ), "contributing" );
		foreach ( $candidatesC as $candidate ) {
			$text .= $this->voteEntry( $candidate[0], $candidate[1], "contributing" );
		}
		$text .= "
		  <tr><td colspan=2>
		  <h2>$volunteer</h2></td></tr>";
		$text .= $this->voteEntry( -1, wfMsg( "boardvote_abstain" ), "volunteer" );
		foreach ( $candidatesV as $candidate ) {
			$text .= $this->voteEntry( $candidate[0], $candidate[1], "volunteer" );
		}
		
		$text .= "<tr><td>&nbsp;</td><td>
		  <input name=\"submit\" type=\"submit\" value=\"$ok\">
		  </td></tr></table></form>";
		$text .= wfMsg( "boardvote_footer" );
		$wgOut->addHTML( $text );
	}

	function voteEntry( $index, $candidate, $name ) {
		if ( $index == -1 ) {
			$checked = " CHECKED";
		} else {
			$checked = "";
		}

		return "
		<tr><td align=\"right\">
		  <input type=\"radio\" name=\"$name\" value=\"$index\"$checked>
		</td><td align=\"left\">
		  $candidate
		</td></tr>";
	}

	function notLoggedIn() {
		global $wgOut;
		$wgOut->addWikiText( wfMsg( "boardvote_notloggedin" ) );
	}
	
	function notQualified() {
		global $wgOut;
		$wgOut->addWikiText( wfMsg( "boardvote_notqualified", $this->mUserDays ) );
	}
	
	function encrypt( $contributing, $volunteer ) {
		global $wgVolunteerCandidates, $wgContributingCandidates;
		global $wgGPGCommand, $wgGPGRecipient, $wgGPGHomedir;
		$file = @fopen( "/dev/urandom", "r" );
		if ( $file ) {
			$salt = implode( "", unpack( "H*", fread( $file, 64 ) ));
			fclose( $file );
		} else {
			$salt = Parser::getRandomString() . Parser::getRandomString();
		}
		$record = 
		  "Contributing: $contributing (" .$wgContributingCandidates[$contributing] . ")\n" .
		  "Volunteer: $volunteer (" . $wgVolunteerCandidates[$volunteer] . ")\n" .
		  "User: {$this->mUserKey}\n" .
		  "Salt: $salt\n";
		# Get file names
		$input = tempnam( "/tmp", "gpg_" );
		$output = tempnam( "/tmp", "gpg_" );

		# Write unencrypted record
		$file = fopen( $input, "w" );
		fwrite( $file, $record );
		fclose( $file );

		# Call GPG
		$command = wfEscapeShellArg( $wgGPGCommand ) . " --batch --yes -ear " . 
		  wfEscapeShellArg( $wgGPGRecipient ) . " -o " . wfEscapeShellArg( $output );
		if ( $wgGPGHomedir ) {
			$command .= " --homedir " . wfEscapeShellArg( $wgGPGHomedir );
		} 
		$command .= " " . wfEscapeShellArg( $input );

		shell_exec( $command );

		# Read result
		$result = file_get_contents( $output );

		# Delete temporary files
		unlink( $input );
		unlink( $output );
		
		return $result;
	}

	function getQualifications( &$user ) {
		$id = $user->getID();
		if ( !$id ) {
			$this->mUserDays = 0;
			$this->mUserEdits = 0;
			return;
		}

		# Count contributions and find earliest edit
		# First cur
		$sql = "SELECT COUNT(*) as n, MIN(cur_timestamp) as t FROM cur WHERE cur_user=$id";
		$res = wfQuery( $sql, DB_READ, "BoardVoteForm::getQualifications" );
		$cur = wfFetchObject( $res );
		wfFreeResult( $res );

		# If the user has stacks of contributions, don't check old as well
		$signup = wfTimestamp2Unix( $cur->t );
		$now = time();
		$days = ($now - $signup) / 86400;
		if ( $cur->n > 400 && $days > 180 ) {
			$this->mUserDays = 0x7fffffff;
			$this->mUserEdits = 0x7fffffff;
			return;
		}

		# Now check old
		$sql = "SELECT COUNT(*) as n, MIN(old_timestamp) as t FROM old WHERE old_user=$id";
		$res = wfQuery( $sql, DB_READ, "BoardVoteForm::getQualifications" );
		$old = wfFetchObject( $res );
		wfFreeResult( $res );
		
		$signup = min( wfTimestamp2Unix( $old->t ), $signup );
		$this->mUserDays = (int)(($now - $signup) / 86400);
		$this->mUserEdits = $cur->n + $old->n;
	}
	
	function displayList() {
		global $wgOut, $wgOutputEncoding, $wgLang, $wgUser;
		$sql = "SELECT log_timestamp,log_user_key FROM {$this->mDBname}.log";
		$res = wfQuery( $sql, DB_READ, "BoardVoteForm::list" );
		if ( wfNumRows( $res ) == 0 ) {
			$wgOut->addWikiText( wfMsg( "boardvote_novotes" ) );
			return;
		}
		$thisTitle = Title::makeTitle( NS_SPECIAL, "Boardvote" );
		$sk = $wgUser->getSkin();
		$dumpLink = $sk->makeKnownLinkObj( $thisTitle, wfMsg( "boardvote_dumplink" ), "action=dump" );
		
		$intro = wfMsg( "boardvote_listintro", $dumpLink );
		$hTime = wfMsg( "boardvote_time" );
		$hUser = wfMsg( "boardvote_user" );
		$hContributing = wfMsg( "boardvote_contributing" );
		$hVolunteer = wfMsg( "boardvote_volunteer" );

		$s = "$intro <table border=0><tr><th>
		    $hTime
		  </th><th>
		    $hUser
		  </th></tr>";

		while ( $row = wfFetchObject( $res ) ) {
			if ( $wgOutputEncoding != "utf-8" ) {
				$user = wfUtf8ToHTML( $row->log_user_key );
			} else {
				$user = $row->log_user_key;
			}
			$time = $wgLang->timeanddate( $row->log_timestamp );
			$s .= "<tr><td>
			    $time
			  </td><td>
			    $user
			  </td></tr>";
		}
		$s .= "</table>";
		$wgOut->addHTML( $s );
	}

	function dump() {
		global $wgOut, $wgOutputEncoding, $wgLang, $wgUser;

		$userRights = $wgUser->getRights();
		if ( in_array( "boardvote", $userRights ) ) {
			$admin = true;
		} else {
			$admin = false;
		}
		
		$sql = "SELECT * FROM {$this->mDBname}.log";
		$res = wfQuery( $sql, DB_READ, "BoardVoteForm::list" );
		if ( wfNumRows( $res ) == 0 ) {
			$wgOut->addWikiText( wfMsg( "boardvote_novotes" ) );
			return;
		}
		$s = "<table border=1>";
		if ( $admin ) {
			$s .= wfMsg( "boardvote_dumpheader" );
		}
		
		while ( $row = wfFetchObject( $res ) ) {
			if ( $wgOutputEncoding != "utf-8" ) {
				$user = wfUtf8ToHTML( $row->log_user_key );
			} else {
				$user = $row->log_user_key;
			}
			$time = $wgLang->timeanddate( $row->log_timestamp );
			$record = nl2br( $row->log_record );
			if ( $admin ) {
				$edits = $row->log_edits == 0x7fffffff ? "many" : $row->log_edits;
				$days = $row->log_days == 0x7fffffff ? "many" : $row->log_days;
				$s .= "<tr><td>
				  $time
				</td><td>
				  $user
				</td><td>
				  $edits
				</td><td>
				  $days
				</td><td>
				  {$row->log_ip}
				</td><td>
				  {$row->log_ua}
				</td><td>
				  $record
				</td></tr>";
			} else {
				$s .= "<tr><td>$time</td><td>$user</td><td>$record</td></tr>";
			}
		}
		$s .= "</table>";
		$wgOut->addHTML( $s );
	}
}
?>
