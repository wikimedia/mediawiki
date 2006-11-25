<?php

/**
 * Class representing a list of titles
 * The execute() method checks them all for existence and adds them to a LinkCache object
 +
 * @package MediaWiki
 * @subpackage Cache
 */
class LinkBatch {
	/**
	 * 2-d array, first index namespace, second index dbkey, value arbitrary
	 */
	var $data = array();

	function LinkBatch( $arr = array() ) {
		foreach( $arr as $item ) {
			$this->addObj( $item );
		}
	}

	function addObj( $title ) {
		if ( is_object( $title ) ) {
			$this->add( $title->getNamespace(), $title->getDBkey() );
		} else {
			wfDebug( "Warning: LinkBatch::addObj got invalid title object\n" );
		}
	}

	function add( $ns, $dbkey ) {
		if ( $ns < 0 ) {
			return;
		}
		if ( !array_key_exists( $ns, $this->data ) ) {
			$this->data[$ns] = array();
		}

		$this->data[$ns][$dbkey] = 1;
	}

	/**
	 * Set the link list to a given 2-d array
	 * First key is the namespace, second is the DB key, value arbitrary
	 */
	function setArray( $array ) {
		$this->data = $array;
	}

	/**
	 * Returns true if no pages have been added, false otherwise.
	 */
	function isEmpty() {
		return ($this->getSize() == 0);
	}

	/**
	 * Returns the size of the batch.
	 */
	function getSize() {
		return count( $this->data );
	}

	/**
	 * Do the query and add the results to the LinkCache object
	 * Return an array mapping PDBK to ID
	 */
	 function execute() {
	 	$linkCache =& LinkCache::singleton();
	 	return $this->executeInto( $linkCache );
	 }

	/**
	 * Do the query and add the results to a given LinkCache object
	 * Return an array mapping PDBK to ID
	 */
	function executeInto( &$cache ) {
		$fname = 'LinkBatch::executeInto';
		wfProfileIn( $fname );
		// Do query
		$res = $this->doQuery();
		if ( !$res ) {
			wfProfileOut( $fname );
			return array();
		}

		// For each returned entry, add it to the list of good links, and remove it from $remaining

		$ids = array();
		$remaining = $this->data;
		while ( $row = $res->fetchObject() ) {
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$cache->addGoodLinkObj( $row->page_id, $title );
			$ids[$title->getPrefixedDBkey()] = $row->page_id;
			unset( $remaining[$row->page_namespace][$row->page_title] );
		}
		$res->free();

		// The remaining links in $data are bad links, register them as such
		foreach ( $remaining as $ns => $dbkeys ) {
			foreach ( $dbkeys as $dbkey => $unused ) {
				$title = Title::makeTitle( $ns, $dbkey );
				$cache->addBadLinkObj( $title );
				$ids[$title->getPrefixedDBkey()] = 0;
			}
		}
		wfProfileOut( $fname );
		return $ids;
	}

	/**
	 * Perform the existence test query, return a ResultWrapper with page_id fields
	 */
	function doQuery() {
		$fname = 'LinkBatch::doQuery';

		if ( $this->isEmpty() ) {
			return false;
		}
		wfProfileIn( $fname );

		// Construct query
		// This is very similar to Parser::replaceLinkHolders
		$dbr =& wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$set = $this->constructSet( 'page', $dbr );
		if ( $set === false ) {
			wfProfileOut( $fname );
			return false;
		}
		$sql = "SELECT page_id, page_namespace, page_title FROM $page WHERE $set";

		// Do query
		$res = new ResultWrapper( $dbr,  $dbr->query( $sql, $fname ) );
		wfProfileOut( $fname );
		return $res;
	}

	/**
	 * Construct a WHERE clause which will match all the given titles.
	 * Give the appropriate table's field name prefix ('page', 'pl', etc).
	 *
	 * @param $prefix String: ??
	 * @return string
	 * @public
	 */
	function constructSet( $prefix, &$db ) {
		$first = true;
		$firstTitle = true;
		$sql = '';
		foreach ( $this->data as $ns => $dbkeys ) {
			if ( !count( $dbkeys ) ) {
				continue;
			}

			if ( $first ) {
				$first = false;
			} else {
				$sql .= ' OR ';
			}
			$sql .= "({$prefix}_namespace=$ns AND {$prefix}_title IN (";

			$firstTitle = true;
			foreach( $dbkeys as $dbkey => $unused ) {
				if ( $firstTitle ) {
					$firstTitle = false;
				} else {
					$sql .= ',';
				}
				$sql .= $db->addQuotes( $dbkey );
			}

			$sql .= '))';
		}
		if ( $first && $firstTitle ) {
			# No titles added
			return false;
		} else {
			return $sql;
		}
	}
}

?>
