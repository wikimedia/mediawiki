<?php
# Class representing a Wikipedia article and history.
# See design.doc for an overview.

# Note: edit user interface and cache support functions have been
# moved to separate EditPage and CacheManager classes.

include_once( "CacheManager.php" );

class Article {
	/* private */ var $mContent, $mContentLoaded;
	/* private */ var $mUser, $mTimestamp, $mUserText;
	/* private */ var $mCounter, $mComment, $mCountAdjustment;
	/* private */ var $mMinorEdit, $mRedirectedFrom;
	/* private */ var $mTouched, $mFileCache, $mTitle;
	/* private */ var $mId, $mTable;
	
	function Article( &$title ) {
		$this->mTitle =& $title;
		$this->clear();
	}
	
	/* private */ function clear()
	{
		$this->mContentLoaded = false;
		$this->mCurID = $this->mUser = $this->mCounter = -1; # Not loaded
		$this->mRedirectedFrom = $this->mUserText =
		$this->mTimestamp = $this->mComment = $this->mFileCache = "";
		$this->mCountAdjustment = 0;
		$this->mTouched = "19700101000000";
	}

	/* static */ function getRevisionText( $row, $prefix = "old_" ) {
		# Deal with optional compression of archived pages.
		# This can be done periodically via maintenance/compressOld.php, and
		# as pages are saved if $wgCompressRevisions is set.
		$text = $prefix . "text";
		$flags = $prefix . "flags";
		if( isset( $row->$flags ) && (false !== strpos( $row->$flags, "gzip" ) ) ) {
			return gzinflate( $row->$text );
		}
		if( isset( $row->$text ) ) {
			return $row->$text;
		}
		return false;
	}
	
	/* static */ function compressRevisionText( &$text ) {
		global $wgCompressRevisions;
		if( !$wgCompressRevisions ) {
			return "";
		}
		if( !function_exists( "gzdeflate" ) ) {
			wfDebug( "Article::compressRevisionText() -- no zlib support, not compressing\n" );
			return "";
		}
		$text = gzdeflate( $text );
		return "gzip";
	}
	
	# Note that getContent/loadContent may follow redirects if
	# not told otherwise, and so may cause a change to mTitle.

	# Return the text of this revision
	function getContent( $noredir = false )
	{
		global $wgRequest;

		# Get variables from query string :P
		$action = $wgRequest->getText( 'action', 'view' );
		$section = $wgRequest->getText( 'section' );

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
	
	# Load the revision (including cur_text) into this object
	function loadContent( $noredir = false )
	{
		global $wgOut, $wgMwRedir, $wgRequest;
		
		# Query variables :P
		$oldid = $wgRequest->getVal( 'oldid' );
		$redirect = $wgRequest->getVal( 'redirect' );

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
					if( $rt ) {
						# Gotta hand redirects to special pages differently:
						# Fill the HTTP response "Location" header and ignore
						# the rest of the page we're on.
	
						if ( $rt->getInterwiki() != "" ) {
							$wgOut->redirect( $rt->getFullURL() ) ;
							return;
						}
						if ( $rt->getNamespace() == Namespace::getSpecial() ) {
							$wgOut->redirect( $rt->getFullURL() );
							return;
						}
						$rid = $rt->getArticleID();
						if ( 0 != $rid ) {
							$sql = "SELECT cur_text,cur_timestamp,cur_user," .
							  "cur_counter,cur_restrictions,cur_touched FROM cur WHERE cur_id={$rid}";
							$res = wfQuery( $sql, DB_READ, $fname );
	
							if ( 0 != wfNumRows( $res ) ) {
								$this->mRedirectedFrom = $this->mTitle->getPrefixedText();
								$this->mTitle = $rt;
								$s = wfFetchObject( $res );
							}
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
			$sql = "SELECT old_text,old_timestamp,old_user,old_flags FROM old " .
			  "WHERE old_id={$oldid}";
			$res = wfQuery( $sql, DB_READ, $fname );
			if ( 0 == wfNumRows( $res ) ) { return; }

			$s = wfFetchObject( $res );
			$this->mContent = Article::getRevisionText( $s );
			$this->mUser = $s->old_user;
			$this->mCounter = 0;
			$this->mTimestamp = $s->old_timestamp;
			wfFreeResult( $res );
		}
		$this->mContentLoaded = true;
		return $this->mContent;
	}

	# Gets the article text without using so many damn globals
	# Returns false on error
	function getContentWithoutUsingSoManyDamnGlobals( $oldid = 0, $noredir = false ) {
		global $wgMwRedir;

		if ( $this->mContentLoaded ) {
			return $this->mContent;
		}
		$this->mContent = false;
		
		$fname = "Article::loadContent";
		
		if ( ! $oldid ) {	# Retrieve current version
			$id = $this->getID();
			if ( 0 == $id ) {
				return false;
			}

			$sql = "SELECT " .
			  "cur_text,cur_timestamp,cur_user,cur_counter,cur_restrictions,cur_touched " .
			  "FROM cur WHERE cur_id={$id}";
			$res = wfQuery( $sql, DB_READ, $fname );
			if ( 0 == wfNumRows( $res ) ) { 
				return false; 
			}

			$s = wfFetchObject( $res );
			# If we got a redirect, follow it (unless we've been told
			# not to by either the function parameter or the query
			if ( !$noredir && $wgMwRedir->matchStart( $s->cur_text ) ) {
				if ( preg_match( "/\\[\\[([^\\]\\|]+)[\\]\\|]/",
				  $s->cur_text, $m ) ) {
					$rt = Title::newFromText( $m[1] );
					if( $rt &&  $rt->getInterwiki() == "" && $rt->getNamespace() != Namespace::getSpecial() ) {
						$rid = $rt->getArticleID();
						if ( 0 != $rid ) {
							$sql = "SELECT cur_text,cur_timestamp,cur_user," .
							  "cur_counter,cur_restrictions,cur_touched FROM cur WHERE cur_id={$rid}";
							$res = wfQuery( $sql, DB_READ, $fname );
	
							if ( 0 != wfNumRows( $res ) ) {
								$this->mRedirectedFrom = $this->mTitle->getPrefixedText();
								$this->mTitle = $rt;
								$s = wfFetchObject( $res );
							}
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
			$sql = "SELECT old_text,old_timestamp,old_user,old_flags FROM old " .
			  "WHERE old_id={$oldid}";
			$res = wfQuery( $sql, DB_READ, $fname );
			if ( 0 == wfNumRows( $res ) ) { 
				return false; 
			}

			$s = wfFetchObject( $res );
			$this->mContent = Article::getRevisionText( $s );
			$this->mUser = $s->old_user;
			$this->mCounter = 0;
			$this->mTimestamp = $s->old_timestamp;
			wfFreeResult( $res );
		}
		$this->mContentLoaded = true;
		return $this->mContent;
	}

	function getID() {
		if( $this->mTitle ) {
			return $this->mTitle->getArticleID();
		} else {
			return 0;
		}
	}

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

	# Loads everything from cur except cur_text
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
		global $wgUser, $wgOut, $wgLang, $wgRequest;
		global $wgLinkCache, $IP, $wgEnableParserCache;
		
		$fname = "Article::view";
		wfProfileIn( $fname );

		# Get variables from query string :P
		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );

		$wgOut->setArticleFlag( true );
		$wgOut->setRobotpolicy( "index,follow" );

		# If we got diff and oldid in the query, we want to see a
		# diff page instead of the article.

		if ( !is_null( $diff ) ) {
			$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
			$de = new DifferenceEngine( intval($oldid), intval($diff) );
			$de->showDiffPage();
			wfProfileOut( $fname );
			return;
		}

		if ( !is_null( $oldid ) and $this->checkTouched() ) {
			if( $wgOut->checkLastModified( $this->mTouched ) ){
				return;
			} else if ( $this->tryFileCache() ) {
				# tell wgOut that output is taken care of
				$wgOut->disable();
				$this->viewUpdates();
				return;
			}
		}

		$text = $this->getContent(); # May change mTitle
		$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
		$wgOut->setHTMLTitle( $this->mTitle->getPrefixedText() .
		  " - " . wfMsg( "wikititlesuffix" ) );

		# We're looking at an old revision

		if ( !empty( $oldid ) ) {
			$this->setOldSubtitle();
			$wgOut->setRobotpolicy( "noindex,follow" );
		}
		if ( "" != $this->mRedirectedFrom ) {
			$sk = $wgUser->getSkin();
			$redir = $sk->makeKnownLink( $this->mRedirectedFrom, "",
			  "redirect=no" );
			$s = wfMsg( "redirectedfrom", $redir );
			$wgOut->setSubtitle( $s );
		}

		$wgLinkCache->preFill( $this->mTitle );
		
		if( $wgEnableParserCache && intval($wgUser->getOption( "stubthreshold" )) == 0 ){
			$wgOut->addWikiText( $text, true, $this );
		} else {
			$wgOut->addWikiText( $text );
		}

		# Add link titles as META keywords
		$wgOut->addMetaTags() ;

		$this->viewUpdates();
		wfProfileOut( $fname );
	}

	# Theoretically we could defer these whole insert and update
	# functions for after display, but that's taking a big leap
	# of faith, and we want to be able to report database
	# errors at some point.

	/* private */ function insertNewArticle( $text, $summary, $isminor, $watchthis )
	{
		global $wgOut, $wgUser, $wgLinkCache, $wgMwRedir;
		global $wgUseSquid, $wgDeferredUpdateList, $wgInternalServer;
		
		$fname = "Article::insertNewArticle";

		$this->mCountAdjustment = $this->isCountable( $text );

		$ns = $this->mTitle->getNamespace();
		$ttl = $this->mTitle->getDBkey();
		$text = $this->preSaveTransform( $text );
		if ( $wgMwRedir->matchStart( $text ) ) { $redir = 1; }
		else { $redir = 0; }

		$now = wfTimestampNow();
		$won = wfInvertTimestamp( $now );
		wfSeedRandom();
		$rand = number_format( mt_rand() / mt_getrandmax(), 12, ".", "" );
		$isminor = ( $isminor && $wgUser->getID() ) ? 1 : 0;
		$sql = "INSERT INTO cur (cur_namespace,cur_title,cur_text," .
		  "cur_comment,cur_user,cur_timestamp,cur_minor_edit,cur_counter," .
		  "cur_restrictions,cur_user_text,cur_is_redirect," .
		  "cur_is_new,cur_random,cur_touched,inverse_timestamp) VALUES ({$ns},'" . wfStrencode( $ttl ) . "', '" .
		  wfStrencode( $text ) . "', '" .
		  wfStrencode( $summary ) . "', '" .
		  $wgUser->getID() . "', '{$now}', " .
		  $isminor . ", 0, '', '" .
		  wfStrencode( $wgUser->getName() ) . "', $redir, 1, $rand, '{$now}', '{$won}')";
		$res = wfQuery( $sql, DB_WRITE, $fname );

		$newid = wfInsertId();
		$this->mTitle->resetArticleID( $newid );

		Article::onArticleCreate( $this->mTitle );
		RecentChange::notifyNew( $now, $this->mTitle, $isminor, $wgUser, $summary );
		
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
		
		# standard deferred updates
		$this->editUpdates( $text );

		$this->showArticle( $text, wfMsg( "newarticle" ) );
	}


	/* Side effects: loads last edit */
	function getTextOfLastEditWithSectionReplacedOrAdded($section, $text, $summary = ""){
		$this->loadLastEdit();
		$oldtext = $this->getContent();
		if ($section != "") {
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
		return $text;
	}

	function updateArticle( $text, $summary, $minor, $watchthis, $forceBot = false )
	{
		global $wgOut, $wgUser, $wgLinkCache;
		global $wgDBtransactions, $wgMwRedir;
		global $wgUseSquid, $wgInternalServer;
		$fname = "Article::updateArticle";

		if ( $this->mMinorEdit ) { $me1 = 1; } else { $me1 = 0; }
		if ( $minor && $wgUser->getID() ) { $me2 = 1; } else { $me2 = 0; }		
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

			# This overwrites $oldtext if revision compression is on
			$flags = Article::compressRevisionText( $oldtext );
			
			$sql = "INSERT INTO old (old_namespace,old_title,old_text," .
			  "old_comment,old_user,old_user_text,old_timestamp," .
			  "old_minor_edit,inverse_timestamp,old_flags) VALUES (" .
			  $this->mTitle->getNamespace() . ", '" .
			  wfStrencode( $this->mTitle->getDBkey() ) . "', '" .
			  wfStrencode( $oldtext ) . "', '" .
			  wfStrencode( $this->getComment() ) . "', " .
			  $this->getUser() . ", '" .
			  wfStrencode( $this->getUserText() ) . "', '" .
			  $this->getTimestamp() . "', " . $me1 . ", '" .
			  wfInvertTimestamp( $this->getTimestamp() ) . "','$flags')";
			$res = wfQuery( $sql, DB_WRITE, $fname );
			$oldid = wfInsertID( $res );

			$bot = (int)($wgUser->isBot() || $forceBot);
			RecentChange::notifyEdit( $now, $this->mTitle, $me2, $wgUser, $summary, 
				$oldid, $this->getTimestamp(), $bot );
			Article::onArticleEdit( $this->mTitle );
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
		# standard deferred updates
		$this->editUpdates( $text );
		
		
		$urls = array();
		# Template namespace
		# Purge all articles linking here
		if ( $this->mTitle->getNamespace() == NS_TEMPLATE) {
			$titles = $this->mTitle->getLinksTo();
			Title::touchArray( $titles );
			if ( $wgUseSquid ) {
				foreach ( $titles as $title ) {
					$urls[] = $title->getInternalURL();
				}
			}
		}
	
		# Squid updates
		if ( $wgUseSquid ) {
			$urls = array_merge( $urls, $this->mTitle->getSquidURLs() );
			$u = new SquidUpdate( $urls );
			$u->doUpdate();
		}

		$this->showArticle( $text, wfMsg( "updated" ) );
		return true;
	}

	# After we've either updated or inserted the article, update
	# the link tables and redirect to the new page.

	function showArticle( $text, $subtitle )
	{
		global $wgOut, $wgUser, $wgLinkCache;
		global $wgMwRedir;

		$wgLinkCache = new LinkCache();

		# Get old version of link table to allow incremental link updates
		$wgLinkCache->preFill( $this->mTitle );
		$wgLinkCache->clear();

		# Now update the link cache by parsing the text	
		$wgOut = new OutputPage();
		$wgOut->addWikiText( $text );

		if( $wgMwRedir->matchStart( $text ) )
			$r = "redirect=no";
		else
			$r = "";
		$wgOut->redirect( $this->mTitle->getFullURL( $r ) );
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
		$wgOut->redirect( $this->mTitle->getFullURL() );
	}

	function unprotect()
	{
		return $this->protect( "" );
	}

	# UI entry point for page deletion 
	function delete()
	{
		global $wgUser, $wgOut, $wgMessageCache, $wgRequest;
		$fname = "Article::delete";
		$confirm = $wgRequest->getBool( 'wpConfirm' ) && $wgRequest->isPosted();
		$reason = $wgRequest->getText( 'wpReason' );
		
		# This code desperately needs to be totally rewritten
		
		# Check permissions
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

		if ( $confirm ) {
			$this->doDelete( $reason );
			return;
		}

		# determine whether this page has earlier revisions
		# and insert a warning if it does
		# we select the text because it might be useful below
		$ns = $this->mTitle->getNamespace();
		$title = $this->mTitle->getDBkey();
		$etitle = wfStrencode( $title );
		$sql = "SELECT old_text,old_flags FROM old WHERE old_namespace=$ns and old_title='$etitle' ORDER BY inverse_timestamp LIMIT 1";
		$res = wfQuery( $sql, DB_READ, $fname );
		if( ($old=wfFetchObject($res)) && !$confirm ) {
			$skin=$wgUser->getSkin();
			$wgOut->addHTML("<B>".wfMsg("historywarning"));
			$wgOut->addHTML( $skin->historyLink() ."</B><P>");
		}

		$sql="SELECT cur_text FROM cur WHERE cur_namespace=$ns and cur_title='$etitle'";
		$res=wfQuery($sql, DB_READ, $fname);
		if( ($s=wfFetchObject($res))) {

			# if this is a mini-text, we can paste part of it into the deletion reason

			#if this is empty, an earlier revision may contain "useful" text
			$blanked = false;
			if($s->cur_text!="") {
				$text=$s->cur_text;
			} else {
				if($old) {
					$text = Article::getRevisionText( $old );
					$blanked = true;
				}
				
			}
			
			$length=strlen($text);				
			
			# this should not happen, since it is not possible to store an empty, new
			# page. Let's insert a standard text in case it does, though
			if($length == 0 && $reason === "") { 
				$reason = wfMsg("exblank");
			}
			
			if($length < 500 && $reason === "") {
									
				# comment field=255, let's grep the first 150 to have some user
				# space left
				$text=substr($text,0,150);
				# let's strip out newlines and HTML tags
				$text=preg_replace("/\"/","'",$text);
				$text=preg_replace("/\</","&lt;",$text);
				$text=preg_replace("/\>/","&gt;",$text);
				$text=preg_replace("/[\n\r]/","",$text);
				if(!$blanked) {
					$reason=wfMsg("excontent"). " '".$text;
				} else {
					$reason=wfMsg("exbeforeblank") . " '".$text;
				}
				if($length>150) { $reason .= "..."; } # we've only pasted part of the text
				$reason.="'"; 
			}
		}

		return $this->confirmDelete( "", $reason );
	}
	
	# Output deletion confirmation dialog
	function confirmDelete( $par, $reason )
	{
		global $wgOut;

		wfDebug( "Article::confirmDelete\n" );
		
		$sub = htmlspecialchars( $this->mTitle->getPrefixedText() );
		$wgOut->setSubtitle( wfMsg( "deletesub", $sub ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		$wgOut->addWikiText( wfMsg( "confirmdeletetext" ) );

		$formaction = $this->mTitle->escapeLocalURL( "action=delete" . $par );
		
		$confirm = wfMsg( "confirm" );
		$check = wfMsg( "confirmcheck" );
		$delcom = wfMsg( "deletecomment" );

		$wgOut->addHTML( "
<form id=\"deleteconfirm\" method=\"post\" action=\"{$formaction}\">
<table border=0><tr><td align=right>
{$delcom}:</td><td align=left>
<input type=text size=60 name=\"wpReason\" value=\"" . htmlspecialchars( $reason ) . "\">
</td></tr><tr><td>&nbsp;</td></tr>
<tr><td align=right>
<input type=checkbox name=\"wpConfirm\" value='1' id=\"wpConfirm\">
</td><td><label for=\"wpConfirm\">{$check}</label></td>
</tr><tr><td>&nbsp;</td><td>
<input type=submit name=\"wpConfirmB\" value=\"{$confirm}\">
</td></tr></table></form>\n" );

		$wgOut->returnToMain( false );
	}

	# Perform a deletion and output success or failure messages
	function doDelete( $reason )
	{
		global $wgOut, $wgUser, $wgLang;
		$fname = "Article::doDelete";
		wfDebug( "$fname\n" );

		if ( $this->doDeleteArticle() ) {	
			$deleted = $this->mTitle->getPrefixedText();

			$wgOut->setPagetitle( wfMsg( "actioncomplete" ) );
			$wgOut->setRobotpolicy( "noindex,nofollow" );

			$sk = $wgUser->getSkin();
			$loglink = $sk->makeKnownLink( $wgLang->getNsText(
			  Namespace::getWikipedia() ) .
			  ":" . wfMsg( "dellogpage" ), wfMsg( "deletionlog" ) );

			$text = wfMsg( "deletedtext", $deleted, $loglink );

			$wgOut->addHTML( "<p>" . $text );
			$wgOut->returnToMain( false );
		} else {
			$wgOut->fatalError( wfMsg( "cannotdelete" ) );
		}
	}

	# Back-end article deletion
	# Deletes the article with database consistency, writes logs, purges caches
	# Returns success
	function doDeleteArticle( $reason )
	{
		global $wgUser, $wgLang;
		global  $wgUseSquid, $wgDeferredUpdateList, $wgInternalServer;

		$fname = "Article::doDeleteArticle";
		wfDebug( "$fname\n" );

		$ns = $this->mTitle->getNamespace();
		$t = wfStrencode( $this->mTitle->getDBkey() );
		$id = $this->mTitle->getArticleID();

		if ( "" == $t || $id == 0 ) {
			return false;
		}

		$u = new SiteStatsUpdate( 0, 1, -$this->isCountable( $this->getContent( true ) ) );
		array_push( $wgDeferredUpdateList, $u );
		
		$linksTo = $this->mTitle->getLinksTo();

		# Squid purging
		if ( $wgUseSquid ) {
			$urls = array(	
				$this->mTitle->getInternalURL(),
				$this->mTitle->getInternalURL( "history" ) 
			);
			foreach ( $linksTo as $linkTo ) {
				$urls[] = $linkTo->getInternalURL();
			}

			$u = new SquidUpdate( $urls );
			array_push( $wgDeferredUpdateList, $u );

		}

		# Client and file cache invalidation
		Title::touchArray( $linksTo );

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
		$t = wfStrencode( $this->mTitle->getPrefixedDBkey() );

		Article::onArticleDelete( $this->mTitle );
		
		$sql = "INSERT INTO brokenlinks (bl_from,bl_to) VALUES ";
		$first = true;

		foreach ( $linksTo as $titleObj ) {
			if ( ! $first ) { $sql .= ","; }
			$first = false;
			# Get article ID. Efficient because it was loaded into the cache by getLinksTo().
			$linkID = $titleObj->getArticleID(); 
			$sql .= "({$linkID},'{$t}')";
		}
		if ( ! $first ) {
			wfQuery( $sql, DB_WRITE, $fname );
		}

		$sql = "DELETE FROM links WHERE l_to={$id}";
		wfQuery( $sql, DB_WRITE, $fname );

		$sql = "DELETE FROM links WHERE l_from={$id}";
		wfQuery( $sql, DB_WRITE, $fname );

		$sql = "DELETE FROM imagelinks WHERE il_from={$id}";
		wfQuery( $sql, DB_WRITE, $fname );

		$sql = "DELETE FROM brokenlinks WHERE bl_from={$id}";
		wfQuery( $sql, DB_WRITE, $fname );
		
		$log = new LogPage( wfMsg( "dellogpage" ), wfMsg( "dellogpagetext" ) );
		$art = $this->mTitle->getPrefixedText();
		$log->addEntry( wfMsg( "deletedarticle", $art ), $reason );

		# Clear the cached article id so the interface doesn't act like we exist
		$this->mTitle->resetArticleID( 0 );
		$this->mTitle->mArticleID = 0;
		return true;
	}

	function rollback()
	{
		global $wgUser, $wgLang, $wgOut, $wgRequest;

		if ( ! $wgUser->isSysop() ) {
			$wgOut->sysopRequired();
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage( $this->getContent() );
			return;
		}
		
		# Enhanced rollback, marks edits rc_bot=1
		$bot = $wgRequest->getBool( 'bot' );
		
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
		
		$from = str_replace( '_', ' ', $wgRequest->getVal( "from" ) );
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
		$sql = "SELECT old_text,old_user,old_user_text,old_timestamp,old_flags
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
		
		if ( $bot ) {
			# Mark all reverted edits as bot
			$sql = "UPDATE recentchanges SET rc_bot=1 WHERE
				rc_cur_id=$pid AND rc_user=$uid AND rc_timestamp > '{$s->old_timestamp}'";
			wfQuery( $sql, DB_WRITE, $fname );
		}

		# Save it!
		$newcomment = wfMsg( "revertpage", $s->old_user_text, $from );
		$wgOut->setPagetitle( wfMsg( "actioncomplete" ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		$wgOut->addHTML( "<h2>" . $newcomment . "</h2>\n<hr>\n" );
		$this->updateArticle( Article::getRevisionText( $s ), $newcomment, 1, $this->mTitle->userIsWatching(), $bot );
		Article::onArticleEdit( $this->mTitle );
		$wgOut->returnToMain( false );
	}
	

	# Do standard deferred updates after page view

	/* private */ function viewUpdates()
	{
		global $wgDeferredUpdateList;
		if ( 0 != $this->getID() ) {
			global $wgDisableCounters;
			if( !$wgDisableCounters ) {
				Article::incViewCount( $this->getID() );
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
		global $wgMessageCache;

		wfSeedRandom();
		if ( 0 == mt_rand( 0, 999 ) ) {
			$cutoff = wfUnix2Timestamp( time() - ( 7 * 86400 ) );
			$sql = "DELETE FROM recentchanges WHERE rc_timestamp < '{$cutoff}'";
			wfQuery( $sql, DB_WRITE );
		}
		$id = $this->getID();
		$title = $this->mTitle->getPrefixedDBkey();
		$shortTitle = $this->mTitle->getDBkey();
		
		$adj = $this->mCountAdjustment;

		if ( 0 != $id ) {
			$u = new LinksUpdate( $id, $title );
			array_push( $wgDeferredUpdateList, $u );
			$u = new SiteStatsUpdate( 0, 1, $adj );
			array_push( $wgDeferredUpdateList, $u );
			$u = new SearchUpdate( $id, $title, $text );
			array_push( $wgDeferredUpdateList, $u );

			$u = new UserTalkUpdate( 1, $this->mTitle->getNamespace(), $shortTitle );
			array_push( $wgDeferredUpdateList, $u );

			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
				$wgMessageCache->replace( $shortTitle, $text );
			}
		}
	}

	/* private */ function setOldSubtitle()
	{
		global $wgLang, $wgOut;

		$td = $wgLang->timeanddate( $this->mTimestamp, true );
		$r = wfMsg( "revisionasof", $td );
		$wgOut->setSubtitle( "({$r})" );
	}

	# This function is called right before saving the wikitext,
	# so we can do things like signatures and links-in-context.

	function preSaveTransform( $text )
	{
		global $wgParser, $wgUser;
		return $wgParser->preSaveTransform( $text, $this->mTitle, $wgUser, ParserOptions::newFromUser( $wgUser ) );
	}
	
	/* Caching functions */

	# checkLastModified returns true if it has taken care of all
	# output to the client that is necessary for this request.
	# (that is, it has sent a cached version of the page)
	function tryFileCache() {
		static $called = false;
		if( $called ) {
			wfDebug( " tryFileCache() -- called twice!?\n" );
			return;
		}
		$called = true;
		if($this->isFileCacheable()) {
			$touched = $this->mTouched;
			if( $this->mTitle->getPrefixedDBkey() == wfMsg( "mainpage" ) ) {
				# Expire the main page quicker
				$expire = wfUnix2Timestamp( time() - 3600 );
				$touched = max( $expire, $touched );
			}
			$cache = new CacheManager( $this->mTitle );
			if($cache->isFileCacheGood( $touched )) {
				global $wgOut;
				wfDebug( " tryFileCache() - about to load\n" );
				$cache->loadFromFileCache();
				return true;
			} else {
				wfDebug( " tryFileCache() - starting buffer\n" );			
				ob_start( array(&$cache, 'saveToFileCache' ) );
			}
		} else {
			wfDebug( " tryFileCache() - not cacheable\n" );
		}
	}

	function isFileCacheable() {
		global $wgUser, $wgUseFileCache, $wgShowIPinHeader, $wgRequest;
		extract( $wgRequest->getValues( 'action', 'oldid', 'diff', 'redirect', 'printable' ) );
		
		return $wgUseFileCache
			and (!$wgShowIPinHeader)
			and ($this->getID() != 0)
			and ($wgUser->getId() == 0)
			and (!$wgUser->getNewtalk())
			and ($this->mTitle->getNamespace() != Namespace::getSpecial())
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
	
	/* static */ function incViewCount( $id )
	{
		$id = intval( $id );
		global $wgHitcounterUpdateFreq;

		if( $wgHitcounterUpdateFreq <= 1 ){ // 
			wfQuery("UPDATE cur SET cur_counter = cur_counter + 1 " .
				"WHERE cur_id = $id", DB_WRITE);
			return;
		}

		# Not important enough to warrant an error page in case of failure
		$oldignore = wfIgnoreSQLErrors( true ); 

		wfQuery("INSERT INTO hitcounter (hc_id) VALUES ({$id})", DB_WRITE);

		$checkfreq = intval( $wgHitcounterUpdateFreq/25 + 1 );
		if( (rand() % $checkfreq != 0) or (wfLastErrno() != 0) ){
			# Most of the time (or on SQL errors), skip row count check
			wfIgnoreSQLErrors( $oldignore );
			return;
		}

		$res = wfQuery("SELECT COUNT(*) as n FROM hitcounter", DB_WRITE);
		$row = wfFetchObject( $res );
		$rown = intval( $row->n );
		if( $rown >= $wgHitcounterUpdateFreq ){ 
			wfProfileIn( "Article::incViewCount-collect" );
			$old_user_abort = ignore_user_abort( true );

			wfQuery("LOCK TABLES hitcounter WRITE", DB_WRITE);
			wfQuery("CREATE TEMPORARY TABLE acchits TYPE=HEAP ".
				"SELECT hc_id,COUNT(*) AS hc_n FROM hitcounter ".
				"GROUP BY hc_id", DB_WRITE);
			wfQuery("DELETE FROM hitcounter", DB_WRITE);
			wfQuery("UNLOCK TABLES", DB_WRITE);
			wfQuery("UPDATE cur,acchits SET cur_counter=cur_counter + hc_n ".
				"WHERE cur_id = hc_id", DB_WRITE);
			wfQuery("DROP TABLE acchits", DB_WRITE);

			ignore_user_abort( $old_user_abort );
			wfProfileOut( "Article::incViewCount-collect" );
		}
		wfIgnoreSQLErrors( $oldignore );
	}

	# The onArticle*() functions are supposed to be a kind of hooks
	# which should be called whenever any of the specified actions 
	# are done. 
	#
	# This is a good place to put code to clear caches, for instance. 

	# This is called on page move and undelete, as well as edit	
	/* static */ function onArticleCreate($title_obj){
		global $wgEnablePersistentLC, $wgEnableParserCache, $wgUseSquid, $wgDeferredUpdateList;

		$titles = $title_obj->getBrokenLinksTo();
		
		# Purge squid 
		if ( $wgUseSquid ) {
			$urls = $title_obj->getSquidURLs();
			foreach ( $titles as $linkTitle ) {
				$urls[] = $linkTitle->getInternalURL();
			}
			$u = new SquidUpdate( $urls );
			array_push( $wgDeferredUpdateList, $u );
		}

		# Clear persistent link cache
		if ( $wgEnablePersistentLC ) {
			LinkCache::linksccClearBrokenLinksTo( $title_obj->getPrefixedDBkey() );
		}

		# Clear parser cache (not really used)
		if ( $wgEnableParserCache ) {
			OutputPage::parsercacheClearBrokenLinksTo( $title_obj->getPrefixedDBkey() );
		}
	}

	/* static */ function onArticleDelete($title_obj){
		global $wgEnablePersistentLC, $wgEnableParserCache;
		if ( $wgEnablePersistentLC ) {
			LinkCache::linksccClearLinksTo( $title_obj->getArticleID() );
		}
		if ( $wgEnableParserCache ) {
			OutputPage::parsercacheClearLinksTo( $title_obj->getArticleID() );
		}
	}

	/* static */ function onArticleEdit($title_obj){
		global $wgEnablePersistentLC, $wgEnableParserCache;
		if ( $wgEnablePersistentLC ) {
			LinkCache::linksccClearPage( $title_obj->getArticleID() );
		}
		if ( $wgEnableParserCache ) {
			OutputPage::parsercacheClearPage( $title_obj->getArticleID(), $title_obj->getNamespace() );
		}
	}
}

?>
