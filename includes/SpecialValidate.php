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
		$validationtypes = $wgLang->getValidationTypes() ;
		if ( $article_title == "" )
			{
			$article_title = $_GET['article_title'] ;
			$heading = "<h1>" . $article_title . "</h1>\n" ;
			}
		else $heading = "" ;
		$article_time = "" ;
		if ( isset ( $_GET['timestamp'] ) ) $article_time = $_GET['timestamp'] ;
		else $article_time = "" ;
		$article = Title::newFromText ( $article_title ) ;
			
		# Now we get all the "votes" for the different versions of this article for this user
		$val = $this->get_prev_data ( $wgUser->getID() , $article_title ) ;
		
		# No votes for this version, initial data
		if ( !isset ( $val[$article_time] ) )
			{
			if ( $article_time == "" )
				{
				$res = wfQuery( "select cur_timestamp FROM cur WHERE cur_title=\"{$article_title}\" AND cur_namespace=0", DB_READ );
				if ( $s = wfFetchObject( $res ) ) $article_time = $s->cur_timestamp ;
				}
			$val[$article_time] = array () ;
			}

		krsort ( $val ) ; # Newest versions first

		# User has clicked "Doit" before, so evaluate form
		if ( isset ( $_POST['doit'] ) )
			{
			$oldtime = $_POST['oldtime'] ;
			if ( !isset ( $val["{$oldtime}"] ) ) $val["{$oldtime}"] = array () ;
			
			# Reading postdata
			$postrad = array () ;
			$poscomment = array () ;
			for ( $idx = 0 ; $idx < count ( $validationtypes) ; $idx++ )	
				{
				$postrad[$idx] = $_POST["rad{$idx}"] ;
				$postcomment[$idx] = $_POST["comment{$idx}"] ;
				}
			
			# Merge others into this one
			if ( isset ( $_POST['merge_other'] ) && $_POST['merge_other'] == 1 )
				{
				foreach ( $val AS $time => $stuff )
					{
					if ( $time <> $article_time )
						{
						for ( $idx = 0 ; $idx < count ( $validationtypes) ; $idx++ )
							{
							$rad = $postrad[$idx] ;
							if ( isset ( $stuff[$idx] ) AND $stuff[$idx]->val_value != -1 AND $rad == -1 )
								{
								$postrad[$idx] = $stuff[$idx]->val_value ;
								$postcomment[$idx] = $stuff[$idx]->val_comment ;
								}
							}
						}
					}
				}
			
			# Clear all others
			if ( isset ( $_POST['clear_other'] ) && $_POST['clear_other'] == 1 )
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
	
			# Incorporate changes
			for ( $idx = 0 ; $idx < count ( $validationtypes) ; $idx++ ) # Changes
				{
				$comment = $postcomment[$idx] ;
				$comment_sql = str_replace ( "'" , "\'" , $comment ) ;
				$rad = $postrad[$idx] ;
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
		$html = "" ;
		
		$skin = $wgUser->getSkin() ;
		$staturl = $skin->makeSpecialURL ( "validate" , "mode=stat_page&article_title={$article_title}" ) ;
		$html .= "<a href=\"{$staturl}\">" . wfMsg('val_stat_link_text') . "</a><br>\n" ;
		$html .= "<small>" . wfMsg('val_form_note') . "</small><br>\n" ;
		
		# Generating data tables
		$tabsep = "<td width=0px style='border-left:2px solid black;'></td>" ;
		$topstyle = "style='border-top:2px solid black'" ;
		foreach ( $val AS $time => $stuff )
			{
			$tablestyle = "cellspacing=0 cellpadding=2" ;
			if ( $article_time == $time ) $tablestyle .=" style='border: 2px solid red'" ;
			$html .= str_replace ( "$1" , gmdate("F d, Y H:i:s",wfTimestamp2Unix($time)) , wfMsg("val_version_of") ) ;
			$html .= "<form method=post>\n" ;
			$html .= "<input type=hidden name=oldtime value='{$time}'>" ;
			$html .= "<table {$tablestyle}>\n" ;
			$html .= str_replace ( "$1" , $tabsep , wfMsg("val_table_header") ) ;
			for ( $idx = 0 ; $idx < count ( $validationtypes) ; $idx++ )
				{
				$x = explode ( "|" , $validationtypes[$idx] , 4 ) ;
				if ( isset ( $stuff[$idx] ) ) $choice = $stuff[$idx]->val_value ;
				else $choice = -1 ;
				if ( isset ( $stuff[$idx] ) ) $comment = $stuff[$idx]->val_comment ;
				else $comment = "" ;
				$html .= "<tr><th align=left>{$x[0]}</th>{$tabsep}<td align=right>{$x[1]}</td><td align=center>" ;			
				for ( $cnt = 0 ; $cnt < $x[3] ; $cnt++)
					{
					$html .= "<input type=radio name='rad{$idx}' value='{$cnt}'" ;
					if ( $choice == $cnt ) $html .= " checked" ;
					$html .= "> " ;
					}
				$html .= "</td><td>{$x[2]}</td>" ;
				$html .= "<td><input type=radio name='rad{$idx}' value='-1'" ;
				if ( $choice == -1 ) $html .= " checked" ;
				$html .= "> " . wfMsg ( "val_noop" ) . "</td>{$tabsep}" ;
				$html .= "<td><input type=text name='comment{$idx}' value='{$comment}'></td>" ;
				$html .= "</tr>\n" ;
				}
			$html .= "<tr><td {$topstyle} colspan=2></td><td {$topstyle} colspan=3>" ;
			$html .= "<input type=checkbox name=merge_other value=1 checked>" ;
			$html .= wfMsg ( 'val_merge_old' );
			$html .= "<br><input type=checkbox name=clear_other value=1 checked>" ;
			$html .= wfMsg ( 'val_clear_old', $skin->makeKnownLinkObj( $article ) );
			$html .= "</td><td {$topstyle} align=right valign=center><input type=submit name=doit value='" . wfMsg("ok") . "'></td>" ;
			$html .= "<td {$topstyle} colspan=2></td></tr></table></form>\n" ;
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
		while( $s = wfFetchObject( $res ) ) $ret["{$s->val_title}"]["{$s->val_timestamp}"]["{$s->val_type}"][] = $s ;
		return $ret ;
		}
		
	# Show statistics for the different versions of a single article
	function getPageStatistics ( $article_title = "" )
		{
		global $wgLang, $wgUser ;
		$validationtypes = $wgLang->getValidationTypes() ;
		$article_title = $_GET['article_title'] ;
		$d = $this->getData ( -1 , $article_title , -1 ) ;
		if ( count ( $d ) ) $d = array_shift ( $d ) ;
		else $d = array () ;
		krsort ( $d ) ;

		# Getting table data (cur_id, old_id etc.) for each version
		$table_id = array() ;
		$table_name = array() ;
		foreach ( $d AS $version => $data )
			{
			$this->find_this_version ( $article_title , $version , $table_id[$version] , $table_name[$version] ) ;
			}
 
		# Generating HTML
		$html = "<h1>Page validation statistics</h1>\n" ;
		$html .= "<table border=1 cellpadding=2 style='font-size:8pt;'>\n" ;
		$html .= "<tr><th>" . wfMsg('val_version') . "</th>" ;
		foreach ( $validationtypes AS $idx => $title )
			{
			$title = explode ( "|" , $title ) ;
			$html .= "<th>{$title[0]}</th>" ;
			}
		$html .= "<th>" . wfMsg('val_total') . "</th>" ;
		$html .= "</tr>\n" ;
		foreach ( $d AS $version => $data )
			{
			# Preamble for this version
			$title = Title::newFromDBkey ( $article_title ) ;
			$version_date = gmdate("F d, Y H:i:s",wfTimestamp2Unix($version)) ;
			$version_validate_link = $title->getLocalURL( "action=validate&timestamp={$version}" ) ;
			$version_validate_link = "<a class=intern href=\"{$version_validate_link}\">" . wfMsg('val_validate_version') . "</a>" ;
			if ( $table_name[$version] == 'cur' ) $version_view_link = $title->getLocalURL( "" ) ;
			else $version_view_link = $title->getLocalURL( "oldid={$table_id[$version]}" ) ;
			$version_view_link = "<a href=\"{$version_view_link}\">" . wfMsg('val_view_version') . "</a>" ;
			$html .= "<tr>" ;
			$html .= "<td align=center valign=top nowrap><b>{$version_date}</b><br>{$version_view_link}<br>{$version_validate_link}</td>" ;

			# Individual data
			$vmax = array() ;
			$vcur = array() ;
			$users = array() ;
			foreach ( $data AS $type => $x2 )
				{
				if ( !isset ( $vcur[$type] ) ) $vcur[$type] = 0 ;
				if ( !isset ( $vmax[$type] ) ) $vmax[$type] = 0 ;
				if ( !isset ( $users[$type] ) ) $users[$type] = 0 ;
				foreach ( $x2 AS $user => $x )
					{
					$vcur[$type] += $x->val_value ;
					$temp = explode ( "|" , $validationtypes[$type]) ;
					$vmax[$type] += $temp[3] - 1 ;
					$users[$type] += 1 ;
					}
				}
	
			$total_count = 0 ;
			$total_percent = 0 ;
			foreach ( $validationtypes AS $idx => $title )
				{
				if ( isset ( $vcur[$idx] ) )
					{
					$average = 100 * $vcur[$idx] / $vmax[$idx] ;
					$total_count += 1 ;
					$total_percent += $average ;
					if ( $users[$idx] > 1 ) $h = wfMsg ( "val_percent" ) ;
					else $h = wfMsg ( "val_percent_single" ) ;
					$h = str_replace ( "$1" , number_format ( $average , 2 ) , $h ) ;
					$h = str_replace ( "$2" , $vcur[$idx] , $h ) ;
					$h = str_replace ( "$3" , $vmax[$idx] , $h ) ;
					$h = str_replace ( "$4" , $users[$idx] , $h ) ;
					$html .= "<td align=center valign=top>" . $h ;
					}
				else
					{
					$html .= "<td align=center valign=center>" ;
					$html .= "(" . wfMsg ( "val_noop" ) . ")" ;
					}
				$html .= "</td>" ;
				}
			
			if ( $total_count > 0 )
				{
				$total = $total_percent / $total_count ;
				$total = number_format ( $total , 2 ) . " %" ;
				}
			else $total = "" ;
			$html .= "<td align=center valign=top nowrap><b>{$total}</b></td>" ;
			
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
