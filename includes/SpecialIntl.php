<?

function wfSpecialIntl()
{
	global $wgUser, $wgOut, $wgLang, $wgTitle;
	global $limit, $offset; # From query string
	global $wgDBconnection ;
	$fname = "wfSpecialIntl";
	$s = "" ;

	if ( ! $limit ) {
		$limit = $wgUser->getOption( "rclimit" );
		if ( ! $limit ) { $limit = 50; }
	}
	if ( ! $offset ) { $offset = 0; }

	# Connecting to the wiki-intl database
	$c = $wgDBconnection ;
	if ( !mysql_select_db ( $wgDBIntlName, $c ) ) {
		$wgOut->addHTML( htmlspecialchars(mysql_error()) );
		return ;
		}

	global $mode ;
	$mode = strtolower ( trim ( $mode ) ) ;
	if ( $mode == "" ) $mode = "main" ;

	if ( $mode == "main" ) $s .= intl_main ( $c ) ;
	else if ( $mode == "addlink" ) $s .= intl_add ( $c ) ;
	else if ( $mode == "zoom" ) $s .= intl_zoom ( $c ) ;
	else if ( $mode == "incominglinks" ) $s .= intl_incoming ( $c ) ;
	else if ( $mode == "outgoinglinks" ) $s .= intl_outgoing ( $c ) ;
	else if ( $mode == "alllinks" ) $s .= intl_all ( $c ) ;
	else if ( $mode == "delete" ) $s .= intl_delete ( $c ) ;
	else if ( $mode == "recentchanges" ) $s .= intl_recentchanges ( $c ) ;

	$si = "Special:Intl" ;
	$sk = $wgUser->getSkin();
	if ( $mode != "" && $mode != "main" )
		$s .= $sk->makeKnownLink($si,"International issues main menu") ;

	$wgOut->addHTML( $s );
}

function appendRecentChanges ( $message ) {
	global $wgDBconnection , $wgUser , $wgLanguageCode , $wgLang ;
	$user_name = $wgLang->getNSText(Namespace::getUser()).":".$wgUser->getName() ;
	$user_lang = $wgLanguageCode ;
	$message = str_replace ( '"' , '\"' , $message ) ;
	$sql = "INSERT INTO recentchanges (user_name,user_lang,message) VALUES (
		\"{$user_name}\",
		\"{$user_lang}\",
		\"{$message}\")" ;
	$res = mysql_query ( $sql , $wgDBconnection ) ;
	}

function intl_recentchanges ( $c ) {
	global $wgLang ;
	$r = "<h2>Recent Link Changes</h2>\n" ;

	$rc = array () ;
	$sql = "SELECT * FROM recentchanges ORDER BY date DESC LIMIT 250" ;
	$res = mysql_query ( $sql , $c ) ;
	while ( $q = mysql_fetch_object ( $res ) ) $rc[] = $q ;
	mysql_free_result ( $res ) ;

	$r .= "<UL>\n" ;
	foreach ( $rc AS $x ) {
		$r .= "<li>" ;
		$r .= getArticleLink ( $x->user_name , $x->user_lang ) ;
		$h = $wgLang->time( $x->date, true );
		$r .= " ({$h}) " ;
		$r .= $x->message ;
		$r .= "</li>\n" ;
		}
	$r .= "</UL>\n" ;

	return $r ;
	}

function getArticleLink ( $title , $lang = "" ) {
	global $wgLanguageCode ;
	$cl = "external" ;
	if ( $lang == "" ) $lang = $wgLanguageCode ;
	if ( $lang == $wgLanguageCode ) $cl = "internal" ;
	$nt = Title::newFromText ( $title ) ;
	$link = "http://".$lang.".wikipedia.org/wiki/".$title ;
	$link = "<a class='{$cl}' href=\"{$link}\">".$nt->getText()."</a>" ;
	return $link ;
	}

function intl_main ( $c ) {
	global $wgUser ;
	$sk = $wgUser->getSkin();
	$si = "Special:Intl" ;

	$r = "<h2>International issues main menu</h2>" ;
	$r .= "<UL>" ;
	$r .= "<li>".$sk->makeKnownLink($si,"Add a link","mode=addlink")."</li>" ;
	$r .= "<li>".$sk->makeKnownLink($si,"View incoming links","mode=incominglinks")."</li>" ;
	$r .= "<li>".$sk->makeKnownLink($si,"View outgoing links","mode=outgoinglinks")."</li>" ;
	$r .= "<li>".$sk->makeKnownLink($si,"View all links","mode=alllinks")."</li>" ;
	$r .= "<li>".$sk->makeKnownLink($si,"Recent Link Changes","mode=recentchanges")."</li>" ;
	$r .= "</UL>" ;
	return $r ;
	}

function intl_add_doit ( $c ) {
	global $wgUser , $wgLang , $wgLanguageCode , $doit ;
	global $l_f , $l_t , $t_f , $t_t , $backlink ;
	$sk = $wgUser->getSkin();
	$si = "Special:Intl" ;

	# checking for language link
	$q = explode ( ":" , $t_t , 2 ) ;
	$ln = $wgLang->getLanguageNames();
	if ( count ( $q ) == 2 ) {
		$nl_t = trim ( array_shift ( $q ) ) ;
		$nt_t = trim ( array_shift ( $q ) ) ;
		if ( $nl_t != "" AND isset ( $ln[$nl_t] ) ) {
			$l_t = $nl_t ;
			$t_t = $nt_t ;
			}
		}

	$nt = Title::newFromText ( $t_f ) ;
	$t_f = $nt->getDBkey() ;
	$nt = Title::newFromText ( $t_t ) ;
	$t_t = $nt->getDBkey() ;
	
	$r = "<h2>Creating/updating language links</h2>" ;

	# Deleting forward link
	$sql = "DELETE FROM ilinks WHERE 
lang_from='{$l_f}' AND
lang_to='{$l_t}' AND
title_from='{$t_f}'
" ;
	$res = mysql_query ( $sql , $c ) ;

	$r .= "Executed {$sql}" ;
	$r .= "<br>Result {$res}" ;
	$r .= "<br>Error ".  htmlspecialchars(mysql_error()) ;
	$r .= "<br><br>" ;

	# Adding link
	$sql = "INSERT INTO ilinks (lang_from,lang_to,title_from,title_to) VALUES
('{$l_f}','{$l_t}','{$t_f}','{$t_t}')" ;
	$res = mysql_query ( $sql , $c ) ;

	$r .= "Executed {$sql}" ;
	$r .= "<br>Result {$res}" ;
	$r .= "<br>Error ".  htmlspecialchars(mysql_error()) ;

	appendRecentChanges ( $ln[$l_f].":".getArticleLink($t_f,$l_f)." &rarr; ".
				$ln[$l_t].":".getArticleLink($t_t,$l_t) ) ;

	if ( $backlink == "on" ) {
		$backlink = "" ;
		$x = $l_f ; $l_f = $l_t ; $l_t = $x ;
		$x = $t_f ; $t_f = $t_t ; $t_t = $x ;
		intl_add_doit ( $c ) ; # Ugly recursion
		}

	return $r ;
	}

function intl_add ( $c ) {
	global $wgUser , $wgLang , $wgLanguageCode , $doit , $mode ;
	global $xl , $xt , $yl , $yt ;
	$r = "" ;
	if ( isset ( $doit ) ) {
		global $al_t , $at_t , $l_t , $t_t ;
		for ( $x = 0 ; $x < 10 ; $x++ ) {
			if ( trim($at_t[$x]) != "" ) {
				$t_t = $at_t[$x] ;
				$l_t = $al_t[$x] ;
				$r .= "<font color=red size=+1>".
					"The link ".
					$l_f.":".$t_f." &harr; ".$l_t.":".$t_t.
					" has been added.</font><br>" ;
				intl_add_doit ( $c ) ;
				}
			}
		$yt = "" ;
		$yl = "" ;
		}

	$sk = $wgUser->getSkin();
	$si = "Special:Intl" ;

	if ( $xl == "" ) $xl = $wgLanguageCode ;

	$oxt = $xt ;
	$oyt = $yt ;
	$nt = Title::newFromText ( $xt ) ;
	$xt = $nt->getPrefixedText () ;
	$nt = Title::newFromText ( $yt ) ;
	$yt = $nt->getPrefixedText () ;

	$ll1 = $ll2 = "" ;
	$a = $wgLang->getLanguageNames();
	$ak = array_keys ( $a ) ;
	foreach ( $ak AS $k ) {
		$sel = "" ;
		if ( $k == $xl ) $sel = " SELECTED" ;
		$ll1 .= "<option{$sel} value='{$k}'>{$a[$k]}</option>\n" ;
		$sel = "" ;
		if ( $k == $yl ) $sel = " SELECTED" ;
		$ll2 .= "<option{$sel} value='{$k}'>{$a[$k]}</option>\n" ;
		}

	$r .= "<h2>Add or update a link</h2>" ;

	if ( $oxt != "" ) {
		$zl = "See the group of articles interlinked for ".$a[$xl].":".$xt ;
		$zl = $sk->makeKnownLink($si,$zl,"mode=zoom&xl={$xl}&xt={$oxt}")."<br>\n" ;
		$al = getArticleLink ( $oxt , $xl ) ;
		$r .= $zl.$al ;
		}

	$r .= "Note: You can also type the language code before the target (e.g., 'en:target'). The selection of the drop down box will then be ignored.<br>\n" ;

	$r .= "<FORM method=post>\n" ;

	$r .= "<li>Source \n" ;
	$r .= "<select name=l_f>\n{$ll1}</select>\n " ;
	$r .= "<input type=text name=t_f value=\"{$xt}\">\n" ;
	$r .= "</li>\n" ;

	for ( $x = 0 ; $x < 10 ; $x++ ) {
		$r .= "<li>Destin. \n" ;
		$r .= "<select name='al_t[{$x}]'>\n{$ll2}</select>\n " ;
		$r .= "<input type=text name='at_t[{$x}]' value=\"{$yt}\">\n" ;
		$r .= "</li>\n" ;
		}

	$r .= "<INPUT type=checkbox name=backlink checked>Add link in both directions<br>\n" ;

	$r .= "<INPUT type=submit name=doit value='Do it'>\n" ;

	$r .= "</FORM>\n" ;

	return $r ;
	}

function eliminate_doubles ( &$list ) { # Real ugly
	$ak = array_keys ( $list ) ;
	foreach ( $ak AS $k1 ) {
		if ( $list[$k1]->hidden ) continue ;
		foreach ( $ak AS $k2 ) {
			if ( $k1 != $k2 &&
				$list[$k1]->title_from == $list[$k2]->title_to &&
				$list[$k1]->title_to == $list[$k2]->title_from &&
				$list[$k1]->lang_from == $list[$k2]->lang_to &&
				$list[$k1]->lang_to == $list[$k2]->lang_from ) {
				$list[$k1]->both = true ;
				$list[$k2]->hidden = true ;
				break ;
				}
			}
		}
	}

function displayLinks ( $list , $opt = "" ) {
	eliminate_doubles ( $list ) ;
	global $wgLang , $wgUser , $mode ;
	$si = "Special:Intl" ;
	$sk = $wgUser->getSkin();
	$ln = $wgLang->getLanguageNames();
	$r = "" ;

	if ( !isset ( $opt->showdel ) ) $opt->showdel = true ;

	global $limit , $offset , $intlparam ;
	if ( $intlparam != "" ) {
		$r .= wfShowingResults( $offset, $limit );
		$sl = wfViewPrevNext( $offset, $limit,
		  $wgLang->specialPage( "Intl".$intlparam ) );
		$r .= "<br>{$sl}\n" ;
		}

	$r .= "<table border=1 cellpadding=2 cellspacing=0>\n" ;
	$r .= "<tr>\n" ;
	$r .= "<th colspan=2>From</th>\n" ;
	$r .= "<th>&nbsp;</th>\n" ;
	$r .= "<th colspan=2>To</th>\n" ;
	if ( $mode != "zoom" ) $r .= "<th>&nbsp;</th>\n" ;
	if ( $opt->showdel ) $r .= "<th colspan=3>Delete</th>\n" ;
	if ( $opt->display != "" ) $r .= "<th colspan=3>".$opt->display."</th>\n" ;
	$r .= "</tr>\n" ;

	foreach ( $list AS $q ) {
		if ( $q->hidden ) continue ;
		$zoom = "xl={$q->lang_from}&xt=".urlencode($q->title_from) ;
		$zoom = $sk->makeKnownLink($si,"[&Sigma;]","mode=zoom&{$zoom}") ;
		$del1 = "xl={$q->lang_from}&xt=".urlencode($q->title_from)."&yl={$q->lang_to}" ;
		$del2 = $sk->makeKnownLink($si,"[&harr;]","mode=delete&{$del1}&back=yes") ;
		$del1 = $sk->makeKnownLink($si,"[&rarr;]","mode=delete&{$del1}") ;
		$del1a = "xl={$q->lang_to}&xt=".urlencode($q->title_to)."&yl={$q->lang_from}" ;
		$del1a = $sk->makeKnownLink($si,"[&larr;]","mode=delete&{$del1a}") ;
		$sign = "&rarr;" ;
		if ( $q->both ) $sign = "&harr;" ;
		else $del1a = "&nbsp;" ;

		$r .= "<tr>\n" ;
		$r .= "<td>".$ln[$q->lang_from]."</td>\n" ;
		$r .= "<td>".getArticleLink($q->title_from,$q->lang_from)."</td>\n" ;
		$r .= "<td> {$sign} </td>\n" ;
		$r .= "<td>".$ln[$q->lang_to]."</td>\n" ;
		$r .= "<td>".getArticleLink($q->title_to,$q->lang_to)."</td>\n" ;
		if ( $mode != "zoom" ) $r .= "<td>{$zoom}</td>\n" ;
		if ( $opt->showdel ) {
			$r .= "<td>{$del1}</td>\n" ;
			$r .= "<td>{$del1a}</td>\n" ;
			$r .= "<td>{$del2}</td>\n" ;
			}
		if ( $opt->display != "" ) {
			if ( $q->display == "" ) $q->display = "&nbsp;" ;
			$r .= "<td>{$q->display}</td>\n" ;
			}
		$r .= "</tr>\n" ;
		}
	$r .= "</table>\n" ;
	if ( $intlparam != "" )
		$r .= "{$sl}<br>\n" ;
	return $r ;
	}

function intl_outgoing ( $c ) {
	global $wgLanguageCode ;
	global $limit , $offset , $intlparam ;
	$intlparam = "&mode=outgoinglinks" ;
	$list = array() ;
	$r = "<h2>Outgoing links</h2>\n" ;
	$sql = "SELECT * FROM ilinks WHERE lang_from='{$wgLanguageCode}' LIMIT {$offset}, {$limit}";
	$res = mysql_query ( $sql , $c ) ;
	while ( $q = mysql_fetch_object ( $res ) ) $list[] = $q ;
	mysql_free_result ( $res ) ;
	$r .= displayLinks ( $list ) ;
	return $r ;
	}

function intl_incoming ( $c ) {
	global $wgLanguageCode ;
	global $limit , $offset , $intlparam ;
	$intlparam = "&mode=incominglinks" ;
	$list = array() ;
	$r = "<h2>Incoming links</h2>\n" ;
	$sql="SELECT * FROM ilinks WHERE lang_to='{$wgLanguageCode}' LIMIT {$offset}, {$limit}";
	$res = mysql_query ( $sql , $c ) ;
	while ( $q = mysql_fetch_object ( $res ) ) $list[] = $q ;
	mysql_free_result ( $res ) ;
	$r .= displayLinks ( $list ) ;
	return $r ;
	}

function intl_all ( $c ) {
	global $wgLanguageCode ;
	global $limit , $offset , $intlparam ;
	$intlparam = "&mode=alllinks" ;
	$list = array() ;
	$r = "<h2>All links</h2>\n" ;
	$sql = "SELECT * FROM ilinks LIMIT {$offset}, {$limit}";
	$res = mysql_query ( $sql , $c ) ;
	while ( $q = mysql_fetch_object ( $res ) ) $list[] = $q ;
	mysql_free_result ( $res ) ;
	$r .= displayLinks ( $list ) ;
	return $r ;
	}


function do_zoom ( &$found , &$list , $c ) {
	$news = array () ;
	foreach ( $found AS $x ) {
		if ( $x->new ) {
$sql = "SELECT * FROM ilinks WHERE 
( lang_from='{$x->lang}' AND title_from='{$x->title}' ) OR 
( lang_to='{$x->lang}'   AND title_to='{$x->title}' )
" ;
			$res = mysql_query ( $sql , $c ) ;
			while ( $q = mysql_fetch_object ( $res ) ) {
				$i->orig = $q ;
				$i->lang = $q->lang_from ;
				$i->title = $q->title_from ;
				$news[] = $i ;

				$i->lang = $q->lang_to ;
				$i->title = $q->title_to ;
				$news[] = $i ;
				}
			mysql_free_result ( $res ) ;
			}
		}
	$ak = array_keys ( $found ) ;
	foreach ( $ak AS $x ) $found[$x]->new = false ;

	# Adding new ones
	$isnewone = false ;
	foreach ( $news AS $n ) {
		$didfind = 0 ;
		foreach ( $found AS $f ) {
			if($n->lang==$f->lang AND $n->title==$f->title) {
				$didfind=1;
				if ( $f->new ) $list[] = $n->orig ;
				}
			}
		if ( $didfind == 0 ) {
			$i->lang = $n->lang ;
			$i->title = $n->title ;
			$i->new = true ;
			$found[] = $i ;
			$list[] = $n->orig ;
			$isnewone = true ;
			}
		}

	if ( $isnewone ) do_zoom ( $found , $list , $c ) ;
	}

function getMissingLinks ( $found , $list ) {
	$a = $r = array () ;
	foreach ( $found AS $f1 ) {
		foreach ( $found AS $f2 ) {
			if ( $f1 != $f2 ) {
				$i->lang_from = $f1->lang ;
				$i->lang_to = $f2->lang ;
				$i->title_from = $f1->title ;
				$i->title_to = $f2->title ;
				$a[] = $i ;
				}
			}
		}
	foreach ( $a AS $x ) {
		$f = false ;
		foreach ( $list AS $l ) {
			if ( $x->lang_from == $l->lang_from &&
			     $x->lang_to == $l->lang_to &&
			     $x->title_from == $l->title_from &&
			     $x->title_to == $l->title_to ) {
				$f = true ;
				break ;
				}
			}
		if ( !$f ) $r[] = $x ;
		}
	return $r ;
	}

function intl_zoom2 ( $c ) {
	global $doit , $ZLF , $ZLT , $ZTF , $ZTT , $ZCB ;
	global $l_f , $l_t , $t_f , $t_t , $backlink ;
	$r = "<h2>Adding selected language links</h2>\n" ;
	$r .= "<OL>\n" ;
	$ak = array_keys ( $ZCB ) ;
	foreach ( $ak AS $cnt ) {
		if ( $ZCB[$cnt] == "on" ) {
			$l_f = $ZLF[$cnt] ;
			$l_t = $ZLT[$cnt] ;
			$t_f = $ZTF[$cnt] ;
			$t_t = $ZTT[$cnt] ;
			$backlink = "on" ;
			intl_add_doit ( $c ) ;
			$r .= "<li>$l_f:$t_f &harr; $l_t:$t_t</li>\n" ;
			}
		}
	$r .= "</OL>\n" ;
	return $r ;
	}

function intl_zoom ( $c ) {
	global $doit ;
	global $wgLanguageCode , $wgLang ;
	global $xl , $xt ;
	if ( isset ( $doit ) ) return intl_zoom2 ( $c ) ;
	$ln = $wgLang->getLanguageNames();
	$list = array() ;
	$found = array () ;
	$r = "<h2>Interlinked articles group</h2>\n" ;
	$initial->lang = $xl ;
	$initial->title = urldecode ( $xt ) ;
	$initial->new = true ;
	$found[] = $initial ;

	do_zoom ( $found , $list , $c ) ;

	$involved = array() ;
	foreach ( $found AS $f )
		$involved[] = $ln[$f->lang].":".getArticleLink ( $f->title , $f->lang ) ;
	$r .= "Involved are ".implode ( ", " , $involved )."<br>\n" ;

	$r .= displayLinks ( $list ) ;

	$list2 = getMissingLinks ( $found , $list ) ;

	if ( count ( $list2 ) > 0 ) {
		$r .= "<h3>Missing links</h3>\n" ;
		$opt->showdel = false ;
		$opt->display = "Create" ;
		$ak = array_keys ( $list2 ) ;
		$cnt = 1 ;
		foreach ( $ak AS $a ) {
			$b = $list2[$a] ;
			$z = "<input type=checkbox name='ZCB[{$cnt}]' checked>\n" ;
			$z.="<input type=hidden name='ZLF[{$cnt}]' value='{$b->lang_from}'>\n";
			$z.="<input type=hidden name='ZLT[{$cnt}]' value='{$b->lang_to}'>\n";
			$z.="<input type=hidden name='ZTF[{$cnt}]' value='{$b->title_from}'>\n";
			$z.="<input type=hidden name='ZTT[{$cnt}]' value='{$b->title_to}'>\n";
			$list2[$a]->display = $z ;
			$cnt++ ;
			}
		$r .= "<FORM method=post>\n" ;
		$r .= displayLinks ( $list2 , $opt ) ;
		$r .= "<INPUT type=submit name=doit value='Create selected links'>\n" ;
		$r .= " (Note: This is still buggy, I don't know why...)" ;
		$r .= "</FORM>\n" ;
		}

	return $r ;
	}

function intl_delete ( $c ) {
	global $wgLang ;
	global $xt , $xl , $yl , $back ;
	$title = urldecode ( $xt ) ;
	$ln = $wgLang->getLanguageNames();

	$sql = "DELETE FROM ilinks WHERE 
lang_from='{$xl}' AND
lang_to='{$yl}' AND
title_from='{$title}'
" ;
	$res = mysql_query ( $sql , $c ) ;

	$r = "<h2>Deletion</h2>" ;
	$r .= "The link from ".$ln[$xl].":".$title." to ".$ln[$yl]." has been deleted.<br>" ;

	appendRecentChanges ( "- ".$ln[$xl].":".getArticleLink($title,$xl)." &rarr;" ) ;

	# Backlink?
	if ( $back != "yes" ) return $r ;

	$sql = "DELETE FROM ilinks WHERE 
lang_to='{$xl}' AND
lang_from='{$yl}' AND
title_to='{$title}'
" ;
	$res = mysql_query ( $sql , $c ) ;

	appendRecentChanges ( "- &rarr;".$ln[$xl].":".getArticleLink($title,$xl) ) ;

	$r .= "As was the backlink.<br>" ;
	return $r ;
	}
?>
