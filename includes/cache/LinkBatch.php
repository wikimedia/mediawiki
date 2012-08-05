<?php
/**
 * Batch query to determine page existence.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Cache
 */

/**
 * Class representing a list of titles
 * The execute() method checks them all for existence and adds them to a LinkCache object
 *
 * @ingroup Cache
 */
class LinkBatch {
	/**
	 * 2-d array, first index namespace, second index dbkey, value arbitrary
	 */
	var $data = array();

	/**
	 * For debugging which method is using this class.
	 */
	protected $caller;

	function __construct( $arr = array() ) {
		foreach( $arr as $item ) {
			$this->addObj( $item );
		}
	}

	/**
	 * Use ->setCaller( __METHOD__ ) to indicate which code is using this
	 * class. Only used in debugging output.
	 * @since 1.17
	 *
	 * @param $caller
	 */
	public function setCaller( $caller ) {
		$this->caller = $caller;
	}

	/**
	 * @param $title Title
	 */
	public function addObj( $title ) {
		if ( is_object( $title ) ) {
			$this->add( $title->getNamespace(), $title->getDBkey() );
		} else {
			wfDebug( "Warning: LinkBatch::addObj got invalid title object\n" );
		}
	}

	/**
	 * @param $ns int
	 * @param $dbkey string
	 * @return mixed
	 */
	public function add( $ns, $dbkey ) {
		if ( $ns < 0 ) {
			return;
		}
		if ( !array_key_exists( $ns, $this->data ) ) {
			$this->data[$ns] = array();
		}

		$this->data[$ns][str_replace( ' ', '_', $dbkey )] = 1;
	}

	/**
	 * Set the link list to a given 2-d array
	 * First key is the namespace, second is the DB key, value arbitrary
	 *
	 * @param $array array
	 */
	public function setArray( $array ) {
		$this->data = $array;
	}

	/**
	 * Returns true if no pages have been added, false otherwise.
	 *
	 * @return bool
	 */
	public function isEmpty() {
		return ($this->getSize() == 0);
	}

	/**
	 * Returns the size of the batch.
	 *
	 * @return int
	 */
	public function getSize() {
		return count( $this->data );
	}

	/**
	 * Do the query and add the results to the LinkCache object
	 *
	 * @return Array mapping PDBK to ID
	 */
	public function execute() {
		$linkCache = LinkCache::singleton();
		return $this->executeInto( $linkCache );
	}

	/**
	 * Do the query and add the results to a given LinkCache object
	 * Return an array mapping PDBK to ID
	 *
	 * @param $cache LinkCache
	 * @return Array remaining IDs
	 */
	protected function executeInto( &$cache ) {
		wfProfileIn( __METHOD__ );
		$res = $this->doQuery();
		$this->doGenderQuery();
		$ids = $this->addResultToCache( $cache, $res );
		wfProfileOut( __METHOD__ );
		return $ids;
	}

	/**
	 * Add a ResultWrapper containing IDs and titles to a LinkCache object.
	 * As normal, titles will go into the static Title cache field.
	 * This function *also* stores extra fields of the title used for link
	 * parsing to avoid extra DB queries.
	 *
	 * @param $cache LinkCache
	 * @param $res
	 * @return Array of remaining titles
	 */
	public function addResultToCache( $cache, $res ) {
		if ( !$res ) {
			return array();
		}

		// For each returned entry, add it to the list of good links, and remove it from $remaining

		$ids = array();
		$remaining = $this->data;
		foreach ( $res as $row ) {
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$cache->addGoodLinkObjFromRow( $title, $row );
			$ids[$title->getPrefixedDBkey()] = $row->page_id;
			unset( $remaining[$row->page_namespace][$row->page_title] );
		}

		// The remaining links in $data are bad links, register them as such
		foreach ( $remaining as $ns => $dbkeys ) {
			foreach ( $dbkeys as $dbkey => $unused ) {
				$title = Title::makeTitle( $ns, $dbkey );
				$cache->addBadLinkObj( $title );
				$ids[$title->getPrefixedDBkey()] = 0;
			}
		}
		return $ids;
	}

	/**
	 * Perform the existence test query, return a ResultWrapper with page_id fields
	 * @return Bool|ResultWrapper
	 */
	public function doQuery() {
		if ( $this->isEmpty() ) {
			return false;
		}
		wfProfileIn( __METHOD__ );

		// This is similar to LinkHolderArray::replaceInternal
		$dbr = wfGetDB( DB_SLAVE );
		$table = 'page';
		$fields = array( 'page_id', 'page_namespace', 'page_title', 'page_len',
			'page_is_redirect', 'page_latest' );
		$conds = $this->constructSet( 'page', $dbr );

		// Do query
		$caller = __METHOD__;
		if ( strval( $this->caller ) !== '' ) {
			$caller .= " (for {$this->caller})";
		}
		$res = $dbr->select( $table, $fields, $conds, $caller );
		wfProfileOut( __METHOD__ );
		return $res;
	}

	/**
	 * Do (and cache) {{GENDER:...}} information for userpages in this LinkBatch
	 *
	 * @return bool whether the query was successful
	 */
	public function doGenderQuery() {
		if ( $this->isEmpty() ) {
			return false;
		}

		global $wgContLang;
		if ( !$wgContLang->needsGenderDistinction() ) {
			return false;
		}

		$genderCache = GenderCache::singleton();
		$genderCache->doLinkBatch( $this->data, $this->caller );
		return true;
	}

	/**
	 * Construct a WHERE clause which will match all the given titles.
	 *
	 * @param $prefix String: the appropriate table's field name prefix ('page', 'pl', etc)
	 * @param $db DatabaseBase object to use
	 * @return mixed string with SQL where clause fragment, or false if no items.
	 */
	public function constructSet( $prefix, $db ) {
		return $db->makeWhereFrom2d( $this->data, "{$prefix}_namespace", "{$prefix}_title" );
	}
}
