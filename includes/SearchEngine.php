<?php
/**
 * Contain a class for special pages
 * @package MediaWiki
 */

/** */
class SearchEngine {
	var $limit = 10;
	var $offset = 0;
	var $searchTerms = array();
	var $namespaces = array( 0 );
	var $showRedirects = false;
	
	/**
	 * Perform a full text search query and return a result set.
	 *
	 * @param string $term - Raw search term
	 * @param array $namespaces - List of namespaces to search
	 * @return ResultWrapper
	 * @access public
	 */
	function searchText( $term ) {
		return $this->db->resultObject( $this->db->query( $this->getQuery( $this->filter( $term ), true ) ) );
	}

	/**
	 * Perform a title-only search query and return a result set.
	 *
	 * @param string $term - Raw search term
	 * @param array $namespaces - List of namespaces to search
	 * @return ResultWrapper
	 * @access public
	 */
	function searchTitle( $term ) {
		return $this->db->resultObject( $this->db->query( $this->getQuery( $this->filter( $term ), false ) ) );
	}
	
	/**
	 * If an exact title match can be find, or a very slightly close match,
	 * return the title. If no match, returns NULL.
	 *
	 * @param string $term
	 * @return Title
	 * @access private
	 */
	function getNearMatch( $term ) {
		# Exact match? No need to look further.
		$title = Title::newFromText( $term );
		if ( $title->getNamespace() == NS_SPECIAL || 0 != $title->getArticleID() ) {
			return $title;
		}

		# Now try all lower case (i.e. first letter capitalized)
		#
		$title = Title::newFromText( strtolower( $term ) );
		if ( 0 != $title->getArticleID() ) {
			return $title;
		}

		# Now try capitalized string
		#
		$title = Title::newFromText( ucwords( strtolower( $term ) ) );
		if ( 0 != $title->getArticleID() ) {
			return $title;
		}

		# Now try all upper case
		#
		$title = Title::newFromText( strtoupper( $term ) );
		if ( 0 != $title->getArticleID() ) {
			return $title;
		}

		# Entering an IP address goes to the contributions page
		if ( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $term ) ) {
			$title = Title::makeTitle( NS_SPECIAL, "Contributions/" . $term );
			return $title;
		}
		
		return NULL;
	}
	
	function legalSearchChars() {
		return "A-Za-z_'0-9\\x80-\\xFF\\-";
	}

	/**
	 * Set the maximum number of results to return
	 * and how many to skip before returning the first.
	 *
	 * @param int $limit
	 * @param int $offset
	 * @access public
	 */
	function setLimitOffset( $limit, $offset = 0 ) {
		$this->limit = IntVal( $limit );
		$this->offset = IntVal( $offset );
	}
	
	/**
	 * Set which namespaces the search should include.
	 * Give an array of namespace index numbers.
	 *
	 * @param array $namespaces
	 * @access public
	 */
	function setNamespaces( $namespaces ) {
		$this->namespaces = $namespaces;
	}
	
	/**
	 * Make a list of searchable namespaces and their canonical names.
	 * @return array
	 * @access public
	 */
	function searchableNamespaces() {
		global $wgContLang;
		$arr = array();
		foreach( $wgContLang->getNamespaces() as $ns => $name ) {
			if( $ns >= 0 ) {
				$arr[$ns] = $name;
			}
		}
		return $arr;
	}
	
	/**
	 * Fetch an array of regular expression fragments for matching
	 * the search terms as parsed by this engine in a text extract.
	 *
	 * @return array
	 * @access public
	 */
	function termMatches() {
		return $this->searchTerms;
	}
	
	/**
	 * Return a 'cleaned up' search string
	 *
	 * @return string
	 * @access public
	 */
	function filter( $text ) {
		$lc = $this->legalSearchChars();
		return trim( preg_replace( "/[^{$lc}]/", " ", $text ) );
	}
	
	/**
	 * Return a partial WHERE clause to exclude redirects, if so set
	 * @return string
	 * @access private
	 */
	function queryRedirect() {
		if( $this->showRedirects ) {
			return 'AND cur_is_redirect=0';
		} else {
			return '';
		}
	}
	
	/**
	 * Return a partial WHERE clause to limit the search to the given namespaces
	 * @return string
	 * @access private
	 */
	function queryNamespaces() {
		$namespaces = implode( ',', $this->namespaces );
		if ($namespaces == '') {
			$namespaces = '0';
		}
		return 'AND cur_namespace IN (' . $namespaces . ')';
	}
	
	/**
	 * Return a LIMIT clause to limit results on the query.
	 * @return string
	 * @access private
	 */
	function queryLimit() {
		return $this->db->limitResult( $this->limit, $this->offset );
	}
	
	/**
	 * Construct the full SQL query to do the search.
	 * The guts shoulds be constructed in queryMain()
	 * @param string $filteredTerm
	 * @param bool $fulltext
	 * @access private
	 */
	function getQuery( $filteredTerm, $fulltext ) {
		return $this->queryMain( $filteredTerm, $fulltext ) . ' ' .
			$this->queryRedirect() . ' ' .
			$this->queryNamespaces() . ' ' .
			$this->queryLimit();
	}
	
}

/** */
class SearchEngineDummy {
	function search( $term ) {
		return null;
	}
}


?>
