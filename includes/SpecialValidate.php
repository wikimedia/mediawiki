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
	var $topicList ;
	var $voteCache ;
	var $rev2date ;
	var $date2ref ;

	# Reads all revision information of the specified article
	function prepareRevisions ( $id ) {
		$this->rev2date = array () ;
		$this->date2rev = array () ;
		$sql = "SELECT * FROM revision WHERE rev_page='{$id}'" ;
		$res = wfQuery( $sql, DB_READ );
		while( $x = wfFetchObject( $res ) ) {
			$this->rev2date[$x->rev_id] = $x ;
			$this->date2rev[$x->rev_timestamp] = $x ;
		}
		}

	# Returns a HTML link to the specified article revision
	function getVersionLink( &$article , $revision , $text = "" ) {
		$t = $article->getTitle() ;
		if ( $text == "" ) $text = wfMsg("val_view_version");
		$ret = "<a href=\"" . $t->getLocalURL ( "oldid={$revision}" ) . "\">" . $text . "</a>" ;
		return $ret ;
	}

	# Returns an array containing all topics you can vote on
	function getTopicList () {
		$ret = array () ;
		$sql = "SELECT * FROM validate WHERE val_user=0" ;
		$res = wfQuery( $sql, DB_READ );
		while( $x = wfFetchObject( $res ) ) {
			$ret[$x->val_type] = $x ;
		}
		ksort ( $ret ) ;
		return $ret ;
	}
	
	# Merges one dataset into another
	function mergeInto ( &$source , &$dest ) {
		$ret = false ;
		foreach ( $source AS $x => $y ) {
			$doit = false ;
			if ( !isset ( $dest[$x] ) ) $doit = true ;
			else if ( $dest[$x]->value == 0 ) $doit = true ;
			if ( $doit ) {
				$dest[$x] = $y ;
				$ret = true ;
			}
		}
		if ( $ret ) ksort ( $dest ) ;
		return $ret ;
	}

	# Merges all votes prior to the given revision into it
	function mergeOldRevisions ( &$article , $revision ) {
		$tmp = $this->voteCache ;
		krsort ( $tmp ) ;
		$update = false ;
		$ts = $this->getTimestamp($revision) ;
		$data = $this->voteCache[$ts] ;
		foreach ( $tmp AS $x => $y ) {
			if ( $x < $ts ) {
				if ( $this->mergeInto ( $y , $data ) ) $update = true ;
			}
		}
		if ( $update ) $this->setRevision ( $article , $revision , $data ) ;
	}
	
	# Clears all votes prior to the given revision
	function clearOldRevisions ( &$article , $revision ) {
		$tmp = $this->voteCache ;
		$ts = $this->getTimestamp($revision);
		foreach ( $tmp AS $x => $y ) {
			if ( $x < $ts ) $this->deleteRevision ( $article , $this->getRevisionNumber($x) ) ;
		}
	}
	
	# Updates the votes for the given revision from the FORM data
	function updateRevision ( &$article , $revision ) {
		global $wgUser, $wgRequest ;
		
		if ( isset ( $this->voteCache[$this->getTimestamp($revision)] ) ) $data = $this->voteCache[$this->getTimestamp($revision)] ;
		else $data = array () ;
		$nv = $wgRequest->getArray ( "re_v_{$revision}" , array() ) ;
		$nc = $wgRequest->getArray ( "re_c_{$revision}" , array() ) ;
		
		foreach ( $nv AS $x => $y ) {
			$data[$x]->value = $y ;
			$data[$x]->comment = $nc[$x] ;
		}
		krsort ( $data ) ;
		
		$this->setRevision ( $article , $revision , $data ) ;
	}
	
	# Sets a specific revision to both cache and database
	function setRevision ( &$article , $revision , &$data ) {
		global $wgUser ;
		$this->deleteRevision ( $article , $revision ) ;
		$this->voteCache[$this->getTimestamp($revision)] = $data ;
		foreach ( $data AS $x => $y ) {
			if ( $y->value > 0 ) {
				$sql = "INSERT INTO validate (val_user,val_page,val_revision,val_type,val_value,val_comment) VALUES ('" ;
				$sql .= $wgUser->getID() . "','" ;
				$sql .= $article->getID() . "','" ;
				$sql .= $revision . "','" ;
				$sql .= $x . "','" ;
				$sql .= $y->value . "','" ;
				$sql .= Database::strencode ( $y->comment ) . "')" ;
				$res = wfQuery( $sql, DB_WRITE );
			}
		}
	}
	
	# Deletes a specific vote set in both cache and database
	function deleteRevision ( &$article , $revision ) {
		global $wgUser ;
		$ts = $this->getTimestamp ( $revision ) ;
		if ( !isset ( $this->voteCache[$ts] ) ) return ; # Nothing to do
		$sql = "DELETE FROM validate WHERE val_user='" . $wgUser->GetID() . "' AND " ;
		$sql .= " val_page='" . $article->getID() . "' AND val_revision='{$revision}'" ;
		$res = wfQuery( $sql, DB_WRITE );
		unset ( $this->voteCache[$ts] ) ;
	}
	
	# Reads the entire vote list for this user for the given article
	function getVoteList ( $id ) {
		global $wgUser ;
		$r = array () ; # Revisions
		$sql = "SELECT * FROM validate WHERE val_page=" . $id . " AND val_user=" . $wgUser->getID() ;
		$res = wfQuery( $sql, DB_READ );
		while( $x = wfFetchObject( $res ) ) {
			#$y = $x->val_revision ;
			$y = $this->rev2date[$x->val_revision] ;
			$y = $y->rev_timestamp ;
			if ( !isset($r[$y]) ) $r[$y] = array () ;
			$r[$y][$x->val_type]->value = $x->val_value ;
			$r[$y][$x->val_type]->comment = $x->val_comment ;
		}		
		return $r ;
	}
	
	# This functions adds a topic to the database
	function addTopic ( $topic , $limit ) {
		$a = 1 ;
		while ( isset ( $this->topicList[$a] ) ) $a++ ;
		$sql = "INSERT INTO validate (val_user,val_page,val_revision,val_type,val_value,val_comment) VALUES (" ;
		$sql .= "'0','0','0','{$a}','{$limit}','" ;
		$sql .= Database::strencode ( $topic ) . "')" ;
		$res = wfQuery( $sql, DB_WRITE );
		$x->val_user = $x->val_page = $x->val_revision = 0 ;
		$x->val_type = $a ;
		$x->val_value = $limit ;
		$x->val_comment = $topic ;
		$this->topicList[$a] = $x ;
		ksort ( $this->topicList ) ;
	}

	# This functions adds a topic to the database
	function deleteTopic ( $id ) {
		$sql = "DELETE FROM validate WHERE val_type='{$id}'" ;
		$res = wfQuery( $sql, DB_WRITE );
		unset ( $this->topicList[$id] ) ;
	}
	
	# This function returns a link text to the page validation statistics
	function link2statistics ( &$article ) {
		$ret = wfMsg ( 'val_rev_stats_link' ) ;
		$nt = $article->getTitle() ;
		$ret = str_replace ( "$1" , $nt->getPrefixedText() , $ret ) ;

		$url = $nt->getLocalURL ( "action=validate&mode=list" ) ;
		$ret = str_replace ( "$2" , $url , $ret ) ;

		return $ret ;
	}

	# Returns the timestamp of a revision based on the revision number
	function getTimestamp ( $revision ) {
		$ts = $this->rev2date[$revision] ;
		$ts = $ts->rev_timestamp ;
		return $ts ;
	}

	# Returns the revision number of a revision based on the timestamp
	function getRevisionNumber ( $ts ) {
		$revision = $this->date2rev[$ts] ;
		$revision = $revision->rev_id ;
		return $revision ;
	}


	# HTML generation functions from this point on
	
	# Returns the metadata string for a revision
	function getMetadata ( $idx ) {
		$metadata = "" ;
		$x = $this->rev2date[$idx] ;
		$metadata .= wfTimestamp ( TS_DB , $x->rev_timestamp ) ;
		$metadata .= " by " ;
		if ( $x->rev_user == 0 ) {
			$metadata .= $x->rev_user_text ;
		} else {
			$u = new User ;
			$u->setId ( $x->rev_user ) ;
			$u->setName ( $x->rev_user_text ) ;
			$nt = $u->getUserPage() ;
			$url = "<a href='" . $nt->getLocalUrl () . "'>" . $nt->getText() . "</a>" ;
			$metadata .= $url ;
		}
		$metadata .= " : <small>\"" . htmlspecialchars ( $x->rev_comment ) . "\"</small>" ;
		return $metadata ;
	}

	# Generates a form for a single revision
	function getRevisionForm ( &$article , $idx , &$data , $focus = false ) {
		# Fill data with blank values
		$ts = $idx ;
		$revision = $this->getRevisionNumber ( $ts ) ;
		foreach ( $this->topicList AS $x => $y ) {
			if ( !isset ( $data[$x] ) ) {
				$data[$x]->value = 0 ;
				$data[$x]->comment = "" ;
			}
		}
		ksort ( $data ) ;		
	
		# Generate form
		$ret = "<form method='post'>" ;
		$ret .= "<table border='1' cellspacing='0' cellpadding='2'" ;
		if ( $focus ) $ret .= " style='background-color:#00BBFF'" ;
		$ret .= ">\n" ;
		$head = "Revision #" . $revision ;
		$link = " " . $this->getVersionLink ( $article , $revision ) ;
		$metadata = $this->getMetadata ( $revision ) ;
		$ret .= "<tr><th align='left' colspan='3'>" . $head . " ({$link}) {$metadata}</th></tr>\n" ;
		$line = 0 ;
		foreach ( $data AS $x => $y ) {
			$line = 1 - $line ;
			$col = $line == 1 ? "#DDDDDD" : "#EEEEEE" ;
			$idx = "_{$revision}[{$x}]" ;
			$ret .= "<tr bgcolor='{$col}'>\n" ;
			$ret .= "<th nowrap>" ;
			$ret .= $this->topicList[$x]->val_comment ;
			$ret .= "</th>\n" ;
			
			$tlx = $this->topicList[$x] ;
			$vote = "" ;
			$max = $tlx->val_value ;
			for ( $a = 0 ; $a <= $max ; $a++ ) {
				if ( $a == 0 ) $vote .= wfMsg ( "val_noop" ) ;
				$vote .= "<input type='radio' name='re_v{$idx}' value='{$a}'" ;
				if ( $a == $y->value ) $vote .= " checked" ;
				$vote .= "/>" ;
				if ( $max == 2 && $a == 1 ) $vote .= wfMsg ( "val_no" ) . " " ;
				else if ( $max == 2 && $a == 2 ) $vote .= wfMsg ( "val_yes" ) ;
				else if ( $a != 0 ) $vote .= $a . " " ;
				if ( $a == 0 ) $vote .= " &nbsp; " ;
			}			
			$ret .= "<td nowrap valign='center'>{$vote}</td>\n" ;
			
			$ret .= "<td width='100%' align='center'><input size='50' style='width:98%' maxlength='250' type='text' name='re_c{$idx}' value='{$y->comment}'/>" ;
			$ret .= "</td></tr>\n" ;
		}
		$checked = $focus ? " checked" : "" ;
		$ret .= "<tr><td colspan='3' valign='center'>\n" ;
		$ret .= "<input type='checkbox' name='re_merge_{$revision}' value='1'{$checked}/>" . wfMsg( 'val_merge_old' ) . " \n" ;
		$ret .= "<input type='checkbox' name='re_clear_{$revision}' value='1'{$checked}/>" . wfMsg( 'val_clear_old' ) . " \n" ;
		$ret .= "<input type='submit' name='re_submit[{$revision}]' value='" . htmlspecialchars( wfMsg("ok") ) . "'/>\n" ;
		if ( $focus ) $ret .= "<br/>\n<small>" . wfMsg ( "val_form_note" ) . "</small>" ;
		$ret .= "</td></tr>\n" ;
		$ret .= "</table>\n</form>\n\n" ;
		return $ret ;
	}
	

	# Generates the page from the validation tab
	function validatePageForm ( &$article , $revision ) {
		global $wgOut, $wgRequest ;
		
		$this->prepareRevisions ( $article->getID() ) ;
		$this->topicList = $this->getTopicList() ;
		$this->voteCache = $this->getVoteList ( $article->getID() ) ;
		
		# Check for POST data
		$re = $wgRequest->getArray( 're_submit' );
		if ( isset ( $re ) )
			{
			$id = array_keys ( $re ) ;
			$id = $id[0] ; # $id is now the revision number the user clicked "OK" for
			$clearOldRev = $wgRequest->getVal( "re_clear_{$id}" , 0 );
			$mergeOldRev = $wgRequest->getVal( "re_merge_{$id}" , 0 );
			$this->updateRevision ( $article , $id ) ;
			if ( $mergeOldRev ) $this->mergeOldRevisions ( $article , $id ) ;
			if ( $clearOldRev ) $this->clearOldRevisions ( $article , $id ) ;
			}
		
		# Make sure the requested revision exists
		$ts = $this->rev2date[$revision]->rev_timestamp ;
		if ( !isset ( $this->voteCache[$ts] ) ) $this->voteCache[$ts] = array () ;
		
		# Sort revisions list, newest first
		krsort ( $this->voteCache ) ;
		
		# Output
		$ret = "" ;
		$title = $article->getTitle();
		$title = $title->getPrefixedText() ;
		$wgOut->setPageTitle ( wfMsg ( 'val_rev_for' ) . $title ) ;
		foreach ( $this->voteCache AS $x => $y )
			{
			$ret .= $this->getRevisionForm ( $article , $x , $y , $x == $ts ) ;
			$ret .= "<br/>\n" ;
			}
		$ret .= $this->link2statistics ( $article ) ;
		return $ret ;	
	}
	
	# This function performs the "management" mode on Special:Validate
	function manageTopics () {
		global $wgRequest ;
		$this->topicList = $this->getTopicList() ;
		
		$iamsure = $wgRequest->getVal ( "iamsure" , "0" ) == 1 ;
		
		if ( $iamsure && $wgRequest->getVal ( "m_add" , "--" ) != "--" ) {
			$new_topic = $wgRequest->getVal ( "m_topic" ) ;
			$new_limit = $wgRequest->getVal ( "m_limit" ) ;
			if ( $new_topic != "" && $new_limit > 1 )
				$this->addTopic ( $new_topic , $new_limit ) ;
		}

		$da = $wgRequest->getArray ( "m_del" ) ;
		if ( $iamsure && isset ( $da ) && count ( $da ) > 0 ) {
			$id = array_keys ( $da ) ;
			$id = array_shift ( $id ) ;
			$this->deleteTopic ( $id ) ;
		}
		
		$r = "<p>" . wfMsg ( 'val_warning' ) . "</p>\n" ;
		$r .= "<form method='post'>\n" ;
		$r .= "<table border='1' cellspacing='0' cellpadding='2'>\n" ;
		$r .= "<tr>" . wfMsg ( 'val_list_header' ) . "</tr>\n" ;
		foreach ( $this->topicList AS $x => $y ) {
			$r .= "<tr>\n" ;
			$r .= "<th>" . $y->val_type . "</th>\n" ;
			$r .= "<td>{$y->val_comment}</td>\n" ;
			$r .= "<td>1 .. <b>{$y->val_value}</b></td>\n" ;
			$r .= "<td><input type='submit' name='m_del[{$x}]' value='" . wfMsg ( 'val_del' ) . "'/></td>\n" ;
			$r .= "</tr>\n" ;
		}
		$r .= "<tr>\n" ;
		$r .= "<td/>\n" ;
		$r .= "<td><input type='text' name='m_topic' value=''/></td>\n" ;
		$r .= "<td><input type='text' name='m_limit' value=''/></td>\n" ;
		$r .= "<td><input type='submit' name='m_add' value='" . wfMsg ( 'val_add' ) . "'/></td>\n" ;
		$r .= "</tr>\n" ;
		$r .= "</table>\n" ;
		$r .= "<input type='checkbox' name='iamsure' value='1'/>" . wfMsg ( 'val_iamsure' ) . "\n" ;
		$r .= "</form>\n" ;
		return $r ;
	}
	
	function showList ( &$article ) {
		$this->prepareRevisions ( $article->getID() ) ;
		$this->topicList = $this->getTopicList() ;
		
		# Collecting statistic data
		$id = $article->getID() ;
		$sql = "SELECT * FROM validate WHERE val_page='{$id}'" ;
		$res = wfQuery( $sql, DB_READ );
		$data = array () ;
		while( $x = wfFetchObject( $res ) ) {
			#$idx = $x->val_revision ;
			$idx = $this->getTimestamp ( $x->val_revision ) ;
			if ( !isset ( $data[$idx] ) )
				$data[$idx] = array () ;
			if ( !isset ( $data[$idx][$x->val_type] ) ) {
				$data[$idx][$x->val_type]->count = 0 ;
				$data[$idx][$x->val_type]->sum = 0 ;
			}
			$data[$idx][$x->val_type]->count++ ;
			$data[$idx][$x->val_type]->sum += $x->val_value ;
		}
		
		krsort ( $data ) ;
		
		$ret = "" ;
		$ret .= "<table border='1' cellspacing='0' cellpadding='2'>\n" ;
		$ret .= "<tr><th>" . wfMsg("val_revision") . "</th>" ;
#		$ret .= "<th>" . wfMsg("val_time") . "</th>" ;
		foreach ( $this->topicList AS $x => $y )
			$ret .= "<th>{$y->val_comment}</th>" ;
		$ret .= "</tr>\n" ;
		foreach ( $data AS $ts => $y ) {
			$revision = $this->getRevisionNumber ( $ts ) ;
#			$url = $this->getVersionLink ( $article , $revision , $revision ) ;
			$url = $this->getVersionLink ( $article , $revision , wfTimestamp ( TS_DB , $ts ) ) ;
			$ret .= "<tr><th>{$url}</th>" ;
#			$ret .= "<td nowrap>" . wfTimestamp ( TS_DB , $ts ) . "</td>" ;
			foreach ( $this->topicList AS $topicID => $dummy ) {
				if ( isset ( $y[$topicID] ) ) {
					$z = $y[$topicID] ;
					if ( $z->count == 0 ) $a = 0 ;
					else $a = $z->sum / $z->count ;
					$ret .= sprintf ( "<td><b>%1.1f</b> (%d)</td>" , $a , $z->count ) ;
				} else $ret .= "<td/>" ;
				}
			$ret .= "</tr>\n" ;
		}
		$ret .= "</table>\n" ;
		return $ret ;
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
	if ( ! $wgUser->isAllowed('change_validation') ) {
		$wgOut->sysopRequired();
		return;
	}
*/	

	$mode = $wgRequest->getVal ( "mode" ) ;
	$skin = $wgUser->getSkin() ;

	
	if ( $mode == "manage" ) {
		$v = new Validation ;
		$html = $v->manageTopics () ;
#	} else if ( $mode == "list" ) {
#		$v = new Validation ;
#		$html = $v->showList ( $wgRequest->getVal ( "id" ) ) ;
	} else {
		$html = "$mode" ;
		$html .= "<ul>\n" ;

		$t = Title::newFromText ( "Special:Validate" ) ;
		$url = $t->getLocalURL ( "mode=manage" ) ;
		$html .= "<li><a href=\"" . $url . "\">Manage</a></li>\n" ;

		$html .= "</ul>\n" ;
	}

	$wgOut->addHTML( $html );
}

?>
