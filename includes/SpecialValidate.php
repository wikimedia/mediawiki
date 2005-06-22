<?php
# Copyright (C) 2004 Magnus Manske <magnus.manske@web.de>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

class Validation {
	var $topicList;
	var $voteCache;
	var $rev2date;
	var $date2ref;

	# Reads all revision information of the specified article
	function prepareRevisions( $id ) {
		global $wgDBprefix;
		$this->rev2date = array();
		$this->date2rev = array();
		$sql = "SELECT * FROM {$wgDBprefix}revision WHERE rev_page='{$id}'";
		$res = wfQuery( $sql, DB_READ );
		while( $x = wfFetchObject( $res ) ) {
			$this->rev2date[$x->rev_id] = $x;
			$this->date2rev[$x->rev_timestamp] = $x;
		}
	}

	# Returns a HTML link to the specified article revision
	function getVersionLink( &$article, $revision, $text = "" ) {
		$t = $article->getTitle();
		if( $text == "" ) $text = wfMsg("val_view_version");
		$ret = "<a href=\"" . $t->escapeLocalURL( "oldid={$revision}" ) . "\">" . $this->getParsedWiki($text) . "</a>";
		return $ret;
	}

	# Returns an array containing all topics you can vote on
	function getTopicList() {
		global $wgDBprefix;
		$ret = array();
		$sql = "SELECT * FROM {$wgDBprefix}validate WHERE val_page=0";
		$res = wfQuery( $sql, DB_READ );
		while( $x = wfFetchObject( $res ) ) {
			$ret[$x->val_type] = $x;
		}
		ksort( $ret );
		return $ret;
	}
	
	# Merges one dataset into another
	function mergeInto( &$source, &$dest ) {
		$ret = false;
		foreach( $source as $x => $y ) {
			$doit = false;
			if( !isset( $dest[$x] ) ) {
				$doit = true;
			} elseif( $dest[$x]->value == 0 ) {
				$doit = true;
			}
			if( $doit ) {
				$dest[$x] = $y;
				$ret = true;
			}
		}
		if( $ret ) {
			ksort ( $dest );
		}
		return $ret;
	}

	# Merges all votes prior to the given revision into it
	function mergeOldRevisions( &$article, $revision ) {
		$tmp = $this->voteCache;
		krsort( $tmp );
		$update = false;
		$ts = $this->getTimestamp( $revision );
		$data = $this->voteCache[$ts];
		foreach( $tmp as $x => $y ) {
			if( $x < $ts ) {
				if( $this->mergeInto( $y, $data ) ) {
					$update = true;
				}
			}
		}
		if( $update ) {
			$this->setRevision( $article, $revision, $data );
		}
	}
	
	# Clears all votes prior to the given revision
	function clearOldRevisions( &$article, $revision ) {
		$tmp = $this->voteCache;
		$ts = $this->getTimestamp( $revision );
		foreach( $tmp as $x => $y ) {
			if( $x < $ts ) {
				$this->deleteRevision ( $article, $this->getRevisionNumber( $x ) );
			}
		}
	}
	
	# Updates the votes for the given revision from the FORM data
	function updateRevision( &$article, $revision ) {
		global $wgUser, $wgRequest;
		
		if( isset( $this->voteCache[$this->getTimestamp( $revision )] ) ) {
			$data = $this->voteCache[$this->getTimestamp( $revision )];
		} else {
			$data = array();
		}
		$nv = $wgRequest->getArray( "re_v_{$revision}", array() );
		$nc = $wgRequest->getArray( "re_c_{$revision}", array() );
		
		foreach( $nv as $x => $y ) {
			$data[$x]->value = $y;
			$data[$x]->comment = $nc[$x];
		}
		krsort( $data );
		
		$this->setRevision( $article, $revision, $data );
	}
	
	# Sets a specific revision to both cache and database
	function setRevision( &$article, $revision, &$data ) {
		global $wgUser;
		$this->deleteRevision( $article, $revision );
		$this->voteCache[$this->getTimestamp( $revision )] = $data;
		foreach( $data as $x => $y ) {
			if( $y->value > 0 ) {
				$ip = $wgUser->isAnon() ? $wgUser->getName() : '';
				$dbw =& wfGetDB( DB_MASTER );
				$dbw->insert( 'validate',
					array(
						'val_user'     => $wgUser->getId(),
						'val_page'     => $article->getId(),
						'val_revision' => $revision,
						'val_type'     => $x,
						'val_value'    => $y->value,
						'val_comment'  => $y->comment,
						'val_ip'       => $ip ),
					'Validation::setRevision' );
			}
		}
	}
	
	# This function returns a MySQL statement to identify the current user
	function identifyMe( $user = "" ) {
		global $wgUser;
		if( $user == "" ) $user = $wgUser->GetID();
		if( User::isIP( $user ) ) {
			return "(val_user='0' AND val_ip='{$user}')";
		} else {
			return "(val_user='{$user}')";
		}
	}
	
	# Deletes a specific vote set in both cache and database
	function deleteRevision( &$article, $revision ) {
		global $wgUser, $wgDBprefix;
		$ts = $this->getTimestamp( $revision );
		if( !isset ( $this->voteCache[$ts] ) ) {
			return; # Nothing to do
		}
		$sql = "DELETE FROM {$wgDBprefix}validate WHERE" . $this->identifyMe() . " AND ";
		$sql .= " val_page='" . $article->getID() . "' AND val_revision='{$revision}'";
		$res = wfQuery( $sql, DB_WRITE );
		unset( $this->voteCache[$ts] );
	}
	
	# Reads the entire vote list for this user for the given article
	function getVoteList( $id, $user = "" ) {
		global $wgUser, $wgDBprefix;
		if( $user == "" ) {
			$user = $wgUser->GetID();
		}
		$r = array() ; # Revisions
		$sql = "SELECT * FROM {$wgDBprefix}validate WHERE val_page=" . $id . " AND " . $this->identifyMe( $user );
		$res = wfQuery( $sql, DB_READ );
		while( $x = wfFetchObject( $res ) ) {
			#$y = $x->val_revision;
			$y = $this->rev2date[$x->val_revision];
			$y = $y->rev_timestamp;
			if( !isset( $r[$y] ) ) {
				$r[$y] = array();
			}
			$r[$y][$x->val_type]->value = $x->val_value;
			$r[$y][$x->val_type]->comment = $x->val_comment;
		}		
		return $r;
	}
	
	# Reads the entire vote list for this user for all articles
	function getAllVoteLists( $user ) {
		global $wgDBprefix;
		$r = array() ; # Revisions
		$sql = "SELECT * FROM {$wgDBprefix}validate WHERE " . $this->identifyMe( $user );
		$res = wfQuery( $sql, DB_READ );
		while( $x = wfFetchObject( $res ) ) {
			$a = $x->val_page;
			$y = $x->val_revision;
			if( !isset( $r[$a] ) ) {
				$r[$a] = array();
			}
			if( !isset( $r[$a][$y] ) ) {
				$r[$a][$y] = array();
			}
			$r[$a][$y][$x->val_type] = $x;
		}		
		return $r;
	}
	
	# This functions adds a topic to the database
	function addTopic( $topic, $limit ) {
		global $wgDBprefix;
		$a = 1;
		while( isset( $this->topicList[$a] ) ) {
			$a++;
		}
		$sql = "INSERT INTO {$wgDBprefix}validate (val_user,val_page,val_revision,val_type,val_value,val_comment,val_ip) VALUES (";
		$sql .= "'0','0','0','{$a}','{$limit}','";
		$sql .= Database::strencode( $topic ) . "','')";
		$res = wfQuery( $sql, DB_WRITE );
		$x->val_user = $x->val_page = $x->val_revision = 0;
		$x->val_type = $a;
		$x->val_value = $limit;
		$x->val_comment = $topic;
		$x->val_ip = "";
		$this->topicList[$a] = $x;
		ksort( $this->topicList );
	}

	# This functions adds a topic to the database
	function deleteTopic( $id ) {
		global $wgDBprefix;
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->delete( 'validate',
			array( 'val_type' => $id ),
			'Validation::deleteTopic' );
		unset( $this->topicList[$id] );
	}
	
	# This function returns a link text to the page validation statistics
	function link2statistics( &$article ) {
		$nt = $article->getTitle();
		$url = $nt->escapeLocalURL( "action=validate&mode=list" );
		return wfMsg( 'val_rev_stats_link', $nt->getPrefixedText(), $url );
	}

	# This function returns a link text to the page validation statistics of a single revision
	function link2revisionstatistics( &$article, $revision ) {
		$nt = $article->getTitle();
		$url = $nt->escapeLocalURL( "action=validate&mode=details&revision={$revision}" );
		return "(<a href=\"{$url}\">" . $this->getParsedWiki( wfMsg('val_revision_stats_link') ) . '</a>)' ;
	}

	# This function returns a link text to the user rating statistics page
	function link2userratings( $user, $text ) {
		global $wgUser;
		if( $user == 0 ) {
			$user = $wgUser->GetName();
		}
		$nt = Title::newFromText( "Special:Validate" );
		$url = $nt->escapeLocalURL( "mode=userstats&user=" . urlencode( $user ) );
		return "<a href=\"{$url}\">{$text}</a>";
	}

	# Returns the timestamp of a revision based on the revision number
	function getTimestamp( $revision ) {
		$ts = $this->rev2date[$revision];
		$ts = $ts->rev_timestamp;
		return $ts;
	}

	# Returns the revision number of a revision based on the timestamp
	function getRevisionNumber( $ts ) {
		$revision = $this->date2rev[$ts];
		$revision = $revision->rev_id;
		return $revision;
	}


	# HTML generation functions from this point on
	
	# Returns the metadata string for a revision
	function getMetadata( $idx ) {
		$metadata = "";
		$x = $this->rev2date[$idx];
		$metadata .= wfTimestamp( TS_DB, $x->rev_timestamp );
		$metadata .= " by ";
		if( $x->rev_user == 0 ) {
			$metadata .= $x->rev_user_text;
		} else {
			$u = new User;
			$u->setId( $x->rev_user );
			$u->setName( $x->rev_user_text );
			$nt = $u->getUserPage();
			# FIXME: Why doesn't this use standard linking code?
			$url = "<a href='" . $nt->escapeLocalUrl() . "'>" . htmlspecialchars( $nt->getText() ) . "</a>";
			$metadata .= $url;
		}
		# FIXME: Why doesn't this use standard comment formatting?
		$metadata .= " : <small>\"" . $this->getParsedWiki( $x->rev_comment ) . "\"</small>";
		return $metadata;
	}
	
	# Generates a link to the topic description
	function linkTopic ( $s ) {
		# FIXME: Why doesn't this use standard linking code?
		$t = Title::newFromText ( wfMsg ( 'val_topic_desc_page' ) ) ;
		$r = "<a href=\"" ;
		$r .= $t->escapeLocalURL () ;
		$r .= "#" . urlencode ( $s ) ;
		$r .= "\">{$s}</a>" ;
		return $r ;
	}
		
	# Generates HTML from a wiki text, e.g., a wfMsg
	function getParsedWiki ( $text ) {
		global $wgOut , $wgTitle, $wgParser ;
		$parserOutput = $wgParser->parse( $text , $wgTitle, $wgOut->mParserOptions,false);
		return $parserOutput->getText() ;
	}

	# Generates a form for a single revision
	function getRevisionForm( &$article, $idx, &$data, $focus = false ) {
		# Fill data with blank values
		$ts = $idx;
		$revision = $this->getRevisionNumber( $ts );
		foreach( $this->topicList as $x => $y ) {
			if( !isset( $data[$x] ) ) {
				$data[$x]->value = 0;
				$data[$x]->comment = "";
			}
		}
		ksort( $data ) ;		
	
		# Generate form
		$ret = "<form method='post'>";
		$ret .= "<table cellspacing='0' cellpadding='2' class='";
		if( $focus ) $ret .= "revisionform_focus" ;
		else $ret .= "revisionform_default" ;
		$ret .= "'>\n";
		$head = "Revision #" . $revision;
		$link = " " . $this->getVersionLink( $article, $revision );
		$metadata = $this->getMetadata( $revision );
		$ret .= "<tr><th align='left' colspan='3'>" . $head . " ({$link}) {$metadata}</th></tr>\n";
		$line = 0;
		foreach( $data as $x => $y ) {
			$line = 1 - $line;
			$trclass = $line == 1 ? "revision_tr_first" : "revision_tr_default";
			$idx = "_{$revision}[{$x}]";
			$ret .= "<tr class='{$trclass}'>\n";
			$ret .= "<th nowrap>";
			$ret .= $this->linkTopic ( $this->topicList[$x]->val_comment ) ;
			$ret .= "</th>\n";
			
			$tlx = $this->topicList[$x];
			$vote = "";
			$max = $tlx->val_value;
			for( $a = 0 ; $a <= $max ; $a++ ) {
				if( $a == 0 ) {
					$vote .= wfMsg ( "val_noop" );
				}
				$vote .= "<input type='radio' name='re_v{$idx}' value='{$a}'";
				if( $a == $y->value ) {
					$vote .= " checked='checked'";
				}
				$vote .= " />";
				if( $max == 2 && $a == 1 ) {
					$vote .= wfMsg( "val_no" ) . " ";
				} elseif( $max == 2 && $a == 2 ) {
					$vote .= wfMsg( "val_yes" );
				} elseif( $a != 0 ) {
					$vote .= $a . " ";
				}
				if ( $a == 0 ) {
					$vote .= " &nbsp; ";
				}
			}			
			$ret .= "<td nowrap valign='center'>{$vote}</td>\n";
			
			$ret .= "<td width='100%' align='center'><input size='50' style='width:98%' maxlength='250' type='text' name='re_c{$idx}' value='{$y->comment}'/>";
			$ret .= "</td></tr>\n";
		}
		$checked = $focus ? " checked='checked'" : "";
		$ret .= "<tr><td colspan='3' valign='center'>\n";
		$ret .= "<input type='checkbox' name='re_merge_{$revision}' value='1'{$checked} />" . $this->getParsedWiki( wfMsg( 'val_merge_old' ) ) . " \n";
		$ret .= "<input type='checkbox' name='re_clear_{$revision}' value='1'{$checked} />" . $this->getParsedWiki( wfMsg( 'val_clear_old' ) ) . " \n";
		$ret .= "<input type='submit' name='re_submit[{$revision}]' value=\"" . wfMsgHtml( "ok" ) . "\" />\n";
		
		if( $focus ) {
			$ret .= "<br/>\n<small>" . $this->getParsedWiki ( wfMsg( "val_form_note" ) ) . "</small>";
		}
		$ret .= "</td></tr>\n";
		$ret .= "</table>\n</form>\n\n";
		return $ret;
	}
	

	# Generates the page from the validation tab
	function validatePageForm( &$article, $revision ) {
		global $wgOut, $wgRequest, $wgUser;
		
		$ret = "";
		$this->prepareRevisions( $article->getID() );
		$this->topicList = $this->getTopicList();
		$this->voteCache = $this->getVoteList( $article->getID() );
		
		# Check for POST data
		$re = $wgRequest->getArray( 're_submit' );
		if ( isset( $re ) ) {
			$id = array_keys( $re );
			$id = $id[0] ; # $id is now the revision number the user clicked "OK" for
			$clearOldRev = $wgRequest->getVal( "re_clear_{$id}", 0 );
			$mergeOldRev = $wgRequest->getVal( "re_merge_{$id}", 0 );
			$this->updateRevision( $article, $id );
			if( $mergeOldRev ) {
				$this->mergeOldRevisions( $article, $id );
			}
			if( $clearOldRev ) {
				$this->clearOldRevisions( $article, $id );
			}
			$ret .= "<p class='revision_saved'>" . $this->getParsedWiki( wfMsg( 'val_revision_changes_ok' ) ) . "</p>";
		}
		else $ret .= wfMsgHtml ( 'val_votepage_intro' ) ;
		
		# Make sure the requested revision exists
		$ts = $this->rev2date[$revision]->rev_timestamp;
		if( !isset( $this->voteCache[$ts] ) ) {
			$this->voteCache[$ts] = array();
		}
		
		# Sort revisions list, newest first
		krsort( $this->voteCache );
		
		# Output
		$title = $article->getTitle();
		$title = $title->getPrefixedText();
		$wgOut->setPageTitle( wfMsg( 'val_rev_for', $title ) );
		foreach( $this->voteCache as $x => $y ) {
			$ret .= $this->getRevisionForm( $article, $x, $y, $x == $ts );
			$ret .= "<br/>\n";
		}
		$ret .= $this->link2statistics( $article );
		$ret .= "<p>" . $this->link2userratings( $wgUser->getID(), wfMsg( 'val_show_my_ratings' ) ) . "</p>";
		return $ret ;	
	}
	
	# This function performs the "management" mode on Special:Validate
	function manageTopics() {
		global $wgRequest;
		$this->topicList = $this->getTopicList();
		
		$iamsure = $wgRequest->getVal( "iamsure", "0" ) == 1;
		
		if( $iamsure && $wgRequest->getVal( "m_add", "--" ) != "--" ) {
			$new_topic = $wgRequest->getVal( "m_topic" );
			$new_limit = $wgRequest->getVal( "m_limit" );
			if( $new_topic != "" && $new_limit > 1 ) {
				$this->addTopic( $new_topic, $new_limit );
			}
		}

		$da = $wgRequest->getArray( "m_del" );
		if( $iamsure && isset( $da ) && count( $da ) > 0 ) {
			$id = array_keys( $da );
			$id = array_shift( $id );
			$this->deleteTopic( $id );
		}
		
		# FIXME: Wikitext this
		$r = "<p>" . $this->getParsedWiki( wfMsg( 'val_warning' ) ) . "</p>\n";
		
		$r .= "<form method='post'>\n";
		$r .= "<table border='1' cellspacing='0' cellpadding='2'>\n";
		$r .= "<tr>" . wfMsg( 'val_list_header' ) . "</tr>\n";
		foreach( $this->topicList as $x => $y ) {
			$r .= "<tr>\n";
			$r .= "<th>" . $this->getParsedWiki( $y->val_type ) . "</th>\n";
			$r .= "<td>" . $this->linkTopic ( $y->val_comment ) . "</td>\n";
			$r .= "<td>1 .. <b>" . intval( $y->val_value ) . "</b></td>\n";
			$r .= "<td><input type='submit' name='m_del[" . intval( $x ) . "]' value='" . htmlspecialchars( wfMsg( 'val_del' ) ) . "'/></td>\n";
			$r .= "</tr>\n";
		}
		$r .= "<tr>\n";
		$r .= "<td/>\n";
		$r .= "<td><input type='text' name='m_topic' value=''/></td>\n";
		$r .= "<td><input type='text' name='m_limit' value=''/></td>\n";
		$r .= "<td><input type='submit' name='m_add' value='" . htmlspecialchars( wfMsg( 'val_add' ) ) . "'/></td>\n";
		$r .= "</tr>\n";
		$r .= "</table>\n";
		$r .= "<input type='checkbox' name='iamsure' value='1'/>" . $this->getParsedWiki( wfMsg( 'val_iamsure' ) ) . "\n";
		$r .= "</form>\n";
		return $r;
	}
	
	# Generates a user ID for both logged-in users and anons; $x is an object from an SQL query
	function make_user_id( &$x ) {
		if( $x->val_user == 0 ) {
			return $x->val_ip;
		} else {
			return $x->val_user;
		}
	}

	function showDetails( &$article, $revision ) {
		global $wgDBprefix, $wgOut, $wgUser;
		$this->prepareRevisions( $article->getID() );
		$this->topicList = $this->getTopicList();

		$title = $article->getTitle();
		$wgOut->setPageTitle( str_replace( '$1', $title->getPrefixedText(), wfMsg( 'val_validation_of' ) ) );
		
		# Collecting statistic data
		$id = $article->getID();
		$sql = "SELECT * FROM {$wgDBprefix}validate WHERE val_page='{$id}' AND val_revision='{$revision}'";
		$res = wfQuery( $sql, DB_READ );
		$data = array();
		$users = array();
		$topics = array();
		while( $x = wfFetchObject( $res ) ) {
			$data[$this->make_user_id($x)][$x->val_type] = $x;
			$users[$this->make_user_id($x)] = true;
			$topics[$x->val_type] = true;
		}
		
		# Sorting lists of topics and users
		ksort( $users );
		ksort( $topics );
		
		$ts = $this->getTimestamp( $revision );
		$url = $this->getVersionLink( $article, $revision, wfTimestamp( TS_DB, $ts ) );

		# Table headers
		$ret = "" ;			
		$ret .= "<p><b>" . str_replace( '$1', $url, wfMsg( 'val_revision_of' ) ) . "</b></p>\n";
		$ret .= "<table border='1' cellspacing='0' cellpadding='2'>\n";
		$ret .= "<tr><th>" . $this->getParsedWiki ( wfMsg('val_details_th') ) . "</th>" ;
		
		foreach( $topics as $t => $dummy ) {
			$ret .= "<th>" . $this->linkTopic ( $this->topicList[$t]->val_comment ) . "</th>";
		}
		$ret .= "</tr>\n";

		# Table data
		foreach( $users as $u => $dummy ) { # Every row a user
			$ret .= "<tr>";
			$ret .= "<th align='left'>";
			if( !User::IsIP( $u ) ) { # Logged-in user rating
				$ret .= $this->link2userratings( $u, User::whoIs( $u ) );
			} else { # Anon rating
				$ret .= $this->link2userratings( $u, $u );
			}
			$ret .= "</th>";
			foreach( $topics as $t => $dummy ) { # Every column a topic
				if( !isset( $data[$u][$t] ) ) {
					$ret .= "<td/>";
				} else {
					$ret .= "<td valign='center'>";
					$ret .= $data[$u][$t]->val_value;
					if( $data[$u][$t]->val_comment != "" ) {
						$ret .= " <small>(" . $this->getParsedWiki( $data[$u][$t]->val_comment ) . ")</small>";
					}
					$ret .= "</td>";
				}
			}
			$ret .= "</tr>";
		}
		$ret .= "</table>";
		$ret .= "<p>" . $this->link2statistics( $article ) . "</p>";
		$ret .= "<p>" . $this->link2userratings( $wgUser->GetID(), wfMsg( 'val_show_my_ratings' ) ) . "</p>";
		
		return $ret;
	}
	
	function showList( &$article ) {
		global $wgDBprefix, $wgOut, $wgUser;
		$this->prepareRevisions( $article->getID() );
		$this->topicList = $this->getTopicList();

		$title = $article->getTitle();
		$wgOut->setPageTitle( str_replace( '$1', $title->getPrefixedText(), wfMsg( 'val_validation_of' ) ) );
		
		# Collecting statistic data
		$id = $article->getID();
		$sql = "SELECT * FROM {$wgDBprefix}validate WHERE val_page='{$id}'";
		$res = wfQuery( $sql, DB_READ );
		$data = array();
		while( $x = wfFetchObject( $res ) ) {
			$idx = $this->getTimestamp( $x->val_revision );
			if( !isset( $data[$idx] ) ) {
				$data[$idx] = array();
			}
			if( !isset( $data[$idx][$x->val_type] ) ) {
				$data[$idx][$x->val_type]->count = 0;
				$data[$idx][$x->val_type]->sum = 0;
			}
			$data[$idx][$x->val_type]->count++;
			$data[$idx][$x->val_type]->sum += $x->val_value;
		}
		
		krsort( $data );
		
		$ret = "";
		$ret .= "<table border='1' cellspacing='0' cellpadding='2'>\n";
		$ret .= "<tr><th>" . $this->getParsedWiki( wfMsg( "val_revision" ) ) . "</th>";
		foreach( $this->topicList as $x => $y ) {
			$ret .= "<th>" . $this->linkTopic ( $y->val_comment ) . "</th>";
		}
		$ret .= "</tr>\n";
		foreach( $data as $ts => $y ) {
			$revision = $this->getRevisionNumber( $ts );
			$url = $this->getVersionLink( $article, $revision, wfTimestamp( TS_DB, $ts ) );
			$detailsurl = $this->link2revisionstatistics( $article, $revision );
			$ret .= "<tr><td>{$url} {$detailsurl}</td>";
			foreach( $this->topicList as $topicID => $dummy ) {
				if( isset( $y[$topicID] ) ) {
					$z = $y[$topicID];
					if( $z->count == 0 ) {
						$a = 0;
					} else {
						$a = $z->sum / $z->count;
					}
					$ret .= sprintf( "<td><b>%1.1f</b> (%d)</td>", $a, $z->count );
				} else {
					$ret .= "<td></td>";
				}
			}
			$ret .= "</tr>\n";
		}
		$ret .= "</table>\n";
		$ret .= "<p>" . $this->link2userratings( $wgUser->getID(), wfMsg( 'val_show_my_ratings' ) ) . "</p>";
		return $ret;
	}
	
	function getRatingText( $value, $max ) {
		if( $max == 2 && $value == 1 ) {
			$ret = wfMsg ( "val_no" ) . " ";
		} elseif( $max == 2 && $value == 2 ) {
			$ret = wfMsg( "val_yes" );
		} elseif( $value != 0 ) {
			$ret = wfMsg( "val_of", $value, $max ) . " ";
		} else {
			$ret = "";
		}
		return $ret;
	}

	function showUserStats( $user ) {
		global $wgDBprefix, $wgOut, $wgUser;
		$this->topicList = $this->getTopicList();
		$data = $this->getAllVoteLists( $user );
		
		if( $user == $wgUser->getID() ) {
			$wgOut->setPageTitle ( wfMsg ( 'val_my_stats_title' ) );
		} elseif( !User::IsIP( $user ) ) {
			$wgOut->setPageTitle( wfMsg( 'val_user_stats_title', User::whoIs( $user ) ) );
		} else {
			$wgOut->setPageTitle( wfMsg( 'val_user_stats_title', $user ) );
		}
		
		$ret = "<table border='1' cellspacing='0' cellpadding='2'>\n";
		
		foreach( $data as $articleid => $revisions ) {
			$title = Title::newFromID( $articleid );
			$ret .= "<tr><th align='left' colspan='4'><a href=\"" . $title->escapeLocalURL() . "\">" . $title->getEscapedText() . "</a></th></tr>";
			krsort( $revisions );
			foreach( $revisions as $revid => $revision ) {
				$url = $title->getLocalURL( "oldid={$revid}" );
				$ret .= "<tr><th align='left'><a href=\"{$url}\">" . wfMsg( 'val_revision_number', $revid ) . "</a></th>";
				ksort( $revision );
				$initial = true;
				foreach( $revision as $topic => $rating ) {
					if( !$initial ) {
						$ret .= "<tr><td/>";
					}
					$initial = false;
					$ret .= "<td>" . $this->linkTopic ( $this->topicList[$topic]->val_comment ) . "</td>";
					$ret .= "<td>" . $this->getRatingText( $rating->val_value, $this->topicList[$topic]->val_value ) . "</td>";
					$ret .= "<td>" . $this->getParsedWiki( $rating->val_comment ) . "</td>";
					$ret .= "</tr>";
				}
			}
			$ret .= "</tr>";
		}
		$ret .= "</table>";
		
		return $ret;
	}

}

/**
 * constructor
 */
function wfSpecialValidate( $page = '' ) {
	global $wgOut, $wgRequest, $wgUseValidation, $wgUser, $wgContLang;
	
	if( !$wgUseValidation ) {
		$wgOut->errorpage( "nosuchspecialpage", "nospecialpagetext" );
		return;
	}

/*
	# Can do?
	if( ! $wgUser->isAllowed('change_validation') ) {
		$wgOut->sysopRequired();
		return;
	}
*/	

	$mode = $wgRequest->getVal( "mode" );
	$skin = $wgUser->getSkin();

	
	if( $mode == "manage" ) {
		$v = new Validation();
		$html = $v->manageTopics();
	} elseif( $mode == "userstats" ) {
		$v = new Validation();
		$user = $wgRequest->getVal( "user" );
		$html = $v->showUserStats( $user );
	} else {
		$html = "$mode";
		$html .= "<ul>\n";

		$t = Title::newFromText( "Special:Validate" );
		$url = $t->escapeLocalURL( "mode=manage" );
		$html .= "<li><a href=\"" . $url . "\">Manage</a></li>\n";

		$html .= "</ul>\n";
	}

	$wgOut->addHTML( $html );
}

?>
