<?php
# See search.doc

class SearchEngine {
	/* private */ var $mUsertext, $mSearchterms;
	/* private */ var $mTitlecond, $mTextcond;

	var $doSearchRedirects = true;
	var $addtoquery = array();
	var $namespacesToSearch = array();
	var $alternateTitle;
	var $all_titles = false;

	function SearchEngine( $text )
	{
		# We display the query, so let's strip it for safety
		#
		global $wgDBmysql4;
		$lc = SearchEngine::legalSearchChars() . "()";
		if( $wgDBmysql4 ) $lc .= "\"~<>*+-";
		$this->mUsertext = trim( preg_replace( "/[^{$lc}]/", " ", $text ) );
		$this->mSearchterms = array();
		$this->mStrictMatching = true; # Google-style, add '+' on all terms
	}

	function queryNamespaces()
	{
		$namespaces = implode( ",", $this->namespacesToSearch );
		if ($namespaces == "") {
			$namespaces = "0";
		}
		return "AND cur_namespace IN (" . $namespaces . ")";
	}

	function searchRedirects()
	{
		if ( $this->doSearchRedirects ) {
			return "";
		} else {
			return "AND cur_is_redirect=0 ";
		}
	}

	/* private */ function initNamespaceCheckbox( $i )
	{
		global $wgUser, $wgNamespacesToBeSearchedDefault;
		
		if ($wgUser->getID()) {
			// User is logged in so we retrieve his default namespaces
			return $wgUser->getOption( "searchNs".$i );
		} else {
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

		if( isset( $_REQUEST["searchx"] ) ) {
			$this->addtoquery["searchx"] = "1";
		}
		
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

	function setupPage() {
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( "searchresults" ) );
		$q = wfMsg( "searchquery", htmlspecialchars( $this->mUsertext ) );
		$wgOut->setSubtitle( $q );
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
	}

	# Perform the search and construct the results page
	function showResults()
	{
		global $wgUser, $wgTitle, $wgOut, $wgLang, $wgDisableTextSearch;
		global $wgInputEncoding;
		$fname = "SearchEngine::showResults";

		$search = $_REQUEST['search'];

		$powersearch = $this->powersearch(); /* Need side-effects here? */

		$this->setupPage();
		
		$sk = $wgUser->getSkin();
		$header = wfMsg( "searchresulttext", $sk->makeKnownLink(
		  wfMsg( "searchhelppage" ), wfMsg( "searchingwikipedia" ) ) );
		$wgOut->addHTML( $header );

		$this->parseQuery();
		if ( "" == $this->mTitlecond || "" == $this->mTextcond ) {
			$wgOut->addHTML( "<h2>" . wfMsg( "badquery" ) . "</h2>\n" .
			  "<p>" . wfMsg( "badquerytext" ) );
			return;
		}
		list( $limit, $offset ) = wfCheckLimits( 20, "searchlimit" );

		$searchnamespaces = $this->queryNamespaces();
		$redircond = $this->searchRedirects();

		if ( $wgDisableTextSearch ) {
			$wgOut->addHTML( wfMsg( "searchdisabled" ) );
			$wgOut->addHTML( wfMsg( "googlesearch", htmlspecialchars( $search ), $GLOBALS['wgInputEncoding'] ) );
		} else {
			$sql = "SELECT cur_id,cur_namespace,cur_title," .
			  "cur_text FROM cur,searchindex " .
			  "WHERE cur_id=si_page AND {$this->mTitlecond} " .
			  "{$searchnamespaces} {$redircond}" .
			  "LIMIT {$offset}, {$limit}";
			$res1 = wfQuery( $sql, DB_READ, $fname );
			$num = wfNumRows($res1);

			$sk = $wgUser->getSkin();
			$text = "";

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
		global $wgLang;
		$lc = SearchEngine::legalSearchChars();
		$searchon = "";
		$this->mSearchterms = array();

		# FIXME: This doesn't handle parenthetical expressions.
		if( preg_match_all( '/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/',
			  $this->mUsertext, $m, PREG_SET_ORDER ) ) {
			foreach( $m as $terms ) {
				if( $searchon !== "" ) $searchon .= " ";
				if( $this->mStrictMatching && ($terms[1] == "") ) {
					$terms[1] = "+";
				}
				$searchon .= $terms[1] . $wgLang->stripForSearch( $terms[2] );
				if( $terms[3] ) {
					$regexp = preg_quote( $terms[3] );
					if( $terms[4] ) $regexp .= "[0-9A-Za-z_]+";
				} else {
					$regexp = preg_quote( str_replace( '"', '', $terms[2] ) );
				}
				$this->mSearchterms[] = $regexp;
			}
			wfDebug( "Would search with '$searchon'\n" );
			wfDebug( "Match with /\b" . implode( '\b|\b', $this->mSearchterms ) . "\b/\n" );
		} else {
			wfDebug( "Can't understand search query '$this->mUsertext'\n" );
		}
		
		$searchon = wfStrencode( $searchon );
		$this->mTitlecond = " MATCH(si_title) AGAINST('$searchon' IN BOOLEAN MODE)";
		$this->mTextcond = " (MATCH(si_text) AGAINST('$searchon' IN BOOLEAN MODE) AND cur_is_redirect=0)";
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
		$size = wfMsg( "nbytes", strlen( $row->cur_text ) );
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
		global $wgOut, $wgDisableTextSearch;
		$fname = "SearchEngine::goResult";
		
		$search		= trim( $_REQUEST['search'] );
 		# Entering an IP address goes to the contributions page
		if ( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $search ) ) {
			$title = Title::makeTitle( NS_SPECIAL, "Contributions" );
			$wgOut->redirect( wfLocalUrl( $title->getPrefixedURL(), "target=$search" ) );
			return;
		}

		# First try to go to page as entered.
		#
		$t = Title::newFromText( $search );

		# If the string cannot be used to create a title
		if( false == $t ){ 
			$this->showResults();
			return;
		}

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
		$wgOut->addHTML( wfMsg("nogomatch", 
			htmlspecialchars( wfLocalUrl( ucfirst($this->mUsertext), "action=edit") ) )
			. "\n<p>" );

		# Try a fuzzy title search
		$anyhit = false;
		global $wgDisableFuzzySearch;
		if(! $wgDisableFuzzySearch ){
			foreach( array(NS_MAIN, NS_WP, NS_USER, NS_IMAGE, NS_MEDIAWIKI) as $namespace){
				$anyhit |= SearchEngine::doFuzzyTitleSearch( $search, $namespace );
			}
		}
		
		if( ! $anyhit ){
			return $this->showResults();
		}
	}

	/* static */ function doFuzzyTitleSearch( $search, $namespace ){
		global $wgLang, $wgOut;
		
		$this->setupPage();
		
		$sstr = ucfirst($search);
		$sstr = str_replace(" ", "_", $sstr);
		$fuzzymatches = SearchEngine::fuzzyTitles( $sstr, $namespace );
		$fuzzymatches = array_slice($fuzzymatches, 0, 10);
		$slen = strlen( $search );
		$wikitext = "";
		foreach($fuzzymatches as $res){
			$t = str_replace("_", " ", $res[1]);
			$tfull = $wgLang->getNsText( $namespace ) . ":$t|$t";
			if( $namespace == NS_MAIN )
				$tfull = "$t";
			$distance = $res[0];
			$closeness = (strlen( $search ) - $distance) / strlen( $search );
			$percent = intval( $closeness * 100 ) . "%";
			$stars = str_repeat("*", ceil(5 * $closeness) );
			$wikitext .= "* [[$tfull]] $percent ($stars)\n";	
		}
		if( $wikitext ){
			if( $namespace != NS_MAIN )
				$wikitext = "=== " . $wgLang->getNsText( $namespace ) . " ===\n" . $wikitext;
			$wgOut->addWikiText( $wikitext );
			return true;
		}
		return false;
	}

	/* static */ function fuzzyTitles( $sstr, $namespace = NS_MAIN ){
		$span = 0.10; // weed on title length before doing levenshtein.
		$tolerance = 0.35; // allowed percentage of erronous characters
		$slen = strlen($sstr);
		$tolerance_count = ceil($tolerance * $slen);
		$spanabs = ceil($slen * (1 + $span)) - $slen;
		# print "Word: $sstr, len = $slen, range = [$min, $max], tolerance_count = $tolerance_count<BR>\n";
		$result = array();
		for( $i=0; $i <= $spanabs; $i++ ){
			$titles = SearchEngine::getTitlesByLength( $slen + $i, $namespace );
			if( $i != 0)
				$titles = array_merge($titles, SearchEngine::getTitlesByLength( $slen - $i, $namespace ) );
			foreach($titles as $t){
				$d = levenshtein($sstr, $t);
				if($d < $tolerance_count) 
					$result[] = array($d, $t);
				$cnt++;
			}
	        }
		usort($result, "SearchEngine_pcmp");
		return $result;
	}

	/* static */ function getTitlesByLength($aLength, $aNamespace = 0){
		global $wgMemc, $wgDBname;

		// to avoid multiple costly SELECTs in case of no memcached
		if( $this->all_titles ){ 
			if( isset( $this->all_titles[$aLength][$aNamespace] ) ){
				return $this->all_titles[$aLength][$aNamespace];
			} else {
				return array();
			}
		}

		$mkey = "$wgDBname:titlesbylength:$aLength:$aNamespace";
		$mkeyts = "$wgDBname:titlesbylength:createtime";
		$ts = $wgMemc->get( $mkeyts );
		$result = $wgMemc->get( $mkey );

		if( time() - $ts < 3600 ){
			// note: in case of insufficient memcached space, we return
			// an empty list instead of starting to hit the DB.
			return is_array( $result ) ? $result : array();
		}

		$wgMemc->set( $mkeyts, time() );

		$res = wfQuery("SELECT cur_title, cur_namespace FROM cur", DB_READ);
		$titles = array(); // length, ns, [titles]
		while( $obj = wfFetchObject( $res ) ){
			$title = $obj->cur_title;
			$ns = $obj->cur_namespace;
			$len = strlen( $title );
			$titles[$len][$ns][] = $title;
		} 
		foreach($titles as $length => $length_arr){
			foreach($length_arr as $ns => $title_arr){
				$mkey = "$wgDBname:titlesbylength:$length:$ns";
				$wgMemc->set( $mkey, $title_arr, 3600 * 24 );
			}
		}
		$this->all_titles = $titles;
		if( isset( $titles[$aLength][$aNamespace] ) )
			return $titles[$aLength][$aNamespace];
		else
			return array();
	}
}

/* private static */ function SearchEngine_pcmp($a, $b){ return $a[0] - $b[0]; }

?>
