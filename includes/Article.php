<?
# Class representing a Wikipedia article and history.
# See design.doc for an overview.

# Note: edit user interface and cache support functions have been
# moved to separate EditPage and CacheManager classes.

/* CHECK MERGE @@@
   TEST THIS @@@

   * s/\$wgTitle/\$this->mTitle/ performed, many replacements
   * mTitle variable added to class
*/

include_once( "CacheManager.php" );

class Article {
	/* private */ var $mContent, $mContentLoaded;
	/* private */ var $mUser, $mTimestamp, $mUserText;
	/* private */ var $mCounter, $mComment, $mCountAdjustment;
	/* private */ var $mMinorEdit, $mRedirectedFrom;
	/* private */ var $mTouched, $mFileCache, $mTitle;

	function Article( &$title ) {
		$this->mTitle =& $title;
		$this->clear();
	}

	/* private */ function clear()
	{
		$this->mContentLoaded = false;
		$this->mUser = $this->mCounter = -1; # Not loaded
		$this->mRedirectedFrom = $this->mUserText =
		$this->mTimestamp = $this->mComment = $this->mFileCache = "";
		$this->mCountAdjustment = 0;
		$this->mTouched = "19700101000000";
	}

	# Note that getContent/loadContent may follow redirects if
	# not told otherwise, and so may cause a change to mTitle.

	function getContent( $noredir = false )
	{
		global $action,$section,$count; # From query string
		$fname =  "Article::getContent"; 
		wfProfileIn( $fname );

		if ( 0 == $this->getID() ) {
			if ( "edit" == $action ) {
				wfProfileOut( $fname );
				return ""; # was "newarticletext", now moved above the box)
			}
			wfProfileOut( $fname );
			return wfMsg( "noarticletext" );
		} else {
			$this->loadContent( $noredir );
						
			if(
				# check if we're displaying a [[User talk:x.x.x.x]] anonymous talk page
				( $this->mTitle->getNamespace() == Namespace::getTalk( Namespace::getUser()) ) &&
				  preg_match("/^\d{1,3}\.\d{1,3}.\d{1,3}\.\d{1,3}$/",$this->mTitle->getText()) &&
				  $action=="view"
				) 
				{
				wfProfileOut( $fname );
				return $this->mContent . "\n" .wfMsg("anontalkpagetext"); }
			else {				
				if($action=="edit") {
					if($section!="") {
						if($section=="new") { 
							wfProfileOut( $fname );
							return ""; 
						}

						$secs=preg_split("/(^=+.*?=+|^<h[1-6].*?>.*?<\/h[1-6].*?>)/mi",
						 $this->mContent, -1,
						 PREG_SPLIT_DELIM_CAPTURE);
						if($section==0) {
							wfProfileOut( $fname );
							return trim($secs[0]);
						} else {
							wfProfileOut( $fname );
							return trim($secs[$section*2-1] . $secs[$section*2]);
						}
					}
				}
				wfProfileOut( $fname );
				return $this->mContent;
			}
		}
	}

	function loadContent( $noredir = false )
	{
		global $wgOut, $wgMwRedir;
		global $oldid, $redirect; # From query

		if ( $this->mContentLoaded ) return;
		$fname = "Article::loadContent";

		# Pre-fill content with error message so that if something
		# fails we'll have something telling us what we intended.

		$t = $this->mTitle->getPrefixedText();
		if ( isset( $oldid ) ) {
			$oldid = IntVal( $oldid );
			$t .= ",oldid={$oldid}";
		}
		if ( isset( $redirect ) ) {
			$redirect = ($redirect == "no") ? "no" : "yes";
			$t .= ",redirect={$redirect}";
		}
		$this->mContent = wfMsg( "missingarticle", $t );

		if ( ! $oldid ) {	# Retrieve current version
			$id = $this->getID();
			if ( 0 == $id ) return;

			$sql = "SELECT " .
			  "cur_text,cur_timestamp,cur_user,cur_counter,cur_restrictions,cur_touched " .
			  "FROM cur WHERE cur_id={$id}";
			wfDebug( "$sql\n" );
			$res = wfQuery( $sql, DB_READ, $fname );
			if ( 0 == wfNumRows( $res ) ) { 
				return; 
			}

			$s = wfFetchObject( $res );
			# If we got a redirect, follow it (unless we've been told
			# not to by either the function parameter or the query
			if ( ( "no" != $redirect ) && ( false == $noredir ) &&
			  ( $wgMwRedir->matchStart( $s->cur_text ) ) ) {
				if ( preg_match( "/\\[\\[([^\\]\\|]+)[\\]\\|]/",
				  $s->cur_text, $m ) ) {
					$rt = Title::newFromText( $m[1] );

					# Gotta hand redirects to special pages differently:
					# Fill the HTTP response "Location" header and ignore
					# the rest of the page we're on.

					if ( $rt->getInterwiki() != "" ) {
						$wgOut->redirect( $rt->getFullURL() ) ;
						return;
					}
					if ( $rt->getNamespace() == Namespace::getSpecial() ) {
						$wgOut->redirect( wfLocalUrl(
						  $rt->getPrefixedURL() ) );
						return;
					}
					$rid = $rt->getArticleID();
					if ( 0 != $rid ) {
						$sql = "SELECT cur_text,cur_timestamp,cur_user," .
						  "cur_counter,cur_touched FROM cur WHERE cur_id={$rid}";
						$res = wfQuery( $sql, DB_READ, $fname );

						if ( 0 != wfNumRows( $res ) ) {
							$this->mRedirectedFrom = $this->mTitle->getPrefixedText();
							$this->mTitle = $rt;
							$s = wfFetchObject( $res );
						}
					}
				}
			}

			$this->mContent = $s->cur_text;
			$this->mUser = $s->cur_user;
			$this->mCounter = $s->cur_counter;
			$this->mTimestamp = $s->cur_timestamp;
			$this->mTouched = $s->cur_touched;
			$this->mTitle->mRestrictions = explode( ",", trim( $s->cur_restrictions ) );
			$this->mTitle->mRestrictionsLoaded = true;
			wfFreeResult( $res );
		} else { # oldid set, retrieve historical version
			$sql = "SELECT old_text,old_timestamp,old_user FROM old " .
			  "WHERE old_id={$oldid}";
			$res = wfQuery( $sql, DB_READ, $fname );
			if ( 0 == wfNumRows( $res ) ) { return; }

			$s = wfFetchObject( $res );
			$this->mContent = $s->old_text;
			$this->mUser = $s->old_user;
			$this->mCounter = 0;
			$this->mTimestamp = $s->old_timestamp;
			wfFreeResult( $res );
		}
		$this->mContentLoaded = true;
	}

	function getID() { return $this->mTitle->getArticleID(); }

	function getCount()
	{
		if ( -1 == $this->mCounter ) {
			$id = $this->getID();
			$this->mCounter = wfGetSQL( "cur", "cur_counter", "cur_id={$id}" );
		}
		return $this->mCounter;
	}

	# Would the given text make this article a "good" article (i.e.,
	# suitable for including in the article count)?

	function isCountable( $text )
	{
		global $wgUseCommaCount, $wgMwRedir;
		
		if ( 0 != $this->mTitle->getNamespace() ) { return 0; }
		if ( $wgMwRedir->matchStart( $text ) ) { return 0; }
		$token = ($wgUseCommaCount ? "," : "[[" );
		if ( false === strstr( $text, $token ) ) { return 0; }
		return 1;
	}

	# Load the field related to the last edit time of the article.
	# This isn't necessary for all uses, so it's only done if needed.

	/* private */ function loadLastEdit()
	{
		global $wgOut;
		if ( -1 != $this->mUser ) return;

		$sql = "SELECT cur_user,cur_user_text,cur_timestamp," .
		  "cur_comment,cur_minor_edit FROM cur WHERE " .
		  "cur_id=" . $this->getID();
		$res = wfQuery( $sql, DB_READ, "Article::loadLastEdit" );

		if ( wfNumRows( $res ) > 0 ) {
			$s = wfFetchObject( $res );
			$this->mUser = $s->cur_user;
			$this->mUserText = $s->cur_user_text;
			$this->mTimestamp = $s->cur_timestamp;
			$this->mComment = $s->cur_comment;
			$this->mMinorEdit = $s->cur_minor_edit;
		}
	}

	function getTimestamp()
	{
		$this->loadLastEdit();
		return $this->mTimestamp;
	}

	function getUser()
	{
		$this->loadLastEdit();
		return $this->mUser;
	}

	function getUserText()
	{
		$this->loadLastEdit();
		return $this->mUserText;
	}

	function getComment()
	{
		$this->loadLastEdit();
		return $this->mComment;
	}

	function getMinorEdit()
	{
		$this->loadLastEdit();
		return $this->mMinorEdit;
	}

	# This is the default action of the script: just view the page of
	# the given title.

	function view()
	{
		global $wgUser, $wgOut, $wgLang;
		global $oldid, $diff; # From query
		global $wgLinkCache, $IP;
		$fname = "Article::view";
		wfProfileIn( $fname );

		$wgOut->setArticleFlag( true );
		$wgOut->setRobotpolicy( "index,follow" );

		# If we got diff and oldid in the query, we want to see a
		# diff page instead of the article.

		if ( isset( $diff ) ) {
			$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
			$de = new DifferenceEngine( $oldid, $diff );
			$de->showDiffPage();
			wfProfileOut( $fname );
			return;
		}

		if ( !isset( $oldid ) ) {
			if( $this->checkTouched() ) {
				$wgOut->checkLastModified( $this->mTouched );
				$this->tryFileCache();
			}
		}

		$text = $this->getContent(); # May change mTitle
		$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
		$wgOut->setHTMLTitle( $this->mTitle->getPrefixedText() .
		  " - " . wfMsg( "wikititlesuffix" ) );

		# We're looking at an old revision

		if ( $oldid ) {
			$this->setOldSubtitle();
			$wgOut->setRobotpolicy( "noindex,follow" );
		}
		if ( "" != $this->mRedirectedFrom ) {
			$sk = $wgUser->getSkin();
			$redir = $sk->makeKnownLink( $this->mRedirectedFrom, "",
			  "redirect=no" );
			$s = str_replace( "$1", $redir, wfMsg( "redirectedfrom" ) );
			$wgOut->setSubtitle( $s );
		}
		$wgOut->checkLastModified( $this->mTouched );
		$this->tryFileCache();
		$wgLinkCache->preFill( $this->mTitle );
		$wgOut->addWikiText( $text );

		$this->viewUpdates();
		wfProfileOut( $fname );
	}

	# This is the function that gets called for "action=edit".

	function edit()
	{
		global $wgOut, $wgUser;
		global $wpTextbox1, $wpSummary, $wpSave, $wpPreview;
		global $wpMinoredit, $wpEdittime, $wpTextbox2;

		$fields = array( "wpTextbox1", "wpSummary", "wpTextbox2" );
		wfCleanFormFields( $fields );

		if ( ! $this->mTitle->userCanEdit() ) {
			$wgOut->readOnlyPage( $this->getContent(), true );
			return;
		}
		if ( $wgUser->isBlocked() ) {
			$this->blockedIPpage();
			return;
		}
		if ( wfReadOnly() ) {
			if( isset( $wpSave ) or isset( $wpPreview ) ) {
				$this->editForm( "preview" );
			} else {
				$wgOut->readOnlyPage( $this->getContent() );
			}
			return;
		}
		if ( $_SERVER['REQUEST_METHOD'] != "POST" ) unset( $wpSave );
		if ( isset( $wpSave ) ) {
			$this->editForm( "save" );
		} else if ( isset( $wpPreview ) ) {
			$this->editForm( "preview" );
		} else { # First time through
			$this->editForm( "initial" );
		}
	}

	# Since there is only one text field on the edit form,
	# pressing <enter> will cause the form to be submitted, but
	# the submit button value won't appear in the query, so we
	# Fake it here before going back to edit().  This is kind of
	# ugly, but it helps some old URLs to still work.

	function submit()
	{
		global $wpSave, $wpPreview;
		if ( ! isset( $wpPreview ) ) { $wpSave = 1; }

		$this->edit();
	}

	# The edit form is self-submitting, so that when things like
	# preview and edit conflicts occur, we get the same form back
	# with the extra stuff added.  Only when the final submission
	# is made and all is well do we actually save and redirect to
	# the newly-edited page.

	function editForm( $formtype )
	{
		global $wgOut, $wgUser;
		global $wpTextbox1, $wpSummary, $wpWatchthis;
		global $wpSave, $wpPreview;
		global $wpMinoredit, $wpEdittime, $wpTextbox2, $wpSection;
		global $oldid, $redirect, $section;
		global $wgLang;

		if(isset($wpSection)) { $section=$wpSection; } else { $wpSection=$section; }

		$sk = $wgUser->getSkin();
		$isConflict = false;
		$wpTextbox1 = rtrim ( $wpTextbox1 ) ; # To avoid text getting longer on each preview

		if(!$this->mTitle->getArticleID()) { # new article

			$wgOut->addWikiText(wfmsg("newarticletext"));

		}

		# Attempt submission here.  This will check for edit conflicts,
		# and redundantly check for locked database, blocked IPs, etc.
		# that edit() already checked just in case someone tries to sneak
		# in the back door with a hand-edited submission URL.

		if ( "save" == $formtype ) {
			if ( $wgUser->isBlocked() ) {
				$this->blockedIPpage();
				return;
			}
			if ( wfReadOnly() ) {
				$wgOut->readOnlyPage();
				return;
			}
			# If article is new, insert it.
			
			$aid = $this->mTitle->getArticleID();			
			if ( 0 == $aid ) {
				# we need to strip Windoze linebreaks because some browsers 
				# append them and the string comparison fails
				if ( ( "" == $wpTextbox1 ) ||
				  ( wfMsg( "newarticletext" ) == rtrim( preg_replace("/\r/","",$wpTextbox1) ) ) ) {
					$wgOut->redirect(  wfLocalUrl(
					  $this->mTitle->getPrefixedURL() ) );
					return;
				}
				$this->mCountAdjustment = $this->isCountable( $wpTextbox1 );
				$this->insertNewArticle( $wpTextbox1, $wpSummary, $wpMinoredit, $wpWatchthis );
				return;
			}
			# Article exists. Check for edit conflict.
			# Don't check for conflict when appending a comment - this should always work

			$this->clear(); # Force reload of dates, etc.
			if ( $section!="new" && ( $this->getTimestamp() != $wpEdittime ) ) { 
				$isConflict = true;		
			}
			$u = $wgUser->getID();

			# Supress edit conflict with self

			if ( ( 0 != $u ) && ( $this->getUser() == $u ) ) {
				$isConflict = false;
			} else {
				# switch from section editing to normal editing in edit conflict
				if($isConflict) {
					$section="";$wpSection="";
				}

			}
			if ( ! $isConflict ) {
				# All's well: update the article here
				if($this->updateArticle( $wpTextbox1, $wpSummary, $wpMinoredit, $wpWatchthis, $wpSection ))
					return;
				else
					$isConflict = true;
			}
		}
		# First time through: get contents, set time for conflict
		# checking, etc.

		if ( "initial" == $formtype ) {
			$wpEdittime = $this->getTimestamp();
			$wpTextbox1 = $this->getContent(true);
			$wpSummary = "";
		}
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		$wgOut->setArticleFlag( false );

		if ( $isConflict ) {
			$s = str_replace( "$1", $this->mTitle->getPrefixedText(),
			  wfMsg( "editconflict" ) );
			$wgOut->setPageTitle( $s );
			$wgOut->addHTML( wfMsg( "explainconflict" ) );

			$wpTextbox2 = $wpTextbox1;
			$wpTextbox1 = $this->getContent(true);
			$wpEdittime = $this->getTimestamp();
		} else {
			$s = str_replace( "$1", $this->mTitle->getPrefixedText(),
			  wfMsg( "editing" ) );

			if($section!="") { 
				if($section=="new") {
					$s.=wfMsg("commentedit");
				} else {
					$s.=wfMsg("sectionedit");
				}
			}
			$wgOut->setPageTitle( $s );
			if ( $oldid ) {
				$this->setOldSubtitle();
				$wgOut->addHTML( wfMsg( "editingold" ) );
			}
		}

		if( wfReadOnly() ) {
			$wgOut->addHTML( "<strong>" .
				wfMsg( "readonlywarning" ) .
				"</strong>" );
		}
		if( $this->mTitle->isProtected() ) {
			$wgOut->addHTML( "<strong>" . wfMsg( "protectedpagewarning" ) .
			  "</strong><br />\n" );
		}

		$kblength = (int)(strlen( $wpTextbox1 ) / 1024);
		if( $kblength > 29 ) {
			$wgOut->addHTML( "<strong>" . 
				str_replace( '$1', $kblength , wfMsg( "longpagewarning" ) )
				. "</strong>" );
		}
		
		$rows = $wgUser->getOption( "rows" );
		$cols = $wgUser->getOption( "cols" );

		$ew = $wgUser->getOption( "editwidth" );
		if ( $ew ) $ew = " style=\"width:100%\"";
		else $ew = "" ;

		$q = "action=submit";
		if ( "no" == $redirect ) { $q .= "&redirect=no"; }
		$action = wfEscapeHTML( wfLocalUrl( $this->mTitle->getPrefixedURL(), $q ) );

		$summary = wfMsg( "summary" );		
		$subject = wfMsg("subject");
		$minor = wfMsg( "minoredit" );
		$watchthis = wfMsg ("watchthis");
		$save = wfMsg( "savearticle" );
		$prev = wfMsg( "showpreview" );

		$cancel = $sk->makeKnownLink( $this->mTitle->getPrefixedURL(),
		  wfMsg( "cancel" ) );
		$edithelp = $sk->makeKnownLink( wfMsg( "edithelppage" ),
		  wfMsg( "edithelp" ) );
		$copywarn = str_replace( "$1", $sk->makeKnownLink(
		  wfMsg( "copyrightpage" ) ), wfMsg( "copyrightwarning" ) );

		$wpTextbox1 = wfEscapeHTML( $wpTextbox1 );
		$wpTextbox2 = wfEscapeHTML( $wpTextbox2 );
		$wpSummary = wfEscapeHTML( $wpSummary );
		
		// activate checkboxes if user wants them to be always active
		if (!$wpPreview && $wgUser->getOption("watchdefault")) $wpWatchthis=1;
		if (!$wpPreview && $wgUser->getOption("minordefault")) $wpMinoredit=1;		
		
		// activate checkbox also if user is already watching the page,
		// require wpWatchthis to be unset so that second condition is not
		// checked unnecessarily
		if (!$wpWatchthis && !$wpPreview && $this->mTitle->userIsWatching()) $wpWatchthis=1;
		
		if ( 0 != $wgUser->getID() ) {
			$checkboxhtml=
			"<input tabindex=3 type=checkbox value=1 name='wpMinoredit'".($wpMinoredit?" checked":"").">{$minor}".
			"<input tabindex=4 type=checkbox name='wpWatchthis'".($wpWatchthis?" checked":"").">{$watchthis}<br>";
			
		} else {
			$checkboxhtml="";
		}


		if ( "preview" == $formtype) {
		
			$previewhead="<h2>" . wfMsg( "preview" ) . "</h2>\n<p><large><center><font color=\"#cc0000\">" . 
			wfMsg( "note" ) . wfMsg( "previewnote" ) . "</font></center></large><P>\n";
			if ( $isConflict ) {
				$previewhead.="<h2>" . wfMsg( "previewconflict" ) .
				  "</h2>\n";
			}
			$previewtext = wfUnescapeHTML( $wpTextbox1 );
			
			if($wgUser->getOption("previewontop")) {
				$wgOut->addHTML($previewhead);
				$wgOut->addWikiText( $this->preSaveTransform( $previewtext ) ."\n\n");
			}
			$wgOut->addHTML( "<br clear=\"all\" />\n" );
		}

		# if this is a comment, show a subject line at the top, which is also the edit summary.
		# Otherwise, show a summary field at the bottom
		if($section=="new") {

			$commentsubject="{$subject}: <input tabindex=1 type=text value=\"{$wpSummary}\" name=\"wpSummary\" maxlength=200 size=60><br>";
		} else {

			$editsummary="{$summary}: <input tabindex=3 type=text value=\"{$wpSummary}\" name=\"wpSummary\" maxlength=200 size=60><br>";
		}

		$wgOut->addHTML( "
<form id=\"editform\" name=\"editform\" method=\"post\" action=\"$action\"
enctype=\"application/x-www-form-urlencoded\">
{$commentsubject}
<textarea tabindex=2 name=\"wpTextbox1\" rows={$rows}
cols={$cols}{$ew} wrap=\"virtual\">" .
$wgLang->recodeForEdit( $wpTextbox1 ) .
"
</textarea>
<br>{$editsummary}
{$checkboxhtml}
<input tabindex=5 type=submit value=\"{$save}\" name=\"wpSave\">
<input tabindex=6 type=submit value=\"{$prev}\" name=\"wpPreview\">
<em>{$cancel}</em> | <em>{$edithelp}</em>
<br><br>{$copywarn}
<input type=hidden value=\"{$section}\" name=\"wpSection\">
<input type=hidden value=\"{$wpEdittime}\" name=\"wpEdittime\">\n" );

		if ( $isConflict ) {
			$wgOut->addHTML( "<h2>" . wfMsg( "yourdiff" ) . "</h2>\n" );
			DifferenceEngine::showDiff( $wpTextbox2, $wpTextbox1,
			  wfMsg( "yourtext" ), wfMsg( "storedversion" ) );

			$wgOut->addHTML( "<h2>" . wfMsg( "yourtext" ) . "</h2>
<textarea tabindex=6 name=\"wpTextbox2\" rows={$rows} cols={$cols} wrap=virtual>"
. $wgLang->recodeForEdit( $wpTextbox2 ) .
"
</textarea>" );
		}
		$wgOut->addHTML( "</form>\n" );
		if($formtype =="preview" && !$wgUser->getOption("previewontop")) {
			$wgOut->addHTML($previewhead);
			$wgOut->addWikiText( $this->preSaveTransform( $previewtext ) );
		}
	}

	# Theoretically we could defer these whole insert and update
	# functions for after display, but that's taking a big leap
	# of faith, and we want to be able to report database
	# errors at some point.

	/* private */ function insertNewArticle( $text, $summary, $isminor, $watchthis )
	{
		global $wgOut, $wgUser, $wgLinkCache, $wgMwRedir;
		global $wgEnablePersistentLC;
		
		$fname = "Article::insertNewArticle";

		$ns = $this->mTitle->getNamespace();
		$ttl = $this->mTitle->getDBkey();
		$text = $this->preSaveTransform( $text );
		if ( $wgMwRedir->matchStart( $text ) ) { $redir = 1; }
		else { $redir = 0; }

		$now = wfTimestampNow();
		$won = wfInvertTimestamp( $now );
		wfSeedRandom();
		$rand = number_format( mt_rand() / mt_getrandmax(), 12, ".", "" );
		$sql = "INSERT INTO cur (cur_namespace,cur_title,cur_text," .
		  "cur_comment,cur_user,cur_timestamp,cur_minor_edit,cur_counter," .
		  "cur_restrictions,cur_user_text,cur_is_redirect," .
		  "cur_is_new,cur_random,cur_touched,inverse_timestamp) VALUES ({$ns},'" . wfStrencode( $ttl ) . "', '" .
		  wfStrencode( $text ) . "', '" .
		  wfStrencode( $summary ) . "', '" .
		  $wgUser->getID() . "', '{$now}', " .
		  ( $isminor ? 1 : 0 ) . ", 0, '', '" .
		  wfStrencode( $wgUser->getName() ) . "', $redir, 1, $rand, '{$now}', '{$won}')";
		$res = wfQuery( $sql, DB_WRITE, $fname );

		$newid = wfInsertId();
		$this->mTitle->resetArticleID( $newid );

		if ( $wgEnablePersistentLC ) {
			// Purge related entries in links cache on new page, to heal broken links
			$ptitle = wfStrencode( $ttl );
			wfQuery("DELETE linkscc FROM linkscc,brokenlinks ".
				"WHERE lcc_pageid=bl_from AND bl_to='{$ptitle}'", DB_WRITE);
		}
		
		$sql = "INSERT INTO recentchanges (rc_timestamp,rc_cur_time," .
		  "rc_namespace,rc_title,rc_new,rc_minor,rc_cur_id,rc_user," .
		  "rc_user_text,rc_comment,rc_this_oldid,rc_last_oldid,rc_bot) VALUES (" .
		  "'{$now}','{$now}',{$ns},'" . wfStrencode( $ttl ) . "',1," .
		  ( $isminor ? 1 : 0 ) . ",{$newid}," . $wgUser->getID() . ",'" .
		  wfStrencode( $wgUser->getName() ) . "','" .
		  wfStrencode( $summary ) . "',0,0," .
		  ( $wgUser->isBot() ? 1 : 0 ) . ")";
		wfQuery( $sql, DB_WRITE, $fname );
		if ($watchthis) { 		
			if(!$this->mTitle->userIsWatching()) $this->watch(); 
		} else {
			if ( $this->mTitle->userIsWatching() ) {
				$this->unwatch();
			}
		}
		
		# The talk page isn't in the regular link tables, so we need to update manually:
		$talkns = $ns ^ 1; # talk -> normal; normal -> talk
		$sql = "UPDATE cur set cur_touched='$now' WHERE cur_namespace=$talkns AND cur_title='" . wfStrencode( $ttl ) . "'";
		wfQuery( $sql, DB_WRITE );
		
		$this->showArticle( $text, wfMsg( "newarticle" ) );
	}

	function updateArticle( $text, $summary, $minor, $watchthis, $section = "")
	{
		global $wgOut, $wgUser, $wgLinkCache;
		global $wgDBtransactions, $wgMwRedir;
		$fname = "Article::updateArticle";

		$this->loadLastEdit();

		// insert updated section into old text if we have only edited part 
		// of the article		
		if ($section != "") {			
			$oldtext=$this->getContent();
			if($section=="new") {
				if($summary) $subject="== {$summary} ==\n\n";
				$text=$oldtext."\n\n".$subject.$text;
			} else {
				$secs=preg_split("/(^=+.*?=+|^<h[1-6].*?>.*?<\/h[1-6].*?>)/mi",
				  $oldtext,-1,PREG_SPLIT_DELIM_CAPTURE);
				$secs[$section*2]=$text."\n\n"; // replace with edited
				if($section) { $secs[$section*2-1]=""; } // erase old headline
				$text=join("",$secs);		
			}
		}
		if ( $this->mMinorEdit ) { $me1 = 1; } else { $me1 = 0; }
		if ( $minor ) { $me2 = 1; } else { $me2 = 0; }		
		if ( preg_match( "/^((" . $wgMwRedir->getBaseRegex() . ")[^\\n]+)/i", $text, $m ) ) {
			$redir = 1;
			$text = $m[1] . "\n"; # Remove all content but redirect
		}
		else { $redir = 0; }

		$text = $this->preSaveTransform( $text );
		
		# Update article, but only if changed.

		if( $wgDBtransactions ) {
			$sql = "BEGIN";
			wfQuery( $sql, DB_WRITE );
		}
		$oldtext = $this->getContent( true );

		if ( 0 != strcmp( $text, $oldtext ) ) {
			$this->mCountAdjustment = $this->isCountable( $text )
			  - $this->isCountable( $oldtext );

			$now = wfTimestampNow();
			$won = wfInvertTimestamp( $now );
			$sql = "UPDATE cur SET cur_text='" . wfStrencode( $text ) .
			  "',cur_comment='" .  wfStrencode( $summary ) .
			  "',cur_minor_edit={$me2}, cur_user=" . $wgUser->getID() .
			  ",cur_timestamp='{$now}',cur_user_text='" .
			  wfStrencode( $wgUser->getName() ) .
			  "',cur_is_redirect={$redir}, cur_is_new=0, cur_touched='{$now}', inverse_timestamp='{$won}' " .
			  "WHERE cur_id=" . $this->getID() .
			  " AND cur_timestamp='" . $this->getTimestamp() . "'";
			$res = wfQuery( $sql, DB_WRITE, $fname );
			
			if( wfAffectedRows() == 0 ) {
				/* Belated edit conflict! Run away!! */
				return false;
			}

			$sql = "INSERT INTO old (old_namespace,old_title,old_text," .
			  "old_comment,old_user,old_user_text,old_timestamp," .
			  "old_minor_edit,inverse_timestamp) VALUES (" .
			  $this->mTitle->getNamespace() . ", '" .
			  wfStrencode( $this->mTitle->getDBkey() ) . "', '" .
			  wfStrencode( $oldtext ) . "', '" .
			  wfStrencode( $this->getComment() ) . "', " .
			  $this->getUser() . ", '" .
			  wfStrencode( $this->getUserText() ) . "', '" .
			  $this->getTimestamp() . "', " . $me1 . ", '" .
			  wfInvertTimestamp( $this->getTimestamp() ) . "')";
			$res = wfQuery( $sql, DB_WRITE, $fname );
			$oldid = wfInsertID( $res );

			$sql = "INSERT INTO recentchanges (rc_timestamp,rc_cur_time," .
			  "rc_namespace,rc_title,rc_new,rc_minor,rc_bot,rc_cur_id,rc_user," .
			  "rc_user_text,rc_comment,rc_this_oldid,rc_last_oldid) VALUES (" .
			  "'{$now}','{$now}'," . $this->mTitle->getNamespace() . ",'" .
			  wfStrencode( $this->mTitle->getDBkey() ) . "',0,{$me2}," .
			  ( $wgUser->isBot() ? 1 : 0 ) . "," .
			  $this->getID() . "," . $wgUser->getID() . ",'" .
			  wfStrencode( $wgUser->getName() ) . "','" .
			  wfStrencode( $summary ) . "',0,{$oldid})";
			wfQuery( $sql, DB_WRITE, $fname );

			$sql = "UPDATE recentchanges SET rc_this_oldid={$oldid} " .
			  "WHERE rc_namespace=" . $this->mTitle->getNamespace() . " AND " .
			  "rc_title='" . wfStrencode( $this->mTitle->getDBkey() ) . "' AND " .
			  "rc_timestamp='" . $this->getTimestamp() . "'";
			wfQuery( $sql, DB_WRITE, $fname );

			$sql = "UPDATE recentchanges SET rc_cur_time='{$now}' " .
			  "WHERE rc_cur_id=" . $this->getID();
			wfQuery( $sql, DB_WRITE, $fname );
			
			if ( $wgEnablePersistentLC ) {

				// Purge link cache for this page 
				$pageid=$this->getID();
				wfQuery("DELETE FROM linkscc WHERE lcc_pageid='{$pageid}'", DB_WRITE);

				// This next query just makes sure stub colored links to this page 
				// are updated correctly (I think). If performance is more important
				// than real-time updating of stub links, we really should skip
				// this query.
				wfQuery("DELETE linkscc FROM linkscc,links ".
					"WHERE lcc_title=links.l_from AND l_to={$pageid}", DB_WRITE);
			}

		}
		if( $wgDBtransactions ) {
			$sql = "COMMIT";
			wfQuery( $sql, DB_WRITE );
		}
		
		if ($watchthis) { 
			if (!$this->mTitle->userIsWatching()) $this->watch();			
		} else {
			if ( $this->mTitle->userIsWatching() ) {
				$this->unwatch();
			}
		}

		$this->showArticle( $text, wfMsg( "updated" ) );
		return true;
	}

	# After we've either updated or inserted the article, update
	# the link tables and redirect to the new page.

	function showArticle( $text, $subtitle )
	{
		global $wgOut, $wgUser, $wgLinkCache, $wgUseBetterLinksUpdate;
		global $wgMwRedir;

		$wgLinkCache = new LinkCache();

		# Get old version of link table to allow incremental link updates
		if ( $wgUseBetterLinksUpdate ) {
			$wgLinkCache->preFill( $this->mTitle );
			$wgLinkCache->clear();
		}

		# Now update the link cache by parsing the text	
		$wgOut = new OutputPage();
		$wgOut->addWikiText( $text );

		$this->editUpdates( $text );
		if( $wgMwRedir->matchStart( $text ) )
			$r = "redirect=no";
		else
			$r = "";
		$wgOut->redirect( wfLocalUrl( $this->mTitle->getPrefixedURL(), $r ) );
	}

	# Add this page to my watchlist

	function watch( $add = true )
	{
		global $wgUser, $wgOut, $wgLang;
		global $wgDeferredUpdateList;

		if ( 0 == $wgUser->getID() ) {
			$wgOut->errorpage( "watchnologin", "watchnologintext" );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		if( $add )
			$wgUser->addWatch( $this->mTitle );
		else
			$wgUser->removeWatch( $this->mTitle );

		$wgOut->setPagetitle( wfMsg( $add ? "addedwatch" : "removedwatch" ) );
		$wgOut->setRobotpolicy( "noindex,follow" );

		$sk = $wgUser->getSkin() ;
		$link = $sk->makeKnownLink ( $this->mTitle->getPrefixedText() ) ;

		if($add)
			$text = wfMsg( "addedwatchtext", $link );
		else
			$text = wfMsg( "removedwatchtext", $link );
		$wgOut->addHTML( $text );

		$up = new UserUpdate();
		array_push( $wgDeferredUpdateList, $up );

		$wgOut->returnToMain( false );
	}

	function unwatch()
	{
		$this->watch( false );
	}

	# This shares a lot of issues (and code) with Recent Changes

	function history()
	{
		global $wgUser, $wgOut, $wgLang, $offset, $limit;

		# If page hasn't changed, client can cache this
		
		$wgOut->checkLastModified( $this->getTimestamp() );
		$fname = "Article::history";
		wfProfileIn( $fname );

		$wgOut->setPageTitle( $this->mTitle->getPRefixedText() );
		$wgOut->setSubtitle( wfMsg( "revhistory" ) );
		$wgOut->setArticleFlag( false );
		$wgOut->setRobotpolicy( "noindex,nofollow" );

		if( $this->mTitle->getArticleID() == 0 ) {
			$wgOut->addHTML( wfMsg( "nohistory" ) );
			wfProfileOut( $fname );
			return;
		}
		
		$offset = (int)$offset;
		$limit = (int)$limit;
		if( $limit == 0 ) $limit = 50;
		$namespace = $this->mTitle->getNamespace();
		$title = $this->mTitle->getText();
		$sql = "SELECT old_id,old_user," .
		  "old_comment,old_user_text,old_timestamp,old_minor_edit ".
		  "FROM old USE INDEX (name_title_timestamp) " .
		  "WHERE old_namespace={$namespace} AND " .
		  "old_title='" . wfStrencode( $this->mTitle->getDBkey() ) . "' " .
		  "ORDER BY inverse_timestamp LIMIT $offset, $limit";
		$res = wfQuery( $sql, DB_READ, "Article::history" );

		$revs = wfNumRows( $res );
		if( $this->mTitle->getArticleID() == 0 ) {
			$wgOut->addHTML( wfMsg( "nohistory" ) );
			wfProfileOut( $fname );
			return;
		}
		
		$sk = $wgUser->getSkin();
		$numbar = wfViewPrevNext(
			$offset, $limit,
			$this->mTitle->getPrefixedText(),
			"action=history" );
		$s = $numbar;
		$s .= $sk->beginHistoryList();

		if($offset == 0 )
		$s .= $sk->historyLine( $this->getTimestamp(), $this->getUser(),
		  $this->getUserText(), $namespace,
		  $title, 0, $this->getComment(),
		  ( $this->getMinorEdit() > 0 ) );

		$revs = wfNumRows( $res );
		while ( $line = wfFetchObject( $res ) ) {
			$s .= $sk->historyLine( $line->old_timestamp, $line->old_user,
			  $line->old_user_text, $namespace,
			  $title, $line->old_id,
			  $line->old_comment, ( $line->old_minor_edit > 0 ) );
		}
		$s .= $sk->endHistoryList();
		$s .= $numbar;
		$wgOut->addHTML( $s );
		wfProfileOut( $fname );
	}

	function protect( $limit = "sysop" )
	{
		global $wgUser, $wgOut;

		if ( ! $wgUser->isSysop() ) {
			$wgOut->sysopRequired();
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		$id = $this->mTitle->getArticleID();
		if ( 0 == $id ) {
			$wgOut->fatalEror( wfMsg( "badarticleerror" ) );
			return;
		}
        $sql = "UPDATE cur SET cur_touched='" . wfTimestampNow() . "'," .
			"cur_restrictions='{$limit}' WHERE cur_id={$id}";
		wfQuery( $sql, DB_WRITE, "Article::protect" );

		$log = new LogPage( wfMsg( "protectlogpage" ), wfMsg( "protectlogtext" ) );
		if ( $limit === "" ) {
	 		$log->addEntry( wfMsg( "unprotectedarticle", $this->mTitle->getPrefixedText() ), "" );		
		} else {
			$log->addEntry( wfMsg( "protectedarticle", $this->mTitle->getPrefixedText() ), "" );
		}
		$wgOut->redirect( wfLocalUrl( $this->mTitle->getPrefixedURL() ) );
	}

	function unprotect()
	{
		return $this->protect( "" );
	}

	function delete()
	{
		global $wgUser, $wgOut;
		global $wpConfirm, $wpReason, $image, $oldimage;

		# This code desperately needs to be totally rewritten
		
		if ( ( ! $wgUser->isSysop() ) ) {
			$wgOut->sysopRequired();
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		# Better double-check that it hasn't been deleted yet!
		$wgOut->setPagetitle( wfMsg( "confirmdelete" ) );
		if ( ( "" == trim( $this->mTitle->getText() ) )
		  or ( $this->mTitle->getArticleId() == 0 ) ) {
			$wgOut->fatalError( wfMsg( "cannotdelete" ) );
			return;
		}

		# determine whether this page has earlier revisions
		# and insert a warning if it does
		# we select the text because it might be useful below
		$sql="SELECT old_text FROM old WHERE old_namespace=0 and old_title='" . wfStrencode($this->mTitle->getPrefixedDBkey())."' ORDER BY inverse_timestamp LIMIT 1";
		$res=wfQuery($sql, DB_READ, $fname);
		if( ($old=wfFetchObject($res)) && !$wpConfirm ) {
			$skin=$wgUser->getSkin();
			$wgOut->addHTML("<B>".wfMsg("historywarning"));
			$wgOut->addHTML( $skin->historyLink() ."</B><P>");
		}

		$sql="SELECT cur_text FROM cur WHERE cur_namespace=0 and cur_title='" . wfStrencode($this->mTitle->getPrefixedDBkey())."'";
		$res=wfQuery($sql, DB_READ, $fname);
		if( ($s=wfFetchObject($res))) {

			# if this is a mini-text, we can paste part of it into the deletion reason

			#if this is empty, an earlier revision may contain "useful" text
			if($s->cur_text!="") {
				$text=$s->cur_text;
			} else {
				if($old) {
					$text=$old->old_text;
					$blanked=1;
				}
				
			}
			
			$length=strlen($text);				
			
			# this should not happen, since it is not possible to store an empty, new
			# page. Let's insert a standard text in case it does, though
			if($length==0 && !$wpReason) { $wpReason=wfmsg("exblank");}
			
			
			if($length < 500 && !$wpReason) {
									
				# comment field=255, let's grep the first 150 to have some user
				# space left
				$text=substr($text,0,150);
				# let's strip out newlines and HTML tags
				$text=preg_replace("/\"/","'",$text);
				$text=preg_replace("/\</","&lt;",$text);
				$text=preg_replace("/\>/","&gt;",$text);
				$text=preg_replace("/[\n\r]/","",$text);
				if(!$blanked) {
					$wpReason=wfMsg("excontent"). " '".$text;
				} else {
					$wpReason=wfMsg("exbeforeblank") . " '".$text;
				}
				if($length>150) { $wpReason .= "..."; } # we've only pasted part of the text
				$wpReason.="'"; 
			}
		}

		return $this->confirmDelete();
	}
	
	function confirmDelete( $par = "" )
	{
		global $wgOut;
		
		$sub = htmlspecialchars( $this->mTitle->getPrefixedText() );
		$wgOut->setSubtitle( wfMsg( "deletesub", $sub ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		$wgOut->addWikiText( wfMsg( "confirmdeletetext" ) );

		$t = $this->mTitle->getPrefixedURL();

		$formaction = wfEscapeHTML( wfLocalUrl( $t, "action=delete" . $par ) );
		$confirm = wfMsg( "confirm" );
		$check = wfMsg( "confirmcheck" );
		$delcom = wfMsg( "deletecomment" );

		$wgOut->addHTML( "
<form id=\"deleteconfirm\" method=\"post\" action=\"{$formaction}\">
<table border=0><tr><td align=right>
{$delcom}:</td><td align=left>
<input type=text size=60 name=\"wpReason\" value=\"{$wpReason}\">
</td></tr><tr><td>&nbsp;</td></tr>
<tr><td align=right>
<input type=checkbox name=\"wpConfirm\" value='1' id=\"wpConfirm\">
</td><td><label for=\"wpConfirm\">{$check}</label></td>
</tr><tr><td>&nbsp;</td><td>
<input type=submit name=\"wpConfirmB\" value=\"{$confirm}\">
</td></tr></table></form>\n" );

		$wgOut->returnToMain( false );
	}

	function doDelete()
	{
		global $wgOut, $wgUser, $wgLang;
		global $wpReason;
		$fname = "Article::doDelete";

		$this->doDeleteArticle( $this->mTitle );
		$deleted = $this->mTitle->getPrefixedText();

		$wgOut->setPagetitle( wfMsg( "actioncomplete" ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );

		$sk = $wgUser->getSkin();
		$loglink = $sk->makeKnownLink( $wgLang->getNsText(
		  Namespace::getWikipedia() ) .
		  ":" . wfMsg( "dellogpage" ), wfMsg( "deletionlog" ) );

		$text = str_replace( "$1" , $deleted, wfMsg( "deletedtext" ) );
		$text = str_replace( "$2", $loglink, $text );

		$wgOut->addHTML( "<p>" . $text );
		$wgOut->returnToMain( false );
	}

	function doDeleteArticle( $title )
	{
		global $wgUser, $wgOut, $wgLang, $wpReason, $wgDeferredUpdateList;

		$fname = "Article::doDeleteArticle";
		$ns = $title->getNamespace();
		$t = wfStrencode( $title->getDBkey() );
		$id = $title->getArticleID();

		if ( "" == $t ) {
			$wgOut->fatalError( wfMsg( "cannotdelete" ) );
			return;
		}

		$u = new SiteStatsUpdate( 0, 1, -$this->isCountable( $this->getContent( true ) ) );
		array_push( $wgDeferredUpdateList, $u );

		# Move article and history to the "archive" table
		$sql = "INSERT INTO archive (ar_namespace,ar_title,ar_text," .
		  "ar_comment,ar_user,ar_user_text,ar_timestamp,ar_minor_edit," .
		  "ar_flags) SELECT cur_namespace,cur_title,cur_text,cur_comment," .
		  "cur_user,cur_user_text,cur_timestamp,cur_minor_edit,0 FROM cur " .
		  "WHERE cur_namespace={$ns} AND cur_title='{$t}'";
		wfQuery( $sql, DB_WRITE, $fname );

		$sql = "INSERT INTO archive (ar_namespace,ar_title,ar_text," .
		  "ar_comment,ar_user,ar_user_text,ar_timestamp,ar_minor_edit," .
		  "ar_flags) SELECT old_namespace,old_title,old_text,old_comment," .
		  "old_user,old_user_text,old_timestamp,old_minor_edit,old_flags " .
		  "FROM old WHERE old_namespace={$ns} AND old_title='{$t}'";
		wfQuery( $sql, DB_WRITE, $fname );

		# Now that it's safely backed up, delete it

		$sql = "DELETE FROM cur WHERE cur_namespace={$ns} AND " .
		  "cur_title='{$t}'";
		wfQuery( $sql, DB_WRITE, $fname );

		$sql = "DELETE FROM old WHERE old_namespace={$ns} AND " .
		  "old_title='{$t}'";
		wfQuery( $sql, DB_WRITE, $fname );
		
		$sql = "DELETE FROM recentchanges WHERE rc_namespace={$ns} AND " .
		  "rc_title='{$t}'";
       		wfQuery( $sql, DB_WRITE, $fname );

		# Finally, clean up the link tables

		if ( 0 != $id ) {

			$t = wfStrencode( $title->getPrefixedDBkey() );

			if ( $wgEnablePersistentLC ) {
	                        // Purge related entries in links cache on delete,
				wfQuery("DELETE linkscc FROM linkscc,links ".
                                	"WHERE lcc_title=links.l_from AND l_to={$id}", DB_WRITE);
                        	wfQuery("DELETE FROM linkscc WHERE lcc_title='{$t}'", DB_WRITE);
			}

			$sql = "SELECT l_from FROM links WHERE l_to={$id}";
			$res = wfQuery( $sql, DB_READ, $fname );

			$sql = "INSERT INTO brokenlinks (bl_from,bl_to) VALUES ";
            		$now = wfTimestampNow();
			$sql2 = "UPDATE cur SET cur_touched='{$now}' WHERE cur_id IN (";
			$first = true;

			while ( $s = wfFetchObject( $res ) ) {
				$nt = Title::newFromDBkey( $s->l_from );
				$lid = $nt->getArticleID();

				if ( ! $first ) { $sql .= ","; $sql2 .= ","; }
				$first = false;
				$sql .= "({$lid},'{$t}')";
				$sql2 .= "{$lid}";
			}
			$sql2 .= ")";
			if ( ! $first ) {
				wfQuery( $sql, DB_WRITE, $fname );
				wfQuery( $sql2, DB_WRITE, $fname );
			}
			wfFreeResult( $res );

			$sql = "DELETE FROM links WHERE l_to={$id}";
			wfQuery( $sql, DB_WRITE, $fname );

			$sql = "DELETE FROM links WHERE l_from='{$t}'";
			wfQuery( $sql, DB_WRITE, $fname );

			$sql = "DELETE FROM imagelinks WHERE il_from='{$t}'";
			wfQuery( $sql, DB_WRITE, $fname );

			$sql = "DELETE FROM brokenlinks WHERE bl_from={$id}";
			wfQuery( $sql, DB_WRITE, $fname );
		}
		
		$log = new LogPage( wfMsg( "dellogpage" ), wfMsg( "dellogpagetext" ) );
		$art = $title->getPrefixedText();
		$wpReason = wfCleanQueryVar( $wpReason );
		$log->addEntry( str_replace( "$1", $art, wfMsg( "deletedarticle" ) ), $wpReason );

		# Clear the cached article id so the interface doesn't act like we exist
		$this->mTitle->resetArticleID( 0 );
		$this->mTitle->mArticleID = 0;
	}

	function rollback()
	{
		global $wgUser, $wgLang, $wgOut, $from;

		if ( ! $wgUser->isSysop() ) {
			$wgOut->sysopRequired();
			return;
		}

		# Replace all this user's current edits with the next one down
		$tt = wfStrencode( $this->mTitle->getDBKey() );
		$n = $this->mTitle->getNamespace();
		
		# Get the last editor
		$sql = "SELECT cur_id,cur_user,cur_user_text,cur_comment FROM cur WHERE cur_title='{$tt}' AND cur_namespace={$n}";
		$res = wfQuery( $sql, DB_READ );
		if( ($x = wfNumRows( $res )) != 1 ) {
			# Something wrong
			$wgOut->addHTML( wfMsg( "notanarticle" ) );
			return;
		}
		$s = wfFetchObject( $res );
		$ut = wfStrencode( $s->cur_user_text );
		$uid = $s->cur_user;
		$pid = $s->cur_id;
		
		$from = str_replace( '_', ' ', wfCleanQueryVar( $from ) );
		if( $from != $s->cur_user_text ) {
			$wgOut->setPageTitle(wfmsg("rollbackfailed"));
			$wgOut->addWikiText( wfMsg( "alreadyrolled",
				htmlspecialchars( $this->mTitle->getPrefixedText()),
				htmlspecialchars( $from ),
				htmlspecialchars( $s->cur_user_text ) ) );
			if($s->cur_comment != "") {
				$wgOut->addHTML(
					wfMsg("editcomment",
					htmlspecialchars( $s->cur_comment ) ) );
				}
			return;
		}
		
		# Get the last edit not by this guy
		$sql = "SELECT old_text,old_user,old_user_text
		FROM old USE INDEX (name_title_timestamp)
		WHERE old_namespace={$n} AND old_title='{$tt}'
		AND (old_user <> {$uid} OR old_user_text <> '{$ut}')
		ORDER BY inverse_timestamp LIMIT 1";
		$res = wfQuery( $sql, DB_READ );
		if( wfNumRows( $res ) != 1 ) {
			# Something wrong
			$wgOut->setPageTitle(wfMsg("rollbackfailed"));
			$wgOut->addHTML( wfMsg( "cantrollback" ) );
			return;
		}
		$s = wfFetchObject( $res );
	
		# Save it!
		$newcomment = str_replace( "$1", $s->old_user_text, wfMsg( "revertpage" ) );
		$wgOut->setPagetitle( wfMsg( "actioncomplete" ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		$wgOut->addHTML( "<h2>" . $newcomment . "</h2>\n<hr>\n" );
		$this->updateArticle( $s->old_text, $newcomment, 1, $this->mTitle->userIsWatching() );

		$wgOut->returnToMain( false );
	}
	

	# Do standard deferred updates after page view

	/* private */ function viewUpdates()
	{
		global $wgDeferredUpdateList;
		
		if ( 0 != $this->getID() ) {
			global $wgDisableCounters;
			if( !$wgDisableCounters ) {
				$u = new ViewCountUpdate( $this->getID() );
				array_push( $wgDeferredUpdateList, $u );
				$u = new SiteStatsUpdate( 1, 0, 0 );
				array_push( $wgDeferredUpdateList, $u );
			}
			$u = new UserTalkUpdate( 0, $this->mTitle->getNamespace(),
			  $this->mTitle->getDBkey() );
			array_push( $wgDeferredUpdateList, $u );
		}
	}

	# Do standard deferred updates after page edit.
	# Every 1000th edit, prune the recent changes table.

	/* private */ function editUpdates( $text )
	{
		global $wgDeferredUpdateList, $wgDBname, $wgMemc;

		wfSeedRandom();
		if ( 0 == mt_rand( 0, 999 ) ) {
			$cutoff = wfUnix2Timestamp( time() - ( 7 * 86400 ) );
			$sql = "DELETE FROM recentchanges WHERE rc_timestamp < '{$cutoff}'";
			wfQuery( $sql, DB_WRITE );
		}
		$id = $this->getID();
		$title = $this->mTitle->getPrefixedDBkey();
		$adj = $this->mCountAdjustment;

		if ( 0 != $id ) {
			$u = new LinksUpdate( $id, $title );
			array_push( $wgDeferredUpdateList, $u );
			$u = new SiteStatsUpdate( 0, 1, $adj );
			array_push( $wgDeferredUpdateList, $u );
			$u = new SearchUpdate( $id, $title, $text );
			array_push( $wgDeferredUpdateList, $u );

			$u = new UserTalkUpdate( 1, $this->mTitle->getNamespace(),
			  $this->mTitle->getDBkey() );
			array_push( $wgDeferredUpdateList, $u );

			if ( $this->getNamespace == NS_MEDIAWIKI ) {
				$messageCache = $wgMemc->get( "$wgDBname:messages" );
				if (!$messageCache) {
					$messageCache = wfLoadAllMessages();
				}
				$messageCache[$title] = $text;
				$wgMemc->set( "$wgDBname:messages" );
			}
		}
	}

	/* private */ function setOldSubtitle()
	{
		global $wgLang, $wgOut;

		$td = $wgLang->timeanddate( $this->mTimestamp, true );
		$r = str_replace( "$1", "{$td}", wfMsg( "revisionasof" ) );
		$wgOut->setSubtitle( "({$r})" );
	}

	function blockedIPpage()
	{
		global $wgOut, $wgUser, $wgLang;

		$wgOut->setPageTitle( wfMsg( "blockedtitle" ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		$wgOut->setArticleFlag( false );

		$id = $wgUser->blockedBy();
		$reason = $wgUser->blockedFor();

		$name = User::whoIs( $id );
		$link = "[[" . $wgLang->getNsText( Namespace::getUser() ) .
		  ":{$name}|{$name}]]";

		$text = str_replace( "$1", $link, wfMsg( "blockedtext" ) );
		$text = str_replace( "$2", $reason, $text );
		$text = str_replace( "$3", getenv( "REMOTE_ADDR" ), $text );
		$wgOut->addWikiText( $text );
		$wgOut->returnToMain( false );
	}

	# This function is called right before saving the wikitext,
	# so we can do things like signatures and links-in-context.

	function preSaveTransform( $text )
	{
		$s = "";
		while ( "" != $text ) {
			$p = preg_split( "/<\\s*nowiki\\s*>/i", $text, 2 );
			$s .= $this->pstPass2( $p[0] );

			if ( ( count( $p ) < 2 ) || ( "" == $p[1] ) ) { $text = ""; }
			else {
				$q = preg_split( "/<\\/\\s*nowiki\\s*>/i", $p[1], 2 );
				$s .= "<nowiki>{$q[0]}</nowiki>";
				$text = $q[1];
			}
		}
		return rtrim( $s );
	}

	/* private */ function pstPass2( $text )
	{
		global $wgUser, $wgLang, $wgLocaltimezone;

		# Signatures
		#
		$n = $wgUser->getName();
		$k = $wgUser->getOption( "nickname" );
		if ( "" == $k ) { $k = $n; }
		if(isset($wgLocaltimezone)) {
			$oldtz = getenv("TZ"); putenv("TZ=$wgLocaltimezone");
		}
		/* Note: this is an ugly timezone hack for the European wikis */
		$d = $wgLang->timeanddate( date( "YmdHis" ), false ) .
		  " (" . date( "T" ) . ")";
		if(isset($wgLocaltimezone)) putenv("TZ=$oldtz");

		$text = preg_replace( "/~~~~/", "[[" . $wgLang->getNsText(
		  Namespace::getUser() ) . ":$n|$k]] $d", $text );
		$text = preg_replace( "/~~~/", "[[" . $wgLang->getNsText(
		  Namespace::getUser() ) . ":$n|$k]]", $text );

		# Context links: [[|name]] and [[name (context)|]]
		#
		$tc = "[&;%\\-,.\\(\\)' _0-9A-Za-z\\/:\\x80-\\xff]";
		$np = "[&;%\\-,.' _0-9A-Za-z\\/:\\x80-\\xff]"; # No parens
		$namespacechar = '[ _0-9A-Za-z\x80-\xff]'; # Namespaces can use non-ascii!
		$conpat = "/^({$np}+) \\(({$tc}+)\\)$/";

		$p1 = "/\[\[({$np}+) \\(({$np}+)\\)\\|]]/";		# [[page (context)|]]
		$p2 = "/\[\[\\|({$tc}+)]]/";					# [[|page]]
		$p3 = "/\[\[($namespacechar+):({$np}+)\\|]]/";		# [[namespace:page|]]
		$p4 = "/\[\[($namespacechar+):({$np}+) \\(({$np}+)\\)\\|]]/";
														# [[ns:page (cont)|]]
		$context = "";
		$t = $this->mTitle->getText();
		if ( preg_match( $conpat, $t, $m ) ) {
			$context = $m[2];
		}
		$text = preg_replace( $p4, "[[\\1:\\2 (\\3)|\\2]]", $text );
		$text = preg_replace( $p1, "[[\\1 (\\2)|\\1]]", $text );
		$text = preg_replace( $p3, "[[\\1:\\2|\\2]]", $text );

		if ( "" == $context ) {
			$text = preg_replace( $p2, "[[\\1]]", $text );
		} else {
			$text = preg_replace( $p2, "[[\\1 ({$context})|\\1]]", $text );
		}
		
		# {{SUBST:xxx}} variables
		#
		$mw =& MagicWord::get( MAG_SUBST );
		$text = $mw->substituteCallback( $text, "wfReplaceSubstVar" );

		return $text;
	}

	/* Caching functions */
	
	function tryFileCache() {
		if($this->isFileCacheable()) {
			$touched = $this->mTouched;
			if( strpos( $this->mContent, "{{" ) !== false ) {
				# Expire pages with variable replacements in an hour
				$expire = wfUnix2Timestamp( time() - 3600 );
				$touched = max( $expire, $touched );
			}
			$cache = new CacheManager( $this->mTitle );
			if($cache->isFileCacheGood( $touched )) {
				global $wgOut;
				wfDebug( " tryFileCache() - about to load\n" );
				$cache->loadFromFileCache();
				$wgOut->reportTime(); # For profiling
				exit;
			} else {
				wfDebug( " tryFileCache() - starting buffer\n" );			
				if($cache->useGzip() && wfClientAcceptsGzip()) {
					/* For some reason, adding this header line over in
					   CacheManager::saveToFileCache() fails on my test
					   setup at home, though it works on the live install.
					   Make double-sure...  --brion */
					header( "Content-Encoding: gzip" );
				}
				ob_start( array(&$cache, 'saveToFileCache' ) );
			}
		} else {
			wfDebug( " tryFileCache() - not cacheable\n" );
		}
	}

	function isFileCacheable() {
		global $wgUser, $wgUseFileCache, $wgShowIPinHeader;
		global $action, $oldid, $diff, $redirect, $printable;
		return $wgUseFileCache
			and (!$wgShowIPinHeader)
			and ($this->getID() != 0)
			and ($wgUser->getId() == 0)
			and (!$wgUser->getNewtalk())
			and ($this->mTitle->getNamespace != Namespace::getSpecial())
			and ($action == "view")
			and (!isset($oldid))
			and (!isset($diff))
			and (!isset($redirect))
			and (!isset($printable))
			and (!$this->mRedirectedFrom);
	}
	
	function checkTouched() {
		$id = $this->getID();
		$sql = "SELECT cur_touched,cur_is_redirect FROM cur WHERE cur_id=$id";
		$res = wfQuery( $sql, DB_READ, "Article::checkTouched" );
		if( $s = wfFetchObject( $res ) ) {
			$this->mTouched = $s->cur_touched;
			return !$s->cur_is_redirect;
		} else {
			return false;
		}
	}
}

function wfReplaceSubstVar( $matches ) {
	return wfMsg( $matches[1] );
}

?>
