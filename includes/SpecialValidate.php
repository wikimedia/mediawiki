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
 */

/**
 *
 */
class Validation {
	
	function find_this_version( $article_title , &$article_time , &$id , &$tab ) {
		$id = "";
		$tab = "";
		$sql = "SELECT cur_id,cur_timestamp FROM cur WHERE cur_namespace=0 AND cur_title='" . wfStrencode( $article_title ) . "'";
		$res = wfQuery( $sql, DB_READ );
		if( $s = wfFetchObject( $res ) ) {
			if ( $article_time == "" ) {
				# No timestamp = current version
				$article_time = $s->cur_timestamp;
			} elseif ( $article_time == $s->cur_timestamp ) {
				# Means current version
				$tab = "cur";
				$id = $s->cur_id;
			}
		}
			
		if ( $id == "" ) {
			$sql = "SELECT old_id FROM old WHERE old_namespace=0 AND old_title='" . wfStrencode( $article_title ) .
				"' AND old_timestamp='" . wfStrencode( $article_time ) . "'";
			$res = wfQuery( $sql, DB_READ );
			if( $s = wfFetchObject( $res ) ) {
				$tab = "old";
				$id = $s->old_id;
			}
		}
	}

	function get_prev_data( $user_id , $article_title , $article_timestamp = "" ) {
		$ret = array ();
		$sql = "SELECT * FROM validate WHERE val_user='" . wfStrencode( $user_id ) .
			"' AND val_title='" . wfStrencode( $article_title ) . "'";
		if ( $article_timestamp != "" ) {
			$sql .= " AND val_timestamp='" . wfStrencode( $article_timestamp ) . "'";
		}
		$res = wfQuery( $sql, DB_READ );
		while( $s = wfFetchObject( $res ) ) {
			$ret[$s->val_timestamp][$s->val_type] = $s;
		}
		return $ret;
	}

	function validate_form( $article_title = "" ) {
		global $wgOut, $wgLang, $wgUser, $wgArticle, $wgRequest;
		
		if ( $wgUser->getID() == 0 ) {
			# Anon
			$wgOut->addHTML( htmlspecialchars( wfMsg( 'val_no_anon_validation' ) ) .
				$this->getPageStatistics ( $article_title ) ) ;
			return;
		}
			
		$validationtypes = $wgLang->getValidationTypes();
		if ( $article_title == "" ) {
			$article_title = $wgRequest->getVal( 'article_title' );
			$heading = "<h1>" . htmlspecialchars( $article_title ) . "</h1>\n";
		} else {
			$heading = "";
		}
		$article_time = "";
		$article_time = $wgRequest->getVal( 'timestamp' );
		$article = Title::newFromText( $article_title );
		if( is_null( $article ) ) {
			$wgOut->errorpage( "badtitle", "badtitletext" );
			return;
		}
		
		# Now we get all the "votes" for the different versions of this article for this user
		$val = $this->get_prev_data( $wgUser->getID() , $article_title );
		
		# No votes for this version, initial data
		if( !isset( $val[$article_time] ) ) {
			if( $article_time == "" ) {
				$res = wfQuery( "select cur_timestamp FROM cur WHERE cur_title='" .
					wfStrencode( $article_title ) . "' AND cur_namespace=0", DB_READ );
				if( $s = wfFetchObject( $res ) ) {
					$article_time = $s->cur_timestamp;
				}
			}
			$val[$article_time] = array();
		}

		# Newest versions first
		krsort( $val );

		# User has clicked "Doit" before, so evaluate form
		if( $wgRequest->wasPosted() ) {
			$oldtime = StrVal( $wgRequest->getVal( 'oldtime' ) );
			if( !isset ( $val[$oldtime] ) ) {
				$val[$oldtime] = array();
			}
			
			# Reading postdata
			$postrad = array();
			$poscomment = array();
			for( $idx = 0 ; $idx < count( $validationtypes) ; $idx++ ) {
				$postrad[$idx] = $wgRequest->getVal( "rad{$idx}" );
				$postcomment[$idx] = $wgRequest->getText( "comment{$idx}" );
			}
			
			# Merge others into this one
			if( $wgRequest->getCheck( 'merge_other' ) ) {
				foreach( $val as $time => $stuff ) {
					if( $time != $article_time ) {
						for( $idx = 0; $idx < count( $validationtypes ); $idx++ ) {
							$rad = $postrad[$idx];
							if( isset ( $stuff[$idx] ) && $stuff[$idx]->val_value != -1 && $rad == -1 ) {
								$postrad[$idx] = $stuff[$idx]->val_value;
								$postcomment[$idx] = $stuff[$idx]->val_comment;
							}
						}
					}
				}
			}

			# Clear all others
			if( $wgRequest->getCheck( 'clear_other' ) ) {
				$sql = "DELETE FROM validate WHERE val_title='" . wfStrencode( $article_title ) .
					"' AND val_timestamp<>'" . wfStrencode( $oldtime ) . "' AND val_user='";
				$sql .= wfStrencode( $wgUser->getID() ) . "'";
				wfQuery( $sql, DB_WRITE );
				$val2 = $val[$oldtime]; # Only version left
				$val = array(); # So clear others
				$val[$oldtime] = $val2;
			}

			# Delete old "votes" for this version
			$sql = "DELETE FROM validate WHERE val_title='" . wfStrencode( $article_title ) .
				"' AND val_timestamp='" . wfStrencode( $oldtime ) . "' AND val_user='";
			$sql .= wfStrencode( $wgUser->getID() ) . "'";
			wfQuery( $sql, DB_WRITE );

			# Incorporate changes
			for( $idx = 0; $idx < count( $validationtypes ); $idx++ ) {
				$comment = $postcomment[$idx] ;
				$rad = $postrad[$idx] ;
				if ( !isset( $val[$oldtime][$idx] ) ) {
					$val[$oldtime][$idx] = "";
				}
				$val[$oldtime][$idx]->val_value = $rad;
				$val[$oldtime][$idx]->val_comment = $comment;
				if( $rad != -1 ) {
					# Store it in the database
					$sql = "INSERT INTO validate (val_user,val_title,val_timestamp,val_type,val_value,val_comment) " . 
						 "VALUES ( '" . wfStrencode( $wgUser->getID() ) . "','" .
						 wfStrencode( $article_title ) . "','" .
						 wfStrencode( $oldtime ) . "','" . 
						 wfStrencode( $idx ) . "','" . 
						 wfStrencode( $rad ) . "','" .
						 wfStrencode( $comment ) . "')";
					wfQuery( $sql, DB_WRITE );
				}
			}
			$wgArticle->showArticle( "Juhuu", wfMsg( 'val_validated' ) );
			return; # Show article instead of validation page
		}

		# Generating HTML
		$html = "";

		$skin = $wgUser->getSkin();
		$staturl = $skin->makeSpecialURL( "validate" , "mode=stat_page&article_title=" . urlencode( $article_title ) );
		$listurl = $skin->makeSpecialURL( "validate" , "mode=list_page" );
		$html .= "<a href=\"" . htmlspecialchars( $staturl ) . "\">" . wfMsg('val_stat_link_text') . "</a> \n";
		$html .= "<a href=\"" . htmlspecialchars( $listurl ) . "\">" . wfMsg('val_article_lists') . "</a><br />\n";
		$html .= "<small>" . wfMsg('val_form_note') . "</small><br />\n";
		
		# Generating data tables
		$tabsep = "<td width='0' style='border-left:2px solid black;'></td>";
		$topstyle = "style='border-top:2px solid black'";
		foreach( $val as $time => $stuff ) {
			$tablestyle = "cellspacing='0' cellpadding='2'";
			if ( $article_time == $time ) {
				$tablestyle .=" style='border: 2px solid red'";
			}
			$html .= "<h2>" . wfMsg( 'val_version_of', gmdate( "F d, Y H:i:s", wfTimestamp2Unix( $time ) ) );
			$this->find_this_version ( $article_title , $time , $table_id , $table_name );
			if( $table_name == "cur" ) {
				$html .= " (" . wfMsg( 'val_this_is_current_version' ) . ")";
			}
			$html .= "</h2>\n" ;
			$html .= "<form method='post'>\n" ;
			$html .= "<input type='hidden' name='oldtime' value=\"" . htmlspecialchars( $time ) . "\" />" ;
			$html .= "<table {$tablestyle}>\n" ;
			$html .= wfMsg( 'val_table_header', $tabsep );
			for( $idx = 0; $idx < count( $validationtypes ); $idx++ ) {
				$x = explode( "|" , $validationtypes[$idx] , 4 );
				if( isset ( $stuff[$idx] ) ) {
					$choice = $stuff[$idx]->val_value;
				} else {
					$choice = -1;
				}
				if( isset( $stuff[$idx] ) ) {
					$comment = $stuff[$idx]->val_comment;
				} else {
					$comment = "";
				}
				$html .= "<tr><th align='left'>{$x[0]}</th>{$tabsep}<td align='right'>{$x[1]}</td>"
				      .  "<td align='center'><span style='white-space: nowrap;'>" ;
				for( $cnt = 0 ; $cnt < $x[3] ; $cnt++) {
					$html .= "<input type='radio' name='rad{$idx}' value='{$cnt}'";
					if ( $choice == $cnt ) $html .= " checked='checked'";
					$html .= " /> ";
				}
				$html .= "</span></td><td>{$x[2]}</td>";
				$html .= "<td><input type='radio' name='rad{$idx}' value='-1'";
				if( $choice == -1 ) {
					$html .= " checked='checked'";
				}
				$html .= " /> " . wfMsg ( "val_noop" ) . "</td>{$tabsep}";
				$html .= "<td><input type='text' name='comment{$idx}' value=\"" . htmlspecialchars( $comment ) . "\" /></td>";
				$html .= "</tr>\n";
			}
			$html .= "<tr><td {$topstyle} colspan='2'>";

			# link to version
			$title = Title::newFromDBkey( $article_title );
			if ( $table_name == "cur" ) {
				$link_version = $title->getLocalURL( "" );
			} else {
				$link_version = $title->getLocalURL( "oldid={$table_id}" );
			}
			$link_version = "<a href=\"" . htmlspecialchars( $link_version ) . "\">" . wfMsg ( 'val_view_version' ) . "</a>";
			$html .= $link_version;
			$html .= "</td><td {$topstyle} colspan='5'>";
			$html .= "<input type='checkbox' name='merge_other' value='1' checked='checked' />";
			$html .= wfMsg( 'val_merge_old' );
			$html .= "<br /><input type='checkbox' name='clear_other' value='1' checked='checked' />";
			$html .= wfMsg( 'val_clear_old', $skin->makeKnownLinkObj( $article ) );
			$html .= "</td><td {$topstyle} align='right' valign='center'><input type='submit' name='doit' value=\"" . htmlspecialchars( wfMsg("ok") ) . "\" /></td>";
			$html .= "</tr></table></form>\n";
		}
		
		$html .= "<h2>" . wfMsg( 'preview' ) . "</h2>";
		$wgOut->addHTML( $html );
		$wgOut->addWikiText( $wgArticle->getContent( true ) );
	}

	function getData( $user = -1 , $title = "" , $type = -1 ) {
		$ret = array();
		$sql = array();
		if( $user != -1 ) {
			$sql[] = "val_user='" . wfStrencode( $user ) . "'";
		}
		if( $type != -1 ) {
			$sql[] = "val_type='" . wfStrencode( $type ) . "'";
		}
		if( $title != "" ) {
			$sql[] = "val_title='" . wfStrencode( $title ) . "'";
		}
		$sql = implode( " AND " , $sql );
		if( $sql != "" ) {
			$sql = " WHERE " . $sql;
		}
		$sql = "SELECT * FROM validate" . $sql;
		$res = wfQuery( $sql, DB_READ );
		while( $s = wfFetchObject( $res ) ) {
			$ret["{$s->val_title}"]["{$s->val_timestamp}"]["{$s->val_type}"][] = $s;
		}
		return $ret;
	}

	# Show statistics for the different versions of a single article
	function getPageStatistics( $article_title = "" ) {
		global $wgLang, $wgUser, $wgOut, $wgRequest;
		$validationtypes = $wgLang->getValidationTypes();
		if( $article_title == "" ) {
			$article_title = $wgRequest->getVal( 'article_title' );
		}
		$d = $this->getData( -1 , $article_title , -1 );
		if( count ( $d ) ) {
			$d = array_shift ( $d ) ;
		} else {
			$d = array();
		}
		krsort( $d );

		# Getting table data (cur_id, old_id etc.) for each version
		$table_id = array();
		$table_name = array();
		foreach( $d as $version => $data ) {
			$this->find_this_version( $article_title, $version, $table_id[$version], $table_name[$version] );
		}

		# Generating HTML
		$title = Title::newFromDBkey( $article_title );
		$wgOut->setPageTitle( wfMsg( 'val_page_validation_statistics' , $title->getPrefixedText() ) );
		$html = "";
		$skin = $wgUser->getSkin();
		$listurl = $skin->makeSpecialURL( "validate" , "mode=list_page" );
		$html .= "<a href=\"" . htmlspecialchars( $listurl ) . "\">" . wfMsg( 'val_article_lists' ) . "</a><br /><br />\n";

		$html .= "<table border='1' cellpadding='2' style='font-size:8pt;'>\n";
		$html .= "<tr><th>" . wfMsg('val_version') . "</th>";
		foreach( $validationtypes as $idx => $title ) {
			$title = explode ( "|" , $title );
			$html .= "<th>{$title[0]}</th>";
		}
		$html .= "<th>" . wfMsg('val_total') . "</th>";
		$html .= "</tr>\n";
		foreach( $d as $version => $data ) {
			# Preamble for this version
			$title = Title::newFromDBkey( $article_title );
			$version_date = $wgLang->timeanddate( $version );
			$version_validate_link = $title->escapeLocalURL( "action=validate&timestamp={$version}" );
			$version_validate_link = "<a href=\"{$version_validate_link}\">" . wfMsg('val_validate_version') . "</a>";
			if( $table_name[$version] == 'cur' ) {
				$version_view_link = $title->escapeLocalURL( "" );
			} else {
				$version_view_link = $title->escapeLocalURL( "oldid={$table_id[$version]}" );
			}
			$version_view_link = "<a href=\"{$version_view_link}\">" . wfMsg('val_view_version') . "</a>";
			$html .= "<tr>";
			$html .= "<td align='center' valign='top' nowrap='nowrap'><b>{$version_date}</b><br />{$version_view_link}<br />{$version_validate_link}</td>";

			# Individual data
			$vmax = array();
			$vcur = array();
			$users = array();
			foreach( $data as $type => $x2 ) {
				if ( !isset ( $vcur[$type] ) ) $vcur[$type] = 0 ;
				if ( !isset ( $vmax[$type] ) ) $vmax[$type] = 0 ;
				if ( !isset ( $users[$type] ) ) $users[$type] = 0 ;
				foreach( $x2 as $user => $x ) {
					$vcur[$type] += $x->val_value;
					$temp = explode( "|" , $validationtypes[$type] );
					$vmax[$type] += $temp[3] - 1;
					$users[$type] += 1;
				}
			}

			$total_count = 0;
			$total_percent = 0;
			foreach( $validationtypes as $idx => $title ) {
				if( isset ( $vcur[$idx] ) ) {
					$average = 100 * $vcur[$idx] / $vmax[$idx] ;
					$total_count += 1;
					$total_percent += $average;
					if( $users[$idx] > 1 ) {
						$msgid = "val_percent";
					} else {
						$msgid = "val_percent_single";
					}
					$html .= "<td align='center' valign='top'>" .
							wfMsg( $msgid, number_format( $average, 2 ) ,
									$vcur[$idx] , $vmax[$idx] , $users[$idx] );
				} else {
					$html .= "<td align='center' valign='center'>";
					$html .= "(" . wfMsg ( "val_noop" ) . ")";
				}
				$html .= "</td>";
			}
			
			if( $total_count > 0 ) {
				$total = $total_percent / $total_count;
				$total = number_format( $total , 2 ) . " %";
			} else {
				$total = "";
			}
			$html .= "<td align='center' valign='top' nowrap='nowrap'><b>{$total}</b></td>";

			$html .= "</tr>";
		}
		$html .= "</table>\n";
		return $html ;
	}

	function countUserValidations( $userid ) {
		$sql = "SELECT count(DISTINCT val_title) AS num FROM validate WHERE val_user=" . IntVal( $userid );
		$res = wfQuery( $sql, DB_READ );
		if ( $s = wfFetchObject( $res ) ) {
			$num = $s->num;
		} else {
			$num = 0;
		}
		return $num;
	}

	function getArticleList() {
		global $wgLang, $wgOut;
		$validationtypes = $wgLang->getValidationTypes();
		$wgOut->setPageTitle( wfMsg( 'val_article_lists' ) );
		$html = "";

		# Choices
		$choice = array ();
		$maxw = 0;
		foreach( $validationtypes as $idx => $data ) {
			$x = explode( "|" , $data , 4 );
			if( $x[3] > $maxw ) {
				$maxw = $x[3];
			}
		}
		foreach( $validationtypes as $idx => $data ) {
			$choice[$idx] = array();
			for( $a = 0 ; $a < $maxw ; $a++ ) {
				$var = "cb_{$idx}_{$a}";
				if( isset ( $_POST[$var] ) ) $choice[$idx][$a] = $_POST[$var] ; # Selected
				else if ( !isset ( $_POST["doit"] ) ) $choice[$idx][$a] = 1 ; # First time
				else $choice[$idx][$a] = 0 ; # De-selected
			}
		}

		# The form
		$html .= "<form method='post'>\n";
		$html .= "<table border='1' cellspacing='0' cellpadding='2'>" ;
		foreach( $validationtypes as $idx => $data ) {
			$x = explode ( "|" , $data , 4 );
			
			$html .= "<tr>";
			$html .= "<th nowrap='nowrap'>{$x[0]}</th>";
			$html .= "<td align='right' nowrap='nowrap'>{$x[1]}</td>";

			for( $a = 0; $a < $maxw; $a++ ) {
				if( $a < $x[3] ) {
					$td = "<input type='checkbox' name='cb_{$idx}_{$a}' value='1'";
					if( $choice[$idx][$a] == 1 ) {
						$td .= " checked='checked'" ;
					}
					$td .= " />";
				} else {
					$td = '';
				}
				$html .= "<td>{$td}</td>";
			}

			$html .= "<td nowrap='nowrap'>{$x[2]}</td>";
			$html .= "</tr>\n";
		}
		$html .= "<tr><td colspan='" . ( $maxw + 2 ) . "'></td>\n";
		$html .= "<td align='right' valign='center'><input type='submit' name='doit' value=\"" . htmlspecialchars( wfMsg ( 'ok' ) ) . "\" /></td></tr>";
		$html .= "</table>\n";
		$html .= "</form>\n";

		# The query
		$articles = array();
		$sql = "SELECT DISTINCT val_title,val_timestamp,val_type,avg(val_value) AS avg FROM validate GROUP BY val_title,val_timestamp,val_type";
		$res = wfQuery( $sql, DB_READ );
		while( $s = wfFetchObject( $res ) ) {
			$articles[$s->val_title][$s->val_timestamp][$s->val_type] = $s;
		}

		# The list
		$html .= "<ul>\n";
		foreach( $articles as $dbkey => $timedata ) {
			$title = Title::newFromDBkey( $dbkey );
			$out = array();
			krsort( $timedata );

			foreach( $timedata as $timestamp => $typedata ) {
				$showit = true;
				foreach( $typedata as $type => $data ) {
					$avg = intval ( $data->avg + 0.5 );
					if ( $choice[$type][$avg] == 0 ) $showit = false;
				}
				if( $showit ) {
					$out[] = "<li>" . $this->getVersionLink ( $title , $timestamp ) . "</li>\n";
				}
			}

			if( count( $out ) > 0 ) {
				$html .= "<li>\n";
				$html .= htmlspecialchars( $title->getText() ) . "\n";
				$html .= "<ul>\n";
				$html .= implode( "\n" , $out );
				$html .= "</ul>\n</li>\n";
			}
		}
		$html .= "</ul>\n";
		return $html;
	}

	function getVersionLink( &$title , $timestamp ) {
		global $wgLang;
		$dbkey = $title->getDBkey();
		$this->find_this_version( $dbkey, $timestamp, $table_id, $table_name );
		if( $table_name == 'cur' ) {
			$link = $title->getLocalURL( "" );
		} else {
			$link = $title->getLocalURL( "action=validate&timestamp={$table_id}" );
		}
		$linktitle = wfMsg( 'val_version_of', $wgLang->timeanddate( $timestamp ) );
		$link = "<a href=\"" . htmlspecialchars( $link ) . "\">" . $linktitle . "</a>";
		if( $table_name == 'cur' ) {
			$link .= " (" . wfMsg ( 'val_this_is_current_version' ) . ")";
		}

		$vlink = wfMsg( 'val_tab' );
		$vlink = "[<a href=\"" . $title->escapeLocalURL( "action=validate&timestamp={$timestamp}" ) . "\">{$vlink}</a>] " . $link;
		return $vlink ;
	}

}

/**
 * constructor
 */
function wfSpecialValidate( $page = '' ) {
	global $wgOut, $wgRequest, $wgUseValidation;

	if( !$wgUseValidation ) {
		$wgOut->errorpage( "nosuchspecialpage", "nospecialpagetext" );
		return;
	}

	$mode = $wgRequest->getVal( 'mode', 'form' );
	$v = new Validation;
	$html = "" ;
/*	if( $mode == "form" ) {
		$html = $v->validate_form () ;
	} else */
	if( $mode == "stat_page" ) {
		$html = $v->getPageStatistics();
	} else if( $mode == "list_page" ) {
		$html = $v->getArticleList();
	}
	
	$wgOut->addHTML( $html );
}

?>
