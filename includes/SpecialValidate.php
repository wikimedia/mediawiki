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

class Validation
	{
	# Things that should be in the language files
	var $types = array (
			"0" => "Style|Awful|Awesome|5",
			"1" => "Legal|Illegal|Legal|5",
			"2" => "Completeness|Stub|Extensive|5",
			"3" => "Facts|Wild guesses|Concrete|5"
			) ;
	
	function find_this_version ( $article_title , &$article_time , &$id , &$tab )
		{
		$id = "" ;
		$sql = "SELECT cur_id,cur_timestamp FROM cur WHERE cur_namespace=0 AND cur_title='{$article_title}'" ;
		$res = wfQuery( $sql, DB_READ );
		if( $s = wfFetchObject( $res ) )
			{
			if ( $article_time == "" ) $article_time = $s->cur_timestamp ; # No timestamp = current version
			if ( $article_time == $s->cur_timestamp ) # Means current version
				{
				$tab = "cur" ;
				$id = $s->cur_id ;
				}
			}
			
		if ( $id == "" )
			{
			$sql = "SELECT old_id FROM old WHERE old_namespace=0 AND old_title='{$article_title}' AND old_timestamp='{$article_time}'" ;
			$res = wfQuery( $sql, DB_READ );
			if( $s = wfFetchObject( $res ) )
				{
				$tab = "old" ;
				$id = $s->old_id ;
				}
			}
		}
		
	function get_prev_data ( $user_id , $article_title , $article_timestamp = "" )
		{
		$ret = array () ;
		$sql = "SELECT * FROM validate WHERE val_user='{$user_id}' AND val_title='{$article_title}'" ;
		if ( $article_timestamp != "" ) $sql .= " AND val_timestamp='{$article_timestamp}'" ;
		$res = wfQuery( $sql, DB_READ );
		while( $s = wfFetchObject( $res ) ) $ret[$s->val_timestamp][$s->val_type] = $s ;
		return $ret ;
		}
	
	function validate_form ( $article_title = "" )
		{
		global $wgOut, $wgLang, $wgUser;
		if ( $wgUser->getID() == 0 ) return ; # Anon
		if ( $article_title == "" ) $article_title = $_GET['article'] ;
		$article_time = "" ;
		if ( isset ( $_GET['timestamp'] ) ) $article_time = $_GET['timestamp'] ;	
		$article = Title::newFromText ( $article_title ) ;
			
		# Now we get all the "votes" for the different versions of this article for this user
		$val = $this->get_prev_data ( $wgUser->getID() , $article_title ) ;
		
		# User has clicked "Doit" before, so evaluate form
		if ( isset ( $_POST['doit'] ) )
			{
			$oldtime = $_POST['oldtime'] ;
			if ( !isset ( $val["{$oldtime}"] ) ) $val["{$oldtime}"] = array () ;
			if ( isset ( $_POST['clear_other'] ) && $_POST['clear_other'] == 1 ) # Clear all others
				{
				$sql = "DELETE FROM validate WHERE val_title='{$article_title}' AND val_timestamp<>'{$oldtime}' AND val_user='" ;
				$sql .= $wgUser->getID() . "'" ;
				wfQuery( $sql, DB_WRITE );
				$val2 = $val["{$oldtime}"] ; # Only version left
				$val = array () ; # So clear others
				$val["{$oldtime}"] = $val2 ;
				}
	
			# Delete old "votes" for this version
			$sql = "DELETE FROM validate WHERE val_title='{$article_title}' AND val_timestamp='{$oldtime}' AND val_user='" ;
			$sql .= $wgUser->getID() . "'" ;
			wfQuery( $sql, DB_WRITE );
	
			for ( $idx = 0 ; $idx < count ( $this->types) ; $idx++ ) # Changes
				{
				$comment = $_POST["comment{$idx}"] ;
				$comment_sql = str_replace ( "'" , "\'" , $comment ) ;
				$rad = $_POST["rad{$idx}"] ;
				if ( !isset ( $val["{$oldtime}"][$idx] ) ) $val["{$oldtime}"][$idx] = "" ;
				$val["{$oldtime}"][$idx]->val_value = $rad ;
				$val["{$oldtime}"][$idx]->val_comment = $comment ;
				if ( $rad != -1 )
					{
					# Store it in the database
					$sql = "INSERT INTO validate (val_user,val_title,val_timestamp,val_type,val_value,val_comment) " . 
						 "VALUES ( '" . $wgUser->getID() . "','{$article_title}','{$oldtime}','{$idx}','{$rad}','{$comment_sql}')" ;
					if ( $rad != -1 ) wfQuery( $sql, DB_WRITE );
					}
				}
			}
		
		# Generating HTML
		$html = "<h1>" . $article->getPrefixedText() . "</h1>\n" ;
		foreach ( $val AS $time => $stuff )
			{
			if ( $time == $article_time ) $html .= "<h2>This version</h2>\n" ;
			else $html .= "<h2>Version of {$time}</h2>\n" ;
			$html .= "<form method=post>\n" ;
			$html .= "<input type=hidden name=oldtime value='{$time}'>" ;
			$html .= "<table>\n" ;
			$html .= "<tr><th>Class</th><th colspan=4>Opinion</th><th>Comment</th></tr>\n" ;
			for ( $idx = 0 ; $idx < count ( $this->types) ; $idx++ )
				{
				$x = explode ( "|" , $this->types[$idx] , 4 ) ;
				if ( isset ( $stuff[$idx] ) ) $choice = $stuff[$idx]->val_value ;
				else $choice = -1 ;
				if ( isset ( $stuff[$idx] ) ) $comment = $stuff[$idx]->val_comment ;
				else $comment = "" ;
				$html .= "<tr><th align=left>{$x[0]}</th><td align=right>{$x[1]}</td><td align=center>" ;			
				for ( $cnt = 0 ; $cnt < $x[3] ; $cnt++)
					{
					$html .= "<input type=radio name='rad{$idx}' value='{$cnt}'" ;
					if ( $choice == $cnt ) $html .= " checked" ;
					$html .= "> " ;
					}
				$html .= "</td><td>{$x[2]}</td>" ;
				$html .= "<td><input type=radio name='rad{$idx}' value='-1'" ;
				if ( $choice == -1 ) $html .= " checked" ;
				$html .= "> " . wfMsg ( "val_noop" ) . "</td>" ;
				$html .= "<td><input type=text name='comment{$idx}' value='{$comment}'></td>" ;
				$html .= "</tr>\n" ;
				}
			$html .= "<tr><td></td><td colspan=3><input type=checkbox name=clear_other value=1 checked>" ;
			$html .= str_replace ( "$1" , $article->getPrefixedURL() , wfMsg("clear_old") ) ;
			$html .= "</td><td align=right><input type=submit name=doit value='Do it'></td>" ;
			$html .= "</tr></table></form>\n" ;
			}
		return $html ;
		}
		
	function getData ( $user = -1 , $title = "" , $type = -1 )
		{
		$ret = array () ;
		$sql = array () ;
		if ( $user != -1 ) $sql[] = "val_user='{$user}'" ;
		if ( $type != -1 ) $sql[] = "val_type='{$type}'" ;
		if ( $title != "" ) $sql[] = "val_title='{$title}'" ;
		$sql = implode ( " AND " , $sql ) ;
		if ( $sql != "" ) $sql = " WHERE " . $sql ;
		$sql = "SELECT * FROM validate" . $sql ;
		$res = wfQuery( $sql, DB_READ );
		while( $s = wfFetchObject( $res ) ) $ret["{$s->val_title}"]["{$s->val_timestamp}"]["{$s->val_type}"] = $s ;
		return $ret ;
		}
		
	function getPageStatistics ( $article_title = "" )
		{
		$article_title = $_GET['article'] ;
		$html = "<h1>Page validation statistics</h1>\n" ;
		$d = $this->getData ( -1 , $article_title , -1 ) ;
		if ( count ( $d ) ) $d = array_shift ( $d ) ;
		else $d = array () ;
		$html .= "<table border=1 cellpadding=2>\n" ;
		$html .= "<tr><th>Version</th>" ;
		foreach ( $this->types AS $idx => $title )
			{
			$title = explode ( "|" , $title ) ;
			$html .= "<th>{$title[0]}</th>" ;
			}
		$html .= "</tr>\n" ;
		foreach ( $d AS $version => $data )
			{
			$html .= "<tr>" ;
			$html .= "<th align=left>{$version}</th>" ;

			$vmax = array() ;
			$vcur = array() ;
			foreach ( $data AS $type => $x )
				{
				if ( !isset ( $vcur[$type] ) ) $vcur[$type] = 0 ;
				if ( !isset ( $vmax[$type] ) ) $vmax[$type] = 0 ;
				$vcur[$type] += $x->val_value ;
				$vmax[$type] += $title[3] ;
				}
	
	
			foreach ( $this->types AS $idx => $title )
				{
				$html .= "<td>" ;
				if ( isset ( $vcur[$idx] ) )
					{
					$average = 100 * $vcur[$idx] / $vmax[$idx] ;
					$h = wfMsg ( "val_percent" ) ;
					$h = str_replace ( "$1" , $average , $h ) ;
					$h = str_replace ( "$2" , $vcur[$idx] , $h ) ;
					$h = str_replace ( "$3" , $vmax[$idx] , $h ) ;
					$html .= $h ;
					}
				else $html .= "(" . wfMsg ( "val_noop" ) . ")" ;
				$html .= "</td>" ;
				}
			
			$html .= "</tr>" ;
			}
		$html .= "</table>\n" ;
		return $html ;
		}
		
	}

function wfSpecialValidate( $page = "" )
	{
	global $wgOut ;
	if ( isset ( $_GET['mode'] ) ) $mode = $_GET['mode'] ;
	else $mode = "form" ;
	$v = new Validation ;
	$html = "" ;
	if ( $mode == "form" )
		{
		$html = $v->validate_form () ;
		}
	else if ( $mode == "stat_page" )
		{
		$html = $v->getPageStatistics () ;
		}
	
	$wgOut->addHTML( $html ) ;
	}

?>
