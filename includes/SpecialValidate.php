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

class Validation {
	var $topicList;
	var $voteCache;
	var $page_id;

	function getRevisionFromId( $rev_id ) {
		if( isset( $this->id2rev[$rev_id] ) ) return $this->id2rev[$rev_id];

		$db =& wfGetDB( DB_SLAVE );
		$fname = 'SpecialValidate::getRevisionFromId';
		$res = $db->select( 'revision', '*', array( 'rev_id' => $rev_id ), $fname, array( 'LIMIT' => 1 ) );
		$rev = $db->fetchObject($res);
		$db->freeResult($res);

		$this->id2rev[$rev->rev_id] = $rev;
		$this->ts2rev[$rev->rev_timestamp] = $rev;

		return $rev;
	}

	function getRevisionFromTimestamp( $timestamp ) {
		if( isset( $this->ts2rev[$timestamp] ) ) return $this->ts2rev[$timestamp];

		$db =& wfGetDB( DB_SLAVE );
		$fname = 'SpecialValidate::getRevisionFromTimestamp';
		$res = $db->select( 'revision', '*',
			array( 'rev_page' => $this->page_id, 'rev_timestamp' => $timestamp ),
			$fname, array( 'LIMIT' => 1 )
		);
		$rev = $db->fetchObject($res);
		$db->freeResult($res);

		$this->id2rev[$rev->rev_id] = $rev;
		$this->ts2rev[$rev->rev_timestamp] = $rev;

		return $rev;
	}

	# Returns a HTML link to the specified article revision
	function getRevisionLink( &$article, $revision, $text = "" ) {
		global $wgUser;
		$sk = $wgUser->getSkin();
		$t = $article->getTitle();
		if( $text == "" ) $text = wfMsg("val_view_version");
		return $sk->makeKnownLinkObj( $t, $this->getParsedWiki($text), 'oldid='.urlencode($revision) );
	}

	# Returns an array containing all topics you can vote on
	function getTopicList() {
		$db =& wfGetDB( DB_SLAVE );

		$topics = array();
		$res = $db->select( 'validate', '*', array( 'val_page' => 0 ), 'SpecialValidate::getTopicList' );
		while( $topic = $db->fetchObject($res) ) {
			$topics[$topic->val_type] = $topic;
		}
		$db->freeResult($res);

		ksort( $topics );
		return $topics;
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
		$ts = $this->getRevisionTimestamp( $revision );
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
		$ts = $this->getRevisionTimestamp( $revision );
		foreach( $tmp as $x => $y ) {
			if( $x < $ts ) {
				$this->deleteRevisionVote ( $article, $this->getRevisionId( $x ) );
			}
		}
	}
	
	# Updates the votes for the given revision from the FORM data
	function updateRevision( &$article, $revision ) {
		global $wgRequest;
		
		if( isset( $this->voteCache[$this->getRevisionTimestamp( $revision )] ) ) {
			$data = $this->voteCache[$this->getRevisionTimestamp( $revision )];
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
		$this->deleteRevisionVote( $article, $revision );
		$this->voteCache[ $this->getRevisionTimestamp($revision) ] = $data;
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
					'SpecialValidate::setRevision'
				);
			}
		}
	}
	
	# Returns a map identifying the current user
	function identifyUser( $user = "" ) {
		global $wgUser;
		if( $user == "" ) $user = $wgUser->getID();
		return User::isIP($user)
			? array( 'val_user' => 0, 'val_ip' => $user )
			: array( 'val_user' => $user );
	}
	
	# Deletes a specific vote set in both cache and database
	function deleteRevisionVote( &$article, $revision ) {
		$ts = $this->getRevisionTimestamp( $revision );
		if( !isset ( $this->voteCache[$ts] ) ) return;

		$db =& wfGetDB( DB_MASTER );
		$db->delete(
			'validate',
			array_merge(
				$this->identifyUser(),
				array(
					'val_page' => $article->getID(),
					'val_revision' => $revision
				)
			),
			'SpecialValidate::deleteRevisionVote'
		);

		unset( $this->voteCache[$ts] );
	}
	
	# Reads the entire vote list for this user for the given article
	function getVoteList( $id, $user = "" ) {
		$db =& wfGetDB( DB_SLAVE );
		$res = $db->select( 'validate', '*', array_merge( array( 'val_page' => $id ), $this->identifyUser($user) ) );

		$revisions = array();
		while( $vote = $db->fetchObject($res) ) {
			$ts = $this->getRevisionTimestamp( $vote->val_revision );
			if( ! isset( $revisions[$ts] ) ) {
				$revisions[$ts] = array();
			}
			$revisions[$ts][$vote->val_type]->value = $vote->val_value;
			$revisions[$ts][$vote->val_type]->comment = $vote->val_comment;
		}
		$db->freeResult($res);

		return $revisions;
	}
	
	# Reads the entire vote list for this user for all articles
	# XXX Should be paged
	function getAllVoteLists( $user ) {
		$db =& wfGetDB( DB_SLAVE );
		$res = $db->select( 'validate', '*', $this->identifyUser($user) );

		$votes = array();
		while( $vote = $db->fetchObject($res) ) {
			$votes[$vote->val_page][$vote->val_revision][$vote->val_type] = $vote;
		}
		$db->freeResult($res);

		return $votes;
	}
	
	# This functions adds a topic to the database
	function addTopic( $topic, $limit ) {
		$db =& wfGetDB( DB_MASTER );

		$next_idx = 1;
		while( isset( $this->topicList[$next_idx] ) ) {
		       $next_idx++;
		}

		$db->insert(
			'validate',
			array(
				'val_user' => 0,
				'val_page' => 0,
				'val_revision' => 0,
				'val_type' => $next_idx,
				'val_value' => $limit,
				'val_comment' => $topic,
				'val_ip' => ''
			),
			'SpecialValidate::addTopic'
		);

		$t->val_user = $t->val_page = $t->val_revision = 0;
		$t->val_type = $next_idx;
		$t->val_value = $limit;
		$t->val_comment = $topic;
		$t->val_ip = "";
		$this->topicList[$next_idx] = $t;

		ksort( $this->topicList );
	}

	function deleteTopic( $id ) {
		$db =& wfGetDB( DB_MASTER );
		$db->delete( 'validate', array( 'val_type' => $id ), 'SpecialValidate::deleteTopic' );
		unset( $this->topicList[$id] );
	}
	
	# This function returns a link text to the page validation statistics
	function getStatisticsLink( &$article ) {
		global $wgUser;
		$sk = $wgUser->getSkin();
		$nt = $article->getTitle();
		return $sk->makeKnownLinkObj( $nt, wfMsg( 'val_rev_stats', $nt->getPrefixedText() ), 'action=validate&mode=list' );
	}

	# This function returns a link text to the page validation statistics of a single revision
	function getRevisionStatsLink( &$article, $revision ) {
		global $wgUser;
		$sk = $wgUser->getSkin();
		$nt = $article->getTitle();
		$text = $this->getParsedWiki( wfMsg('val_revision_stats_link') );
		$query = "action=validate&mode=details&revision={$revision}";
		return '(' . $sk->makeKnownLinkObj( $nt, $text, $query ) . ')';
	}

	# This function returns a link text to the user rating statistics page
	function getUserRatingsLink( $user, $text ) {
		global $wgUser;
		$sk = $wgUser->getSkin();
		if( $user == 0 ) $user = $wgUser->getName();
		$nt = Title::newFromText( 'Special:Validate' );
		return $sk->makeKnownLinkObj( $nt, $text, 'mode=userstats&user='.urlencode($user) );
	}

	# Returns the timestamp of a revision based on the revision number
	function getRevisionTimestamp( $rev_id ) {
		$rev = $this->getRevisionFromId( $rev_id );
		return $rev->rev_timestamp;
	}

	# Returns the revision number of a revision based on the timestamp
	function getRevisionId( $ts ) {
		$rev = $this->getRevisionFromTimestamp( $ts );
		return $rev->rev_id;
	}


	# HTML generation functions from this point on
	
	# Returns the metadata string for a revision
	function getMetadata( $rev_id, &$article ) {
		global $wgUser;
		$sk = $wgUser->getSkin();

		$metadata = "";
		$x = $this->getRevisionFromId($rev_id);
		$metadata .= wfTimestamp( TS_DB, $x->rev_timestamp );
		$metadata .= " by ";
		if( $x->rev_user == 0 ) {
			$metadata .= $x->rev_user_text;
		} else {
			$u = new User;
			$u->setId( $x->rev_user );
			$u->setName( $x->rev_user_text );
			$nt = $u->getUserPage();
			$metadata .= $sk->makeKnownLinkObj( $nt, htmlspecialchars( $nt->getText() ) );
		}
		$metadata .= ': '. $sk->commentBlock( $x->rev_comment, $article->getTitle() );
		return $metadata;
	}
	
	# Generates a link to the topic description
	function getTopicLink($s) {
		$t = Title::newFromText ( wfMsg ( 'val_topic_desc_page' ) ) ;
		# FIXME: Why doesn't this use standard linking code?
		$r = "<a href=\"" ;
		$r .= $t->escapeLocalURL () ;
		$r .= "#" . urlencode ( $s ) ;
		$r .= "\">{$s}</a>" ;
		return $r ;
	}
		
	# Generates HTML from a wiki text, e.g., a wfMsg
	function getParsedWiki ( $text ) {
		global $wgOut, $wgTitle, $wgParser ;
		$parserOutput = $wgParser->parse( $text , $wgTitle, $wgOut->mParserOptions,false);
		return $parserOutput->getText() ;
	}

	# Generates a form for a single revision
	function getRevisionForm( &$article, $idx, &$data, $focus = false ) {
		# Fill data with blank values
		$ts = $idx;
		$revision = $this->getRevisionId( $ts );
		foreach( $this->topicList as $x => $y ) {
			if( !isset( $data[$x] ) ) {
				$data[$x]->value = 0;
				$data[$x]->comment = "";
			}
		}
		ksort( $data ) ;		
	
		# Generate form
		$table_class = $focus ? 'revisionform_focus' : 'revisionform_default';
		$ret = "<form method='post'><table class='{$table_class}'>\n";
		$head = "Revision #" . $revision;
		$link = $this->getRevisionLink( $article, $revision );
		$metadata = $this->getMetadata( $revision, $article );
		$ret .= "<tr><th colspan='3'>" . $head . " ({$link}) {$metadata}</th></tr>\n";
		$line = 0;
		foreach( $data as $x => $y ) {
			$line = 1 - $line;
			$trclass = $line == 1 ? "revision_tr_first" : "revision_tr_default";
			$idx = "_{$revision}[{$x}]";
			$ret .= "<tr class='{$trclass}'>\n";
			$ret .= "<th>\n";
			$ret .= $this->getTopicLink ( $this->topicList[$x]->val_comment ) ;
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
			$ret .= "<td>{$vote}</td>\n";
			
			$ret .= "<td><input size='50' style='width:98%' maxlength='250' type='text' name='re_c{$idx}' value='{$y->comment}'/>";
			$ret .= "</td></tr>\n";
		}
		$checked = $focus ? " checked='checked'" : "";
		$ret .= "<tr><td colspan='3'>\n";
		$ret .= "<input type='checkbox' name='re_merge_{$revision}' value='1'{$checked} />" . $this->getParsedWiki( wfMsg( 'val_merge_old' ) ) . " \n";
		$ret .= "<input type='checkbox' name='re_clear_{$revision}' value='1'{$checked} />" . $this->getParsedWiki( wfMsg( 'val_clear_old' ) ) . " \n";
		$ret .= "<input type='submit' name='re_submit[{$revision}]' value=\"" . wfMsgHtml( "ok" ) . "\" />\n";
		
		if( $focus ) $ret .= "<br/>\n<small>" . $this->getParsedWiki ( wfMsg( "val_form_note" ) ) . "</small>";
		$ret .= "</td></tr>\n";
		$ret .= "</table></form>\n\n";
		return $ret;
	}
	

	# Generates the page from the validation tab
	function validatePageForm( &$article, $revision ) {
		global $wgOut, $wgRequest, $wgUser;
		
		$ret = "";
		$this->page_id = $article->getID();
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
			$ret .= '<p class="revision_saved">' . $this->getParsedWiki( wfMsg( 'val_revision_changes_ok' ) ) . "</p>";
		} else {
			$ret .= $this->getParsedWiki( wfMsg ('val_votepage_intro') );
		}
		
		# Make sure the requested revision exists
		$rev = $this->getRevisionFromId($revision);
		$ts = $rev->rev_timestamp;
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
		$ret .= $this->getStatisticsLink( $article );
		$ret .= "<p>" . $this->getUserRatingsLink( $wgUser->getID(), wfMsg( 'val_show_my_ratings' ) ) . "</p>";
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
		$r .= "<table>\n";
		$r .= "<tr>" . wfMsg( 'val_list_header' ) . "</tr>\n";
		foreach( $this->topicList as $x => $y ) {
			$r .= "<tr>\n";
			$r .= "<th>{$y->val_type}</th>\n";
			$r .= "<td>" . $this->getTopicLink ( $y->val_comment ) . "</td>\n";
			$r .= "<td>1 .. <b>" . intval( $y->val_value ) . "</b></td>\n";
			$r .= "<td><input type='submit' name='m_del[" . intval( $x ) . "]' value='" . htmlspecialchars( wfMsg( 'val_del' ) ) . "'/></td>\n";
			$r .= "</tr>\n";
		}
		$r .= "<tr>\n";
		$r .= "<td></td>\n";
		$r .= '<td><input type="text" name="m_topic" value=""/></td>' . "\n";
		$r .= '<td>1 .. <input type="text" name="m_limit" value="" size="4"/></td>' . "\n";
		$r .= '<td><input type="submit" name="m_add" value="' . htmlspecialchars( wfMsg( 'val_add' ) ) . '"/></td>' . "\n";
		$r .= "</tr></table>\n";
		$r .= '<p><input type="checkbox" name="iamsure" id="iamsure" value="1"/>';
		$r .= '<label for="iamsure">' . $this->getParsedWiki( wfMsg( 'val_iamsure' ) ) . "</label></p>\n";
		$r .= "</form>\n";
		return $r;
	}
	
	# Generates an ID for both logged-in users and anons; $res is an object from an SQL query
	function make_user_id( &$res ) {
		return $res->val_user == 0 ? $res->val_ip : $res->val_user;
	}

	function showDetails( &$article, $revision ) {
		global $wgOut, $wgUser;
		$this->page_id = $article->getID();
		$this->topicList = $this->getTopicList();

		$sk = $wgUser->getSkin();
		$title = $article->getTitle();
		$wgOut->setPageTitle( str_replace( '$1', $title->getPrefixedText(), wfMsg( 'val_validation_of' ) ) );
		
		$data = array();
		$users = array();
		$topics = array();

		# Collecting statistic data
		$db =& wfGetDB( DB_SLAVE );
		$res = $db->select( 'validate', '*', array( 'val_page' => $this->page_id, 'val_revision' => $revision ), 'SpecialValidate::showDetails' );
		while( $x = $db->fetchObject($res) ) {
			$data[$this->make_user_id($x)][$x->val_type] = $x;
			$users[$this->make_user_id($x)] = true;
			$topics[$x->val_type] = true;
		}
		$db->freeResult($res);
		
		# Sorting lists of topics and users
		ksort( $users );
		ksort( $topics );
		
		$ts = $this->getRevisionTimestamp( $revision );
		$url = $this->getRevisionLink( $article, $revision, wfTimestamp( TS_DB, $ts ) );

		# Table headers
		$ret = "" ;			
		$ret .= "<p><b>" . str_replace( '$1', $url, wfMsg( 'val_revision_of' ) ) . "</b></p>\n";
		$ret .= "<table>\n";
		$ret .= "<tr><th>" . $this->getParsedWiki ( wfMsg('val_details_th') ) . "</th>" ;
		
		foreach( $topics as $t => $dummy ) {
			$ret .= '<th>' . $sk->commentBlock( $this->topicList[$t]->val_comment, $article->getTitle() ) . '</th>';
		}
		$ret .= "</tr>\n";

		# Table data
		foreach( $users as $u => $dummy ) { # Every row a user
			$ret .= "<tr>";
			$ret .= "<th>";
			if( !User::IsIP( $u ) ) { # Logged-in user rating
				$ret .= $this->getUserRatingsLink( $u, User::whoIs( $u ) );
			} else { # Anon rating
				$ret .= $this->getUserRatingsLink( $u, $u );
			}
			$ret .= "</th>";
			foreach( $topics as $t => $dummy ) { # Every column a topic
				if( !isset( $data[$u][$t] ) ) {
					$ret .= "<td/>";
				} else {
					$ret .= "<td>";
					$ret .= $data[$u][$t]->val_value;
					if( $data[$u][$t]->val_comment != "" ) {
						$ret .= ' ' . $sk->commentBlock( $data[$u][$t]->val_comment, $article->getTitle() );
					}
					$ret .= "</td>";
				}
			}
			$ret .= "</tr>";
		}
		$ret .= "</table>";
		$ret .= "<p>" . $this->getStatisticsLink( $article ) . "</p>";
		$ret .= "<p>" . $this->getUserRatingsLink( $wgUser->getID(), wfMsg( 'val_show_my_ratings' ) ) . "</p>";
		
		return $ret;
	}
	
	# XXX This should be paged
	function showList( &$article ) {
		global $wgOut, $wgUser;
		$this->page_id = $article->getID();
		$this->topicList = $this->getTopicList();

		$title = $article->getTitle();
		$wgOut->setPageTitle( str_replace( '$1', $title->getPrefixedText(), wfMsg( 'val_validation_of' ) ) );
		
		# Collecting statistic data
		$db =& wfGetDB( DB_SLAVE );
		$res = $db->select( 'validate', '*', array( "val_page" => $this->page_id ), 'SpecialValidate::showList' );

		$statistics = array();
		while( $vote = $db->fetchObject($res) ) {
			$ts = $this->getRevisionTimestamp($vote->val_revision);
			if ( !isset ( $statistics[$ts] ) ) $statistics[$ts] = array () ;
			if ( !isset ( $statistics[$ts][$vote->val_type]->count ) ) $statistics[$ts][$vote->val_type]->count = 0 ;
			if ( !isset ( $statistics[$ts][$vote->val_type]->sum ) ) $statistics[$ts][$vote->val_type]->sum = 0 ;
			$statistics[$ts][$vote->val_type]->count++;
			$statistics[$ts][$vote->val_type]->sum += $vote->val_value;
		}
		$db->freeResult($res);

		krsort( $statistics );
		
		$ret = "<table><tr>\n";
		$ret .= "<th>" . $this->getParsedWiki( wfMsg( "val_revision" ) ) . "</th>\n";
		foreach( $this->topicList as $topic ) {
			$ret .= "<th>" . $this->getTopicLink($topic->val_comment) . "</th>";
		}
		$ret .= "</tr>\n";

		foreach( $statistics as $ts => $data ) {
			$rev_id = $this->getRevisionId( $ts );
			$revision_link = $this->getRevisionLink( $article, $rev_id, wfTimestamp( TS_DB, $ts ) );
			$details_link = $this->getRevisionStatsLink( $article, $rev_id );
			$ret .= "<tr><td>{$revision_link} {$details_link}</td>";
			foreach( $this->topicList as $topicType => $topic ) {
				if( isset( $data[$topicType] ) ) {
					$stats = $data[$topicType];
					$average = $stats->count == 0 ? 0 : $stats->sum / $stats->count;
					$ret .= sprintf( "<td><b>%1.1f</b> (%d)</td>", $average, $stats->count );
				} else {
					$ret .= "<td></td>";
				}
			}
			$ret .= "</tr>\n";
		}
		$ret .= "</table>\n";
		$ret .= "<p>" . $this->getUserRatingsLink( $wgUser->getID(), wfMsg( 'val_show_my_ratings' ) ) . "</p>";
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

	# XXX This should be paged
	function showUserStats( $user ) {
		global $wgOut, $wgUser;
		$this->topicList = $this->getTopicList();
		$data = $this->getAllVoteLists( $user );
		$sk = $wgUser->getSkin();
		
		if( $user == $wgUser->getID() ) {
			$wgOut->setPageTitle ( wfMsg ( 'val_my_stats_title' ) );
		} elseif( !User::IsIP( $user ) ) {
			$wgOut->setPageTitle( wfMsg( 'val_user_stats_title', User::whoIs( $user ) ) );
		} else {
			$wgOut->setPageTitle( wfMsg( 'val_user_stats_title', $user ) );
		}
		
		$ret = "<table>\n";
		foreach( $data as $articleid => $revisions ) {
			$title = Title::newFromID( $articleid );
			$ret .= "<tr><th colspan='4'>";
			$ret .= $sk->makeKnownLinkObj( $title, $title->getEscapedText() );
			$ret .= "</th></tr>";
			krsort( $revisions );
			foreach( $revisions as $revid => $revision ) {
				$url = $title->getLocalURL( "oldid={$revid}" );
				$ret .= "<tr><th>";
				$ret .= $sk->makeKnownLinkObj( $title, wfMsg('val_revision_number', $revid ), "oldid={$revid}" );
				$ret .= "</th>";
				ksort( $revision );
				$initial = true;
				foreach( $revision as $topic => $rating ) {
					if( !$initial ) {
						$ret .= "<tr><td/>";
					}
					$initial = false;
					$ret .= "<td>" . $this->getTopicLink ( $this->topicList[$topic]->val_comment ) . "</td>";
					$ret .= "<td>" . $this->getRatingText( $rating->val_value, $this->topicList[$topic]->val_value ) . "</td>";
					$ret .= "<td>" . $sk->commentBlock( $rating->val_comment ) . "</td>";
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
