<?php

# Manages a page to edit a Revision Comment (Summary) and the "Minor change" flag.
class EditComment {
	var $mArticle, $mTitle, $origAction, $expiry, $fudgedExpiry; # from initializer
	var $revisionID, $returnTo, $returnToAction, $returnToTarget; # from HTTP parameters
	var $commentValue, $revisionTimeStamp, $revisionMinor, $revisionUID; # from article, etc.

	function EditComment( $article, $action ) {
		global $wgUser, $wgUserEditCommentTimeout;
	
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;
		$this->origAction = $action;
		$this->commentValue = "";
		
		# Come up with an earliest time that a user may edit an article.
		$this->expiry = $this->fudgedExpiry = '00000000000000'; # assume that any positive date is OK
		if (! $wgUser->isAllowed(EDIT_COMMENT_ALL) && $wgUserEditCommentTimeout >= 0) {
			
			$this->expiry = wfTimestampPlus( wfTimestampNow(), -$wgUserEditCommentTimeout * 60 );
		
			# If the user is allowed to change a time at all, add a little extra "fudge"
			# time to give the user the time to edit the change and save it.
			$fudgeTime = ($wgUserEditCommentTimeout) ? ($wgUserEditCommentTimeout + 2) : 
					$wgUserEditCommentTimeout;
			$this->fudgedExpiry = wfTimestampPlus( wfTimestampNow(), -$fudgeTime * 60);
		}
	}
	
	function process() {
		global $wgRequest;
		global $wgUser, $wgOut, $wgScript;
		global $wgAllowEditComments;
		
		# Pick up parameters that may be part of the URL.
		$this->revisionID = $wgRequest->getInt( 'oldid' );
		$this->returnTo = $wgRequest->getVal( 'returnto' );
		$this->returnToAction = $wgRequest->getVal( 'returntoaction' );
		$this->returnToTarget = $wgRequest->getVal( 'returntotarget' );
		
		# See if this is a submit.
		$doSave = $wgRequest->getVal( 'wpSave' );
		$doCancel = $wgRequest->getVal( 'wpCancel' );
		
		# We're either setting up the form for the first time, or picking existing form data.
		if (! $doSave && ! $doCancel) {
			
			$this->getRevisionComment();
			
			# Error out if the user is not allowed to edit this comment.
			if ( ! $wgAllowEditComments || ! $wgUser->getID() ||
				 ( ! $wgUser->isAllowed(EDIT_COMMENT_ALL) && $wgUser->getID() != $this->revisionUID )) {
				
				$wgOut->errorpage( "eceditsummary", "eccantdo" );
				return;
			}
			
			# Error out if the time the user had to edit this comment has expired.
			if ( $this->revisionTimeStamp < $this->expiry) {
				$wgOut->errorpage( "eceditsummary", "ecctimeexpired" );
				return;
			}
			
			$this->showForm( "" );
		}
		else {
			$this->revisionTimeStamp = $wgRequest->getText( 'wpTimestamp' );
			$this->revisionMinor = ( $wgRequest->getVal( 'wpMinorCheck' ) == "checked" ) ? 1 : 0;
		}
		
		# Save the new comment, if requested.
		if ($doSave) {
			$this->commentValue = $wgRequest->getText( 'wpComment' );
			
			# Error out if the time the user had to edit this comment has expired.
			if ( $this->revisionTimeStamp < $this->fudgedExpiry) {
				
				# Put the error message in the form to allow the user to copy their work.
				$this->showForm( wfMsg( "ecctimeexpired" ) );
				return;
			}
			
			# Note if something went wrong.
			$doWrong = ! $this->saveComment();
		}
		
		# If Cancel, restore the original values.
		if ($doCancel)
			$this->getRevisionComment();
		
		
		# Let the user know an operation completed.
		if ($doSave || $doCancel) {
			$sfmsg = "";
			if ($doSave) {
				if ($doWrong)
					$sfmsg = wfMsg( "ecsavewrong" );
				else
					$sfmsg = wfMsg( "ecsaveok" );
			}
			if ($doCancel)		# (shouldn't have both these messages.)
				$sfmsg .= wfMsg( "eccanceled" );
			
			$this->showForm( $sfmsg );
		}
	}
	
	# Show the Edit Comment (Summary) form. If $rsltMsg == "" the form is being
	# displayed for the first time, with buttons. Otherwise it is being shown
	# to provide confirmation and exit the process. Present a 'returnTo' link
	# in that case.
	function showForm( $rsltMsg )
	{
		global $wgOut, $wgUser, $wgLang;
		global $wgRequest;
		
		$rt = $ra = $rtt = $buttonRow = '';
		
		$wgOut->setArticleFlag( false );

		# The title is the article whose comments are being edited,
		# the subtitle "Editing summary of " revision.
		$wgOut->setPagetitle( $this->mTitle->getPrefixedText() );
		if ($this->revisionID == 0)
			$wgOut->setSubtitle( wfMsg( "ecsubtitlerev0" ) );
		else {
			$wgOut->setSubtitle( wfMsg( "ecsubtitle",
								 $wgLang->timeanddate( $this->revisionTimeStamp, true ) ) );
		}
		$wgOut->addHTML( "<p>" . htmlspecialchars( wfMsg( "ecwarnings" ) ) . "</p>" );
		# $wgOut->addWikiText( htmlspecialchars( wfMsg( "ecwarnings" ) ) );

		$editcommentlabel = htmlspecialchars( wfMsg( "eccommentlabel" ) );
		$editsubmit = htmlspecialchars( wfMsg( "ecsubmitbutton" ) );
		$editcancel = htmlspecialchars( wfMsg( "eccancelbutton" ) );
		$htmlcomment = htmlspecialchars( $this->commentValue );
		# $titleObj = Title::makeTitle( NS_SPECIAL, "Blockip" );
		# $action = $titleObj->escapeLocalURL( "action=editcomment" );
		
		if ($this->returnTo)
			$rt = "&returnto=" . $this->returnTo;
		if ($this->returnToAction)
			$ra = "&returntoaction=" . $this->returnToAction;
		if ($this->returnToTarget)
			$rtt = "&returntotarget=" . $this->returnToTarget;
		$action = $this->mTitle->escapeLocalURL( "action=editcomment&oldid=" . $this->revisionID . $rt . $ra . $rtt);

		# Either show the result message, or the Submit & Cancel buttons.
		if ( "" != $rsltMsg ) {
			# $wgOut->setSubtitle( htmlspecialchars( wfMsg( "formerror" ) ) );
			$wgOut->addHTML( "<p class='error'>{$rsltMsg}</p>\n" );
		}
		else {
			$buttonRow = "
		<tr>
			<td>&nbsp;</td>
			<td align=\"left\">
				<input tabindex='2' type='submit' name=\"wpSave\" value=\"{$editsubmit}\" />
				&nbsp;&nbsp;
				<input tabindex='3' type='submit' name=\"wpCancel\" value=\"{$editcancel}\" />
				<input type='hidden' name=\"wpTimestamp\" value=\"{$this->revisionTimeStamp}\" />
			</td>
		</tr>";
		}

		$minorHeading = wfMsg( "minoredit" );
		if ($this->revisionMinor == 1)
			$minorChecked = "checked";
		else
			$minorChecked = "";
		
		$wgOut->addHTML( "
<form id=\"editcomment\" method=\"post\" action=\"{$action}\">
	<table border='0'>
		<tr>
			<td align=\"right\">{$editcommentlabel}:</td>
			<td align=\"left\">
				<input tabindex='1' type='text' size='60' name=\"wpComment\" value=\"{$htmlcomment}\" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type='checkbox' name=\"wpMinorCheck\" value=\"checked\" {$minorChecked} />
				$minorHeading
			</td>
		</tr>$buttonRow
	</table>
</form>\n" );

		# Create a "return to" link, if appropriate.
		if ( "" != $rsltMsg ) {
			if ($this->returnToAction)
				$rtmRa  = "action=" . $this->returnToAction;
			if ($this->returnToTarget) {
				if ($rtmRa)
					$rtmRa  .= "&target=" . $this->returnToTarget;
				else
					$rtmRa  = "target=" . $this->returnToTarget;
			}
			
			$wgOut->returnToMain( true, $this->returnTo, $rtmRa );
		}

	}
	
	# Save the required comment. Returns true if we performed a successful update.
	# Do last microsecond check to insure someone isn't trying some shenanigans.
	function saveComment()
	{
		global $wgOut, $wgIsMySQL, $wgIsPg, $wgUser;
		
		$usrChk = '';
		
		
		# A user better be logged in.
		if ( ! $wgUser->getID() )
			return false;
		
		if ($this->revisionID == 0) {
			
			# Pick up the old comment for the Recent Changes log.
			$oldComment = $this->mArticle->getComment();
			
			# Unless the user is a SysOp, they'd better be the person who made
			# the revision we're checking.
			if (! $wgUser->isAllowed(EDIT_COMMENT_ALL) )
				$usrChk = ' AND cur_user=' . $wgUser->getID();
			
			# We also want to make sure the date of the revision hasn't changed
			# "under our feet", as you will and that the expiry has not passed.
			$fname = "editComment";
			$sql = "UPDATE cur SET cur_comment='" .  wfStrencode( $this->commentValue ) . "', " .
			  "cur_minor_edit=" . $this->revisionMinor . " " .
			  "WHERE cur_id=" . $this->mArticle->getID() .
			  " AND cur_timestamp='" . $this->revisionTimeStamp . "'" .
			  " AND cur_timestamp>='" . $this->fudgedExpiry . "'" . $usrChk;
			$res = wfQuery( $sql, DB_WRITE, $fname );
		}
		else {
			
			# Pick up the old comment for the Recent Changes log.
			$fname = "editComment";
			$oldtable = $wgIsPg ? '"old"' : 'old';
			$sql = "SELECT old_comment FROM $oldtable " .
			  "WHERE old_id={$this->revisionID}";
			$res = wfQuery( $sql, DB_READ, $fname );
			
			if ( 0 == wfNumRows( $res ) ) {
				return false;
			}
			$s = wfFetchObject( $res );
			$oldComment = $s->old_comment;
			
			# Unless the user is a SysOp, they'd better be the person who made
			# the revision we're checking.
			if (! $wgUser->isAllowed(EDIT_COMMENT_ALL) )
				$usrChk = ' AND old_user=' . $wgUser->getID();
			
			# Check that the expiry has not passed.
			$sql = "UPDATE $oldtable SET old_comment='" .  wfStrencode( $this->commentValue ) . "', " .
			  "old_minor_edit=" . $this->revisionMinor . " " .
			  "WHERE old_id={$this->revisionID}" .
			  " AND old_timestamp>='" . $this->fudgedExpiry . "'" . $usrChk;
			$res = wfQuery( $sql, DB_WRITE, $fname );
		}
		
		# Set a result based on whether any records got updated.
		if( wfAffectedRows() == 0 ) {
			return false;
		}
		
		# We need to invalidate the cache or the history page will not get refreshed.
		$wgUser->invalidateCache();
		$wgUser->saveSettings();		# Seems pretty drastic!
		
		# Log the comment change in Recent Changes.
		RecentChange::notifyEditComment( wfTimestampNow(), $this->mTitle,
										 $this->revisionMinor, $wgUser, $this->commentValue, $oldComment,
										 $this->revisionID, $this->revisionTimeStamp);
		
		return true;					# We were successful.
	}
	
	# Pick up the comment to be edited, as well as the revision time stamp and the
	# user to make sure it can be edited.
	function getRevisionComment()
	{
		global $wgOut, $wgIsMySQL, $wgIsPg;
		
		# If rev 0, we can use the article to get the data, otherwise get it ourselves.
		if ($this->revisionID == 0) {
			$this->commentValue = $this->mArticle->getComment();
			$this->revisionTimeStamp = $this->mArticle->getTimestamp();
			$this->revisionMinor = $this->mArticle->getMinorEdit();
			$this->revisionUID = $this->mArticle->getUser();
		}
		else
		{
			$fname = "editComment";
			$oldtable = $wgIsPg ? '"old"' : 'old';
			$sql = "SELECT old_comment,old_timestamp,old_minor_edit,old_user ".
			  "FROM $oldtable " .
			  "WHERE old_id={$this->revisionID}";
			$res = wfQuery( $sql, DB_READ, $fname );
			
			if ( 0 == wfNumRows( $res ) ) {
				return false;
			}
			$s = wfFetchObject( $res );
			$this->commentValue = $s->old_comment;
			$this->revisionTimeStamp = $s->old_timestamp;
			$this->revisionMinor = $s->old_minor_edit;
			$this->revisionUID = $s->old_user;
			wfFreeResult( $res );
		}
	}
}

?>
