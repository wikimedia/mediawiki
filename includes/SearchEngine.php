<?
# See search.doc

class SearchEngine {
	/* private */ var $mUsertext, $mSearchterms;
	/* private */ var $mTitlecond, $mTextcond;

	var $doSearchRedirects = true;
	var $addtoquery = array();
	var $namespacesToSearch = array();
	var $alternateTitle;

	function SearchEngine( $text )
	{
		# We display the query, so let's strip it for safety
		#
		$lc = SearchEngine::legalSearchChars() . "()";
		$this->mUsertext = trim( preg_replace( "/[^{$lc}]/", " ", $text ) );
		$this->mSearchterms = array();
	}

	function queryNamespaces()
	{
		return "cur_namespace IN (" . implode( ",", $this->namespacesToSearch ) . ")";
		#return "1";
	}

	function searchRedirects()
	{
		if ( $this->doSearchRedirects ) return "";
		return "AND cur_is_redirect=0 ";
	}

	function powersearch()
	{
		global $wgUser, $wgOut, $wgLang, $wgTitle;
		$nscb = array();

		$search			= $_REQUEST['search'];
		$searchx		= $_REQUEST['searchx'];
		$listredirs		= $_REQUEST['redirs'];
		$nscb[0]		= $_REQUEST['ns0'];
		$nscb[1]		= $_REQUEST['ns1'];
		$nscb[2]		= $_REQUEST['ns2'];
		$nscb[3]		= $_REQUEST['ns3'];
		$nscb[4]		= $_REQUEST['ns4'];
		$nscb[5]		= $_REQUEST['ns5'];
		$nscb[6]		= $_REQUEST['ns6'];
		$nscb[7]		= $_REQUEST['ns7'];

		if ( ! isset ( $searchx ) ) {	/* First time here */
			$nscb[0] = $listredirs = 1;	/* All others should be unset */
		}
		$this->checkboxes["searchx"] = 1;
		$ret = wfMsg("powersearchtext");

		# Determine namespace checkboxes

		$ns = $wgLang->getNamespaces();
		array_shift( $ns ); /* Skip "Special" */

		$r1 = "";
		for ( $i = 0; $i < count( $ns ); ++$i ) {
			$checked = "";
			if ( $nscb[$i] == 1 ) {
				$checked = " checked";
				$this->addtoquery["ns{$i}"] = 1;
				array_push( $this->namespacesToSearch, $i );
			}
			$name = str_replace( "_", " ", $ns[$i] );
			if ( "" == $name ) { $name = "(Main)"; }

			if ( 0 != $i ) { $r1 .= " "; }
			$r1 .= "<input type=checkbox value=\"1\" name=\"" .
			  "ns{$i}\"{$checked}>{$name}\n";
		}
		$ret = str_replace ( "$1", $r1, $ret );

		# List redirects checkbox

		$checked = "";
		if ( $listredirs == 1 ) {
			$this->addtoquery["redirs"] = 1;
			$checked = " checked";
		}
		$r2 = "<input type=checkbox value=1 name=\"redirs\"{$checked}>\n";
		$ret = str_replace( "$2", $r2, $ret );

		# Search field

		$r3 = "<input type=text name=\"search\" value=\"" .
			htmlspecialchars( $search ) ."\" width=80>\n";
        $ret = str_replace( "$3", $r3, $ret );

		# Searchx button

		$r9 = "<input type=submit name=\"searchx\" value=\"" .
		  wfMsg("powersearch") . "\">\n";
		$ret = str_replace( "$9", $r9, $ret );

		$ret = "<br><br>\n<form id=\"powersearch\" method=\"get\" " .
		  "action=\"" . wfLocalUrl( "" ) . "\">\n{$ret}\n</form>\n";

		if ( isset ( $searchx ) ) {
			if ( ! $listredirs ) { $this->doSearchRedirects = false; }
		}
		return $ret;
	}

	function showResults()
	{
		global $wgUser, $wgTitle, $wgOut, $wgLang, $wgDisableTextSearch;
		$fname = "SearchEngine::showResults";

		$offset		= $_REQUEST['offset'];
		$limit		= $_REQUEST['limit'];
		$search		= $_REQUEST['search'];

		$powersearch = $this->powersearch(); /* Need side-effects here? */

		$wgOut->setPageTitle( wfMsg( "searchresults" ) );
		$q = str_replace( "$1", $this->mUsertext,
		  wfMsg( "searchquery" ) );
		$wgOut->setSubtitle( $q );
		$wgOut->setArticleFlag( false );
		$wgOut->setRobotpolicy( "noindex,nofollow" );

		$sk = $wgUser->getSkin();
		$text = str_replace( "$1", $sk->makeKnownLink(
		  wfMsg( "searchhelppage" ), wfMsg( "searchingwikipedia" ) ),
		  wfMsg( "searchresulttext" ) );
		$wgOut->addHTML( $text );

		$this->parseQuery();
		if ( "" == $this->mTitlecond || "" == $this->mTextcond ) {
			$wgOut->addHTML( "<h2>" . wfMsg( "badquery" ) . "</h2>\n" .
			  "<p>" . wfMsg( "badquerytext" ) );
			return;
		}
		if ( ! isset( $limit ) ) {
			$limit = $wgUser->getOption( "searchlimit" );
			if ( ! $limit ) { $limit = 20; }
		}
		if ( ! $offset ) { $offset = 0; }

		$searchnamespaces = $this->queryNamespaces();
		$redircond = $this->searchRedirects();

		$sql = "SELECT cur_id,cur_namespace,cur_title," .
		  "cur_text FROM cur,searchindex " .
		  "WHERE cur_id=si_page AND {$this->mTitlecond} " .
		  "AND {$searchnamespaces} {$redircond}" .
		  "LIMIT {$offset}, {$limit}";
		$res1 = wfQuery( $sql, $fname );
		$num = wfNumRows($res1);

		if ( $wgDisableTextSearch ) {
			$res2 = 0;
		} else {
			$sql = "SELECT cur_id,cur_namespace,cur_title," .
			  "cur_text FROM cur,searchindex " .
			  "WHERE cur_id=si_page AND {$this->mTextcond} " .
			  "AND {$searchnamespaces} {$redircond} " .
			  "LIMIT {$offset}, {$limit}";
			$res2 = wfQuery( $sql, $fname );
			$num = $num + wfNumRows($res2);
		}

                if ( $num == $limit ) {
		  $top = wfShowingResults( $offset, $limit);
		} else {
		  $top = wfShowingResultsNum( $offset, $limit, $num );
		}
		$wgOut->addHTML( "<p>{$top}\n" );

		# For powersearch

		$a2l = "" ;
		$akk = array_keys( $this->addtoquery ) ;
		foreach ( $akk AS $ak ) {
			$a2l .= "&{$ak}={$this->addtoquery[$ak]}" ;
		}

		$sl = wfViewPrevNext( $offset, $limit, "",
		  "search=" . wfUrlencode( $this->mUsertext ) . $a2l );
		$wgOut->addHTML( "<br>{$sl}\n" );

		$foundsome = false;

		if ( 0 == wfNumRows( $res1 ) ) {
			$wgOut->addHTML( "<h2>" . wfMsg( "notitlematches" ) .
			  "</h2>\n" );
		} else {
			$foundsome = true;
			$off = $offset + 1;
			$wgOut->addHTML( "<h2>" . wfMsg( "titlematches" ) .
			  "</h2>\n<ol start='{$off}'>" );

			while ( $row = wfFetchObject( $res1 ) ) {
				$this->showHit( $row );
			}
			wfFreeResult( $res1 );
			$wgOut->addHTML( "</ol>\n" );
		}

		if ( $wgDisableTextSearch ) {
			$wgOut->addHTML( str_replace( "$1",
			  htmlspecialchars( $search ), wfMsg( "searchdisabled" ) ) );
		} else {
			if ( 0 == wfNumRows( $res2 ) ) {
				$wgOut->addHTML( "<h2>" . wfMsg( "notextmatches" ) .
				  "</h2>\n" );
			} else {
				$foundsome = true;
				$off = $offset + 1;
				$wgOut->addHTML( "<h2>" . wfMsg( "textmatches" ) . "</h2>\n" .
				  "<ol start='{$off}'>" );
				while ( $row = wfFetchObject( $res2 ) ) {
					$this->showHit( $row );
				}
				wfFreeResult( $res2 );
				$wgOut->addHTML( "</ol>\n" );
			}
		}
		if ( ! $foundsome ) {
			$wgOut->addHTML( "<p>" . wfMsg( "nonefound" ) . "\n" );
		}
		$wgOut->addHTML( "<p>{$sl}\n" );
		$wgOut->addHTML( $powersearch );
	}

	function legalSearchChars()
	{
		$lc = "A-Za-z_'0-9\\x80-\\xFF\\-";
		return $lc;
	}

	function parseQuery()
	{
		global $wgDBminWordLen, $wgLang;

		$lc = SearchEngine::legalSearchChars() . "()";
		$q = preg_replace( "/([()])/", " \\1 ", $this->mUsertext );
		$q = preg_replace( "/\\s+/", " ", $q );
		$w = explode( " ", strtolower( trim( $q ) ) );

		$last = $cond = "";
		foreach ( $w as $word ) {
			$word = $wgLang->stripForSearch( $word );
			if ( "and" == $word || "or" == $word || "not" == $word
			  || "(" == $word || ")" == $word ) {
				$cond .= " " . strtoupper( $word );
				$last = "";
			} else if ( strlen( $word ) < $wgDBminWordLen ) {
				continue;
			} else if ( FulltextStoplist::inList( $word ) ) {
				continue;
			} else {
				if ( "" != $last ) { $cond .= " AND"; }
				$cond .= " (MATCH (##field##) AGAINST ('" .
				  wfStrencode( $word ). "'))";
				$last = $word;
				array_push( $this->mSearchterms, "\\b" . $word . "\\b" );
			}
		}
		if ( 0 == count( $this->mSearchterms ) ) { return; }

		# To disable boolean:
		# $cond = "MATCH (##field##) AGAINST('" . wfStrencode( $q ) . "')";

		$this->mTitlecond = "(" . str_replace( "##field##",
		  "si_title", $cond ) . " )";

		$this->mTextcond = "(" . str_replace( "##field##",
		  "si_text", $cond ) . " AND (cur_is_redirect=0) )";
	}

	function showHit( $row )
	{
		global $wgUser, $wgOut;

		$t = Title::makeName( $row->cur_namespace, $row->cur_title );
		$sk = $wgUser->getSkin();

		$contextlines = $wgUser->getOption( "contextlines" );
		if ( "" == $contextlines ) { $contextlines = 5; }
		$contextchars = $wgUser->getOption( "contextchars" );
		if ( "" == $contextchars ) { $contextchars = 50; }

		$link = $sk->makeKnownLink( $t, "" );
		$size = str_replace( "$1", strlen( $row->cur_text ), WfMsg( "nbytes" ) );
		$wgOut->addHTML( "<li>{$link} ({$size})" );

		$lines = explode( "\n", $row->cur_text );
		$pat1 = "/(.*)(" . implode( "|", $this->mSearchterms ) . ")(.*)/i";
		$lineno = 0;

		foreach ( $lines as $line ) {
			if ( 0 == $contextlines ) { break; }
			--$contextlines;
			++$lineno;
			if ( ! preg_match( $pat1, $line, $m ) ) { continue; }

			$pre = $m[1];
			if ( 0 == $contextchars ) { $pre = "..."; }
			else {
				if ( strlen( $pre ) > $contextchars ) {
					$pre = "..." . substr( $pre, -$contextchars );
				}
			}
			$pre = wfEscapeHTML( $pre );

			if ( count( $m ) < 3 ) { $post = ""; }
			else { $post = $m[3]; }

			if ( 0 == $contextchars ) { $post = "..."; }
			else {
				if ( strlen( $post ) > $contextchars ) {
					$post = substr( $post, 0, $contextchars ) . "...";
				}
			}
			$post = wfEscapeHTML( $post );
			$found = wfEscapeHTML( $m[2] );

			$line = "{$pre}{$found}{$post}";
			$pat2 = "/(" . implode( "|", $this->mSearchterms ) . ")/i";
			$line = preg_replace( $pat2,
			  "<font color='red'>\\1</font>", $line );

			$wgOut->addHTML( "<br><small>{$lineno}: {$line}</small>\n" );
		}
		$wgOut->addHTML( "</li>\n" );
	}

	function goResult()
	{
		global $wgOut, $wgArticle, $wgTitle;
		$fname = "SearchEngine::goResult";
		
		$search		= $_REQUEST['search'];

		# First try to go to page as entered		
		#
		$wgArticle = new Article();
		$wgTitle = Title::newFromText( $search );

		if ( 0 != $wgArticle->getID() ) {
			$wgArticle->view();
			return;
		}

		# Now try all lower case (i.e. first letter capitalized)
		#
		$wgTitle = Title::newFromText( strtolower( $search ) );
		if ( 0 != $wgArticle->getID() ) {
			$wgArticle->view();
			return;
		}
		
		# Now try capitalized string
		#
		$wgTitle=Title::newFromText( ucwords( strtolower( $search ) ) );
		if ( 0 != $wgArticle->getID() ) {
			$wgArticle->view();
			return;
		}

		# Try a near match
		#
		$this->parseQuery();										
		$sql = "SELECT cur_id,cur_title,cur_namespace,si_page FROM cur,searchindex " .
		  "WHERE cur_id=si_page AND {$this->mTitlecond} LIMIT 1";

		if ( "" != $this->mTitlecond ) {
			$res = wfQuery( $sql, $fname );
		} 				
		if ( isset( $res ) && 0 != wfNumRows( $res ) ) {
	 		$s = wfFetchObject( $res );

			$wgTitle = Title::newFromDBkey( $s->cur_title );
			$wgTitle->setNamespace( $s->cur_namespace );
			$wgArticle->view();
			return;
		}
		$wgOut->addHTML( str_replace( "$1",
		  wfLocalUrl( ucfirst($this->mUsertext) . "&action=edit"),
		  wfMsg("nogomatch")) . "\n<p>" );
		$this->showResults();
	}
}

