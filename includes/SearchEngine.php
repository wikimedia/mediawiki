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
		global $wgDBmysql4;
		$lc = SearchEngine::legalSearchChars() . "()";
		if( $wgDBmysql4 ) $lc .= "\"~<>*+-";
		$this->mUsertext = trim( preg_replace( "/[^{$lc}]/", " ", $text ) );
		$this->mSearchterms = array();
	}

	function queryNamespaces()
	{
		$namespaces = implode( ",", $this->namespacesToSearch );
		if ($namespaces == "") {
			$namespaces = "0";
		}
		return "AND cur_namespace IN (" . $namespaces . ")";
		#return "1";
	}

	function searchRedirects()
	{
		if ( $this->doSearchRedirects ) return "";
		return "AND cur_is_redirect=0 ";
	}

	/* private */ function initNamespaceCheckbox( $i )
	{
		global $wgUser, $wgNamespacesToBeSearchedDefault;
		

		if ($wgUser->getID()) {
			// User is logged in so we retrieve his default namespaces
			return $wgUser->getOption( "searchNs".$i );
		}
		else {	
			// User is not logged in so we give him the global default namespaces
			return $wgNamespacesToBeSearchedDefault[ $i ];
		}
	}

	# Display the "power search" footer. Does not actually perform the search, 
	# that is done by showResults()
	function powersearch()
	{
		global $wgUser, $wgOut, $wgLang, $wgTitle;

		$search			= $_REQUEST['search'];
		$searchx		= $_REQUEST['searchx'];
		$listredirs		= $_REQUEST['redirs'];
		
		$ret = wfMsg("powersearchtext"); # Text to be returned
		$tempText = ""; # Temporary text, for substitution into $ret	

		# Do namespace checkboxes
		$namespaces = $wgLang->getNamespaces();
		foreach ( $namespaces as $i => $namespace ) {
			# Skip virtual namespaces
			if ( $i < 0 ) {
				continue;
			}

			$formVar = "ns$i";

			# Initialise checkboxValues, either from defaults or from 
			# a previous invocation
			if ( !isset( $searchx ) ) {
				$checkboxValue = $this->initNamespaceCheckbox( $i );
			} else {
				$checkboxValue = $_REQUEST[$formVar];
			}

			$checked = "";
			if ( $checkboxValue == 1 ) {
				$checked = " checked";
				$this->addtoquery["ns{$i}"] = 1;
				array_push( $this->namespacesToSearch, $i );
			}
			$name = str_replace( "_", " ", $namespaces[$i] );
			if ( "" == $name ) { 
				$name = wfMsg( "blanknamespace" ); 
			}

			if ( $tempText !== "" ) { 
				$tempText .= " "; 
			}
			$tempText .= "<input type=checkbox value=\"1\" name=\"" .
			  "ns{$i}\"{$checked}>{$name}\n";
		}
		$ret = str_replace ( "$1", $tempText, $ret );

		# List redirects checkbox

		$checked = "";
		if ( $listredirs == 1 ) {
			$this->addtoquery["redirs"] = 1;
			$checked = " checked";
		}
		$tempText = "<input type=checkbox value=1 name=\"redirs\"{$checked}>\n";
		$ret = str_replace( "$2", $tempText, $ret );

		# Search field

		$tempText = "<input type=text name=\"search\" value=\"" .
			htmlspecialchars( $search ) ."\" width=80>\n";
        $ret = str_replace( "$3", $tempText, $ret );

		# Searchx button

		$tempText = "<input type=submit name=\"searchx\" value=\"" .
		  wfMsg("powersearch") . "\">\n";
		$ret = str_replace( "$9", $tempText, $ret );

		$ret = "<br><br>\n<form id=\"powersearch\" method=\"get\" " .
		  "action=\"" . wfLocalUrl( "" ) . "\">\n{$ret}\n</form>\n";

		if ( isset ( $searchx ) ) {
			if ( ! $listredirs ) { 
				$this->doSearchRedirects = false; 
			}
		}
		return $ret;
	}

	# Perform the search and construct the results page
	function showResults()
	{
		global $wgUser, $wgTitle, $wgOut, $wgLang, $wgDisableTextSearch;
		global $wgInputEncoding;
		$fname = "SearchEngine::showResults";

		$search		= $_REQUEST['search'];

		$powersearch = $this->powersearch(); /* Need side-effects here? */

		$wgOut->setPageTitle( wfMsg( "searchresults" ) );
		$q = wfMsg( "searchquery", htmlspecialchars( $this->mUsertext ) );
		$wgOut->setSubtitle( $q );
		$wgOut->setArticleFlag( false );
		$wgOut->setRobotpolicy( "noindex,nofollow" );

		$sk = $wgUser->getSkin();
		$text = wfMsg( "searchresulttext", $sk->makeKnownLink(
		  wfMsg( "searchhelppage" ), wfMsg( "searchingwikipedia" ) ) );
		$wgOut->addHTML( $text );

		$this->parseQuery();
		if ( "" == $this->mTitlecond || "" == $this->mTextcond ) {
			$wgOut->addHTML( "<h2>" . wfMsg( "badquery" ) . "</h2>\n" .
			  "<p>" . wfMsg( "badquerytext" ) );
			return;
		}
		list( $limit, $offset ) = wfCheckLimits( 20, "searchlimit" );

		$searchnamespaces = $this->queryNamespaces();
		$redircond = $this->searchRedirects();

		$sql = "SELECT cur_id,cur_namespace,cur_title," .
		  "cur_text FROM cur,searchindex " .
		  "WHERE cur_id=si_page AND {$this->mTitlecond} " .
		  "{$searchnamespaces} {$redircond}" .
		  "LIMIT {$offset}, {$limit}";
		$res1 = wfQuery( $sql, DB_READ, $fname );
		$num = wfNumRows($res1);

		if ( $wgDisableTextSearch ) {
			$wgOut->addHTML( wfMsg( "searchdisabled", $search, $wgInputEncoding ) );
		} else {
			$sk = $wgUser->getSkin();
			$text = wfMsg( "searchresulttext", $sk->makeKnownLink(
			  wfMsg( "searchhelppage" ), wfMsg( "searchingwikipedia" ) ) );
			$wgOut->addHTML( $text );
	
			$this->parseQuery();
			if ( "" == $this->mTitlecond || "" == $this->mTextcond ) {
				$wgOut->addHTML( "<h2>" . wfMsg( "badquery" ) . "</h2>\n" .
				  "<p>" . wfMsg( "badquerytext" ) );
				return;
			}
			list( $limit, $offset ) = wfCheckLimits( 20, "searchlimit" );
	
			$searchnamespaces = $this->queryNamespaces();
			$redircond = $this->searchRedirects();
	
			$sql = "SELECT cur_id,cur_namespace,cur_title," .
			  "cur_text FROM cur,searchindex " .
			  "WHERE cur_id=si_page AND {$this->mTitlecond} " .
			  "{$searchnamespaces} {$redircond}" .
			  "LIMIT {$offset}, {$limit}";
			$res1 = wfQuery( $sql, DB_READ, $fname );
			$num = wfNumRows($res1);
	
			$sql = "SELECT cur_id,cur_namespace,cur_title," .
			  "cur_text FROM cur,searchindex " .
			  "WHERE cur_id=si_page AND {$this->mTextcond} " .
			  "{$searchnamespaces} {$redircond} " .
			  "LIMIT {$offset}, {$limit}";
			$res2 = wfQuery( $sql, DB_READ, $fname );
			$num = $num + wfNumRows($res2);

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
		}
		
		if ( $wgDisableTextSearch ) {
			$wgOut->addHTML( wfMsg( "searchdisabled", $search, $wgInputEncoding ) );
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
			if ( ! $foundsome ) {
				$wgOut->addHTML( "<p>" . wfMsg( "nonefound" ) . "\n" );
			}
			$wgOut->addHTML( "<p>{$sl}\n" );
			$wgOut->addHTML( $powersearch );
		}
	}

	function legalSearchChars()
	{
		$lc = "A-Za-z_'0-9\\x80-\\xFF\\-";
		return $lc;
	}

	function parseQuery()
	{
		global $wgDBminWordLen, $wgLang, $wgDBmysql4;

		if( $wgDBmysql4 ) {
			# Use cleaner boolean search if available
			return $this->parseQuery4();
		}

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

		$this->mTitlecond = "(" . str_replace( "##field##",
		  "si_title", $cond ) . " )";

		$this->mTextcond = "(" . str_replace( "##field##",
		  "si_text", $cond ) . " AND (cur_is_redirect=0) )";
	}
	
	function parseQuery4()
	{
		# FIXME: not ready yet! Do not use.
		
		global $wgLang;
		$lc = SearchEngine::legalSearchChars();
		#$q = preg_replace( "/([+-]?)([$lc]+)/e",
		#	"\"$1\" . \$wgLang->stripForSearch(\"$2\")",
		#	$this->mUsertext );
		
		$q = $this->mUsertext;
		$qq = wfStrencode( $wgLang->stripForSearch( $q ) );
		$this->mSearchterms = preg_split( '/\s+/', $q );
		$this->mTitlecond = " MATCH(si_title) AGAINST('$qq' IN BOOLEAN MODE)";
		$this->mTextcond = " (MATCH(si_text) AGAINST('$qq' IN BOOLEAN MODE) AND cur_is_redirect=0)";
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
		global $wgOut;
		$fname = "SearchEngine::goResult";
		
		$search		= $_REQUEST['search'];

		# First try to go to page as entered		
		#
		$t = Title::newFromText( $search );

		if ( 0 != $t->getArticleID() ) {
			$wgOut->redirect( wfLocalUrl( $t->getPrefixedURL() ) );
			return;
		}

		# Now try all lower case (i.e. first letter capitalized)
		#
		$t = Title::newFromText( strtolower( $search ) );
		if ( 0 != $t->getArticleID() ) {
			$wgOut->redirect( wfLocalUrl( $t->getPrefixedURL() ) );
			return;
		}

		# Now try capitalized string
		#
		$t = Title::newFromText( ucwords( strtolower( $search ) ) );
		if ( 0 != $t->getArticleID() ) {
			$wgOut->redirect( wfLocalUrl( $t->getPrefixedURL() ) );
			return;
		}

		# Now try all upper case
		#
		$t = Title::newFromText( strtoupper( $search ) );
		if ( 0 != $t->getArticleID() ) {
			$wgOut->redirect( wfLocalUrl( $t->getPrefixedURL() ) );
			return;
		}

		# Try a near match
		#
		if( !$wgDisableTextSearch ) {
			$this->parseQuery();										
			$sql = "SELECT cur_id,cur_title,cur_namespace,si_page FROM cur,searchindex " .
			  "WHERE cur_id=si_page AND {$this->mTitlecond} ORDER BY cur_namespace LIMIT 1";
	
			if ( "" != $this->mTitlecond ) {
				$res = wfQuery( $sql, DB_READ, $fname );
			} 				
			if ( isset( $res ) && 0 != wfNumRows( $res ) ) {
		 		$s = wfFetchObject( $res );
	
				$t = Title::makeTitle( $s->cur_namespace, $s->cur_title );
				$wgOut->redirect( wfLocalUrl( $t->getPrefixedURL() ) );
				return;
			}
		}
		$wgOut->addHTML( wfMsg("nogomatch", 
		  htmlspecialchars( wfLocalUrl( ucfirst($this->mUsertext), "action=edit") ) )
		  . "\n<p>" );
		$this->showResults();
	}
}

?>
