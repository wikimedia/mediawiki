<?php
/**
 * Contain site class
 * See search.doc
 * @package MediaWiki
 */

/**
 *
 */
define( 'MW_SEARCH_OK', true );
define( 'MW_SEARCH_BAD_QUERY', false );

/**
 * @todo document
 * @package MediaWiki
 */
class SearchEngine {
	/* private */ var $rawText, $filteredText, $searchTerms;
	/* private */ var $titleCond, $textCond;

	var $doSearchRedirects = true;
	var $addToQuery = array();
	var $namespacesToSearch = array();
	var $alternateTitle;
	var $allTitles = false;

	function SearchEngine( $text ) {
		$this->rawText = trim( $text );

		# We display the query, so let's strip it for safety
		#
		global $wgDBmysql4;
		$lc = SearchEngine::legalSearchChars() . '()';
		if( $wgDBmysql4 ) {
			$lc .= "\"~<>*+-";
		}
		$this->filteredText = trim( preg_replace( "/[^{$lc}]/", " ", $text ) );
		$this->searchTerms = array();
		$this->strictMatching = true; # Google-style, add '+' on all terms

		$this->db =& wfGetDB( DB_SLAVE );
	}

	/**
	 * Return a partial WHERE clause to limit the search to the given namespaces
	 */
	function queryNamespaces() {
		$namespaces = implode( ',', $this->namespacesToSearch );
		if ($namespaces == '') {
			$namespaces = '0';
		}
		return "AND cur_namespace IN (" . $namespaces . ')';
	}

	/**
	 * Return a partial WHERE clause to include or exclude redirects from results
	 */
	function searchRedirects() {
		if ( $this->doSearchRedirects ) {
			return '';
		} else {
			return 'AND cur_is_redirect=0 ';
		}
	}

	/**
	 * @access private
	 */ function initNamespaceCheckbox( $i ) {
		global $wgUser, $wgNamespacesToBeSearchedDefault;
		
		if ($wgUser->getID()) {
			// User is logged in so we retrieve his default namespaces
			return $wgUser->getOption( 'searchNs'.$i );
		} else {
			// User is not logged in so we give him the global default namespaces
			return !empty($wgNamespacesToBeSearchedDefault[ $i ]);
		}
	}

	/**
	 * Display the "power search" footer. Does not actually perform the search, 
	 * that is done by showResults()
	 */
	function powersearch() {
		global $wgUser, $wgOut, $wgContLang, $wgTitle, $wgRequest;
		$sk =& $wgUser->getSkin();
		
		$search			= $this->rawText;
		$searchx		= $wgRequest->getVal( 'searchx' );
		$listredirs		= $wgRequest->getVal( 'redirs' );
		
		$ret = wfMsg('powersearchtext'); # Text to be returned
		$tempText = ''; # Temporary text, for substitution into $ret	

		if( isset( $_REQUEST['searchx'] ) ) {
			$this->addToQuery['searchx'] = '1';
		}
		
		# Do namespace checkboxes
		$namespaces = $wgContLang->getNamespaces();
		foreach ( $namespaces as $i => $namespace ) {
			# Skip virtual namespaces
			if ( $i < 0 ) {
				continue;
			}

			$formVar = 'ns'.$i;

			# Initialise checkboxValues, either from defaults or from 
			# a previous invocation
			if ( !isset( $searchx ) ) {
				$checkboxValue = $this->initNamespaceCheckbox( $i );
			} else {
				$checkboxValue = $wgRequest->getVal( $formVar );
			}

			$checked = '';
			if ( $checkboxValue == 1 ) {
				$checked = ' checked="checked"';
				$this->addToQuery['ns'.$i] = 1;
				array_push( $this->namespacesToSearch, $i );
			}
			$name = str_replace( '_', ' ', $namespaces[$i] );
			if ( '' == $name ) { 
				$name = wfMsg( 'blanknamespace' ); 
			}

			if ( $tempText !== '' ) { 
				$tempText .= ' '; 
			}
			$tempText .= "<input type='checkbox' value=\"1\" name=\"" .
			  "ns{$i}\"{$checked} />{$name}\n";
		}
		$ret = str_replace ( '$1', $tempText, $ret );

		# List redirects checkbox

		$checked = '';
		if ( $listredirs == 1 ) {
			$this->addToQuery['redirs'] = 1;
			$checked = ' checked="checked"';
		}
		$tempText = "<input type='checkbox' value='1' name=\"redirs\"{$checked} />\n";
		$ret = str_replace( '$2', $tempText, $ret );

		# Search field

		$tempText = "<input type='text' name=\"search\" value=\"" .
			htmlspecialchars( $search ) ."\" width=\"80\" />\n";
        $ret = str_replace( "$3", $tempText, $ret );

		# Searchx button

		$tempText = '<input type="submit" name="searchx" value="' .
		  wfMsg('powersearch') . "\" />\n";
		$ret = str_replace( '$9', $tempText, $ret );

		$action = $sk->escapeSearchLink();
		$ret = "<br /><br />\n<form id=\"powersearch\" method=\"get\" " .
		  "action=\"$action\">\n{$ret}\n</form>\n";

		if ( isset ( $searchx ) ) {
			if ( ! $listredirs ) { 
				$this->doSearchRedirects = false; 
			}
		}
		return $ret;
	}

	function setupPage() {
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( 'searchresults' ) );
		$wgOut->setSubtitle( wfMsg( 'searchquery', htmlspecialchars( $this->rawText ) ) );
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
	}

	/**
	 * Perform the search and construct the results page
	 */
	function showResults() {
		global $wgUser, $wgTitle, $wgOut, $wgLang;
		global $wgDisableTextSearch, $wgInputEncoding;
		$fname = 'SearchEngine::showResults';

		$search = $this->rawText;

		$powersearch = $this->powersearch(); /* Need side-effects here? */

		$this->setupPage();

		$sk = $wgUser->getSkin();
		$wgOut->addWikiText( wfMsg( 'searchresulttext' ) );

		if ( !$this->parseQuery() ) {
			$wgOut->addWikiText(
				'==' . wfMsg( 'badquery' ) . "==\n" .
				wfMsg( 'badquerytext' ) );
			return;
		}
		list( $limit, $offset ) = wfCheckLimits( 20, 'searchlimit' );
		
		if ( $wgDisableTextSearch ) {
			$wgOut->addHTML( wfMsg( 'searchdisabled' ) );
			$wgOut->addHTML( wfMsg( 'googlesearch',
				htmlspecialchars( $this->rawText ),
				htmlspecialchars( $wgInputEncoding ) ) );
			return;
		}

		$titleMatches = $this->getMatches( $this->titleCond, $limit, $offset );
		$textMatches = $this->getMatches( $this->textCond, $limit, $offset );

		$sk = $wgUser->getSkin();
		
		$num = count( $titleMatches ) + count( $textMatches );
		if ( $num >= $limit ) {
			$top = wfShowingResults( $offset, $limit );
		} else {
			$top = wfShowingResultsNum( $offset, $limit, $num );
		}
		$wgOut->addHTML( "<p>{$top}</p>\n" );

		# For powersearch
		$a2l = '';
		$akk = array_keys( $this->addToQuery );
		foreach ( $akk AS $ak ) {
			$a2l .= "&{$ak}={$this->addToQuery[$ak]}" ;
		}

		$prevnext = wfViewPrevNext( $offset, $limit, 'Special:Search',
		  'search=' . wfUrlencode( $this->filteredText ) . $a2l );
		$wgOut->addHTML( "<br />{$prevnext}\n" );

		$foundsome = $this->showMatches( $titleMatches, $offset, 'notitlematches', 'titlematches' )
				  || $this->showMatches( $textMatches,  $offset, 'notextmatches',  'textmatches'  );
		
		if ( !$foundsome ) {
			$wgOut->addWikiText( wfMsg( 'nonefound' ) );
		}
		$wgOut->addHTML( "<p>{$prevnext}</p>\n" );
		$wgOut->addHTML( $powersearch );
	}

	function legalSearchChars() {
		$lc = "A-Za-z_'0-9\\x80-\\xFF\\-";
		return $lc;
	}

	function parseQuery() {
		global $wgDBmysql4;
		if (strlen($this->filteredText) < 1)
			return MW_SEARCH_BAD_QUERY;

		if( $wgDBmysql4 ) {
			# Use cleaner boolean search if available
			return $this->parseQuery4();
		} else {
			# Fall back to ugly hack with multiple search clauses
			return $this->parseQuery3();
		}
	}
	
	function parseQuery3() {
		global $wgDBminWordLen, $wgContLang;

		# on non mysql4 database: get list of words we don't want to search for
		require_once( 'FulltextStoplist.php' );

		$lc = SearchEngine::legalSearchChars() . '()';
		$q = preg_replace( "/([()])/", " \\1 ", $this->filteredText );
		$q = preg_replace( "/\\s+/", " ", $q );
		$w = explode( ' ', trim( $q ) );

		$last = $cond = '';
		foreach ( $w as $word ) {
			$word = $wgContLang->stripForSearch( $word );
			if ( 'and' == $word || 'or' == $word || 'not' == $word
			  || '(' == $word || ')' == $word ) {
				$cond .= ' ' . strtoupper( $word );
				$last = '';
			} else if ( strlen( $word ) < $wgDBminWordLen ) {
				continue;
			} else if ( FulltextStoplist::inList( $word ) ) {
				continue;
			} else {
				if ( '' != $last ) { $cond .= ' AND'; }
				$cond .= " (MATCH (##field##) AGAINST ('" .
				  $this->db->strencode( $word ). "'))";
				$last = $word;
				array_push( $this->searchTerms, "\\b" . $word . "\\b" );
			}
		}
		if ( 0 == count( $this->searchTerms ) ) {
			return MW_SEARCH_BAD_QUERY;
		}

		$this->titleCond = '(' . str_replace( '##field##',
		  'si_title', $cond ) . ' )';

		$this->textCond = '(' . str_replace( '##field##',
		  'si_text', $cond ) . ' AND (cur_is_redirect=0) )';
		
		return MW_SEARCH_OK;
	}
	
	function parseQuery4() {
		global $wgContLang;
		$lc = SearchEngine::legalSearchChars();
		$searchon = '';
		$this->searchTerms = array();

		# FIXME: This doesn't handle parenthetical expressions.
		if( preg_match_all( '/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/',
			  $this->filteredText, $m, PREG_SET_ORDER ) ) {
			foreach( $m as $terms ) {
				if( $searchon !== '' ) $searchon .= ' ';
				if( $this->strictMatching && ($terms[1] == '') ) {
					$terms[1] = '+';
				}
				$searchon .= $terms[1] . $wgContLang->stripForSearch( $terms[2] );
				if( !empty( $terms[3] ) ) {
					$regexp = preg_quote( $terms[3] );
					if( $terms[4] ) $regexp .= "[0-9A-Za-z_]+";
				} else {
					$regexp = preg_quote( str_replace( '"', '', $terms[2] ) );
				}
				$this->searchTerms[] = $regexp;
			}
			wfDebug( "Would search with '$searchon'\n" );
			wfDebug( "Match with /\b" . implode( '\b|\b', $this->searchTerms ) . "\b/\n" );
		} else {
			wfDebug( "Can't understand search query '{$this->filteredText}'\n" );
		}
		
		$searchon = $this->db->strencode( $searchon );
		$this->titleCond = " MATCH(si_title) AGAINST('$searchon' IN BOOLEAN MODE)";
		$this->textCond = " (MATCH(si_text) AGAINST('$searchon' IN BOOLEAN MODE) AND cur_is_redirect=0)";
		return MW_SEARCH_OK;
	}

	function &getMatches( $cond, $limit, $offset = 0 ) {
		$searchindex = $this->db->tableName( 'searchindex' );
		$cur = $this->db->tableName( 'cur' );
		$searchnamespaces = $this->queryNamespaces();
		$redircond = $this->searchRedirects();
		
		$sql = "SELECT cur_id,cur_namespace,cur_title," .
		  "cur_text FROM $cur,$searchindex " .
		  "WHERE cur_id=si_page AND {$cond} " .
		  "{$searchnamespaces} {$redircond} " .
		  $this->db->limitResult( $limit, $offset );
		
		$res = $this->db->query( $sql, 'SearchEngine::getMatches' );
		$matches = array();
		while ( $row = $this->db->fetchObject( $res ) ) {
			$matches[] = $row;
		}
		$this->db->freeResult( $res );
		
		return $matches;
	}

	function showMatches( &$matches, $offset, $msgEmpty, $msgFound ) {
		global $wgOut;
		if ( 0 == count( $matches ) ) {
			$wgOut->addHTML( "<h2>" . wfMsg( $msgEmpty ) .
			  "</h2>\n" );
			return false;
		} else {
			$off = $offset + 1;
			$wgOut->addHTML( "<h2>" . wfMsg( $msgFound ) .
			  "</h2>\n<ol start='{$off}'>" );

			foreach( $matches as $row ) {
				$this->showHit( $row );
			}
			$wgOut->addHTML( "</ol>\n" );
			return true;
		}
	}

	function showHit( $row ) {
		global $wgUser, $wgOut, $wgContLang;

		$t = Title::makeName( $row->cur_namespace, $row->cur_title );
		if( is_null( $t ) ) {
			$wgOut->addHTML( "<!-- Broken link in search result -->\n" );
			return;
		}
		$sk = $wgUser->getSkin();

		$contextlines = $wgUser->getOption( 'contextlines' );
		if ( '' == $contextlines ) { $contextlines = 5; }
		$contextchars = $wgUser->getOption( 'contextchars' );
		if ( '' == $contextchars ) { $contextchars = 50; }

		$link = $sk->makeKnownLink( $t, '' );
		$size = wfMsg( 'nbytes', strlen( $row->cur_text ) );
		$wgOut->addHTML( "<li>{$link} ({$size})" );

		$lines = explode( "\n", $row->cur_text );
		$pat1 = "/(.*)(" . implode( "|", $this->searchTerms ) . ")(.*)/i";
		$lineno = 0;

		foreach ( $lines as $line ) {
			if ( 0 == $contextlines ) {
				break;
			}
			--$contextlines;
			++$lineno;
			if ( ! preg_match( $pat1, $line, $m ) ) {
				continue;
			}

			$pre = $wgContLang->truncate( $m[1], -$contextchars, '...' );

			if ( count( $m ) < 3 ) {
				$post = '';
			} else {
				$post = $wgContLang->truncate( $m[3], $contextchars, '...' );
			}

			$found = $m[2];

			$line = htmlspecialchars( $pre . $found . $post );
			$pat2 = '/(' . implode( '|', $this->searchTerms ) . ")/i";
			$line = preg_replace( $pat2,
			  "<span class='searchmatch'>\\1</span>", $line );

			$wgOut->addHTML( "<br /><small>{$lineno}: {$line}</small>\n" );
		}
		$wgOut->addHTML( "</li>\n" );
	}

	function getNearMatch() {
		# Exact match? No need to look further.
		$title = Title::newFromText( $this->rawText );
		if ( $title->getNamespace() == NS_SPECIAL || 0 != $title->getArticleID() ) {
			return $title;
		}

		# Now try all lower case (i.e. first letter capitalized)
		#
		$title = Title::newFromText( strtolower( $this->rawText ) );
		if ( 0 != $title->getArticleID() ) {
			return $title;
		}

		# Now try capitalized string
		#
		$title = Title::newFromText( ucwords( strtolower( $this->rawText ) ) );
		if ( 0 != $title->getArticleID() ) {
			return $title;
		}

		# Now try all upper case
		#
		$title = Title::newFromText( strtoupper( $this->rawText ) );
		if ( 0 != $title->getArticleID() ) {
			return $title;
		}

		# Entering an IP address goes to the contributions page
		if ( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $this->rawText ) ) {
			$title = Title::makeTitle( NS_SPECIAL, "Contributions/" . $this->rawText );
			return $title;
		}
		
		return NULL;
	}

	function goResult() {
		global $wgOut, $wgGoToEdit;
		global $wgDisableTextSearch;
		$fname = 'SearchEngine::goResult';
		
		# Try to go to page as entered.
		#
		$t = Title::newFromText( $this->rawText );

		# If the string cannot be used to create a title
		if( is_null( $t ) ){ 
			$this->showResults();
			return;
		}

		# If there's an exact or very near match, jump right there.
		$t = $this->getNearMatch();
		if( !is_null( $t ) ) {
			$wgOut->redirect( $t->getFullURL() );
			return;
		}
		
		# No match, generate an edit URL
		$t = Title::newFromText( $this->rawText );
		
		# If the feature is enabled, go straight to the edit page
		if ( $wgGoToEdit ) {
			$wgOut->redirect( $t->getFullURL( 'action=edit' ) );
			return;
		}
		
		if( $t ) {
			$editurl = $t->escapeLocalURL( 'action=edit' );
		} else {
			$editurl = ''; # ?? 
		}
		$wgOut->addHTML( '<p>' . wfMsg('nogomatch', $editurl, htmlspecialchars( $this->rawText ) ) . "</p>\n" );

		# Try a fuzzy title search
		$anyhit = false;
		global $wgDisableFuzzySearch;
		if(! $wgDisableFuzzySearch ){
			foreach( array(NS_MAIN, NS_PROJECT, NS_USER, NS_IMAGE, NS_MEDIAWIKI) as $namespace){
				$anyhit |= SearchEngine::doFuzzyTitleSearch( $this->rawText, $namespace );
			}
		}
		
		if( ! $anyhit ){
			return $this->showResults();
		}
	}

	/**
	 * @static
	 */
	function doFuzzyTitleSearch( $search, $namespace ){
		global $wgContLang, $wgOut;
		
		$this->setupPage();
		
		$sstr = ucfirst($search);
		$sstr = str_replace(' ', '_', $sstr);
		$fuzzymatches = SearchEngine::fuzzyTitles( $sstr, $namespace );
		$fuzzymatches = array_slice($fuzzymatches, 0, 10);
		$slen = strlen( $search );
		$wikitext = '';
		foreach($fuzzymatches as $res){
			$t = str_replace('_', ' ', $res[1]);
			$tfull = $wgContLang->getNsText( $namespace ) . ":$t|$t";
			if( $namespace == NS_MAIN )
				$tfull = $t;
			$distance = $res[0];
			$closeness = (strlen( $search ) - $distance) / strlen( $search );
			$percent = intval( $closeness * 100 ) . '%';
			$stars = str_repeat('*', ceil(5 * $closeness) );
			$wikitext .= "* [[$tfull]] $percent ($stars)\n";	
		}
		if( $wikitext ){
			if( $namespace != NS_MAIN )
				$wikitext = '=== ' . $wgContLang->getNsText( $namespace ) . " ===\n" . $wikitext;
			$wgOut->addWikiText( $wikitext );
			return true;
		}
		return false;
	}

	/**
	 * @static
	 */
	function fuzzyTitles( $sstr, $namespace = NS_MAIN ){
		$span = 0.10; // weed on title length before doing levenshtein.
		$tolerance = 0.35; // allowed percentage of erronous characters
		$slen = strlen($sstr);
		$tolerance_count = ceil($tolerance * $slen);
		$spanabs = ceil($slen * (1 + $span)) - $slen;
		# print "Word: $sstr, len = $slen, range = [$min, $max], tolerance_count = $tolerance_count<BR>\n";
		$result = array();
		$cnt = 0;
		for( $i=0; $i <= $spanabs; $i++ ){
			$titles = SearchEngine::getTitlesByLength( $slen + $i, $namespace );
			if( $i != 0) {
				$titles = array_merge($titles, SearchEngine::getTitlesByLength( $slen - $i, $namespace ) );
			}
			foreach($titles as $t){
				$d = levenshtein($sstr, $t);
				if($d < $tolerance_count) 
					$result[] = array($d, $t);
				$cnt++;
			}
		}
		usort($result, 'SearchEngine_pcmp');
		return $result;
	}

	/**
	 * static
	 */
	function getTitlesByLength($aLength, $aNamespace = 0){
		global $wgMemc, $wgDBname;
		$fname = 'SearchEngin::getTitlesByLength';
		
		// to avoid multiple costly SELECTs in case of no memcached
		if( $this->allTitles ){ 
			if( isset( $this->allTitles[$aLength][$aNamespace] ) ){
				return $this->allTitles[$aLength][$aNamespace];
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
		
		$res = $this->db->select( 'cur', array( 'cur_title', 'cur_namespace' ), false, $fname );
		$titles = array(); // length, ns, [titles]
		while( $obj = $this->db->fetchObject( $res ) ){
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
		$this->allTitles = $titles;
		if( isset( $titles[$aLength][$aNamespace] ) )
			return $titles[$aLength][$aNamespace];
		else
			return array();
	}
}

/**
 * @access private
 * @static
 */
function SearchEngine_pcmp($a, $b){ return $a[0] - $b[0]; }

?>
