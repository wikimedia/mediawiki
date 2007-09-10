<?php

/**
 * Support class for the updateArticleCount.php maintenance script
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

class ArticleCounter {

	var $dbr;
	var $namespaces;
	
	function ArticleCounter() {
		global $wgContentNamespaces;
		$this->namespaces = $wgContentNamespaces;
		$this->dbr =& wfGetDB( DB_SLAVE );
	}
	
	/**
	 * Produce a comma-delimited set of namespaces
	 * Includes paranoia
	 *
	 * @return string
	 */
	function makeNsSet() {
		foreach( $this->namespaces as $namespace )
			$namespaces[] = intval( $namespace );
		return implode( ', ', $namespaces );
	}
	
	/**
	 * Produce SQL for the query
	 *
	 * @return string
	 */
	function makeSql() {
		extract( $this->dbr->tableNames( 'page', 'pagelinks' ) );
		$nsset = $this->makeNsSet();
		return "SELECT COUNT(*) AS count FROM {$page}
				LEFT JOIN {$pagelinks} ON pl_from = page_id
				WHERE page_namespace IN ( $nsset )
				AND page_is_redirect = 0
				AND page_len > 0
				AND pl_namespace IS NOT NULL";
	}
	
	/**
	 * Count the number of valid content pages in the wiki
	 *
	 * @return mixed Integer, or false if there's a problem
	 */
	function count() {
		$res = $this->dbr->query( $this->makeSql(), __METHOD__ );
		if( $res ) {
			$row = $this->dbr->fetchObject( $res );
			$this->dbr->freeResult( $res );
			return (int)$row->count;
		} else {
			return false; # Look out for this when handling the result
		}
	}

}

?>