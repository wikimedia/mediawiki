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
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\IDatabase;

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
	public $data = [];

	/**
	 * For debugging which method is using this class.
	 */
	protected $caller;

	/**
	 * @param Traversable|LinkTarget[] $arr Initial items to be added to the batch
	 */
	public function __construct( $arr = [] ) {
		foreach ( $arr as $item ) {
			$this->addObj( $item );
		}
	}

	/**
	 * Use ->setCaller( __METHOD__ ) to indicate which code is using this
	 * class. Only used in debugging output.
	 * @since 1.17
	 *
	 * @param string $caller
	 * @return self (since 1.32)
	 */
	public function setCaller( $caller ) {
		$this->caller = $caller;

		return $this;
	}

	/**
	 * @param LinkTarget $linkTarget
	 */
	public function addObj( $linkTarget ) {
		if ( is_object( $linkTarget ) ) {
			$this->add( $linkTarget->getNamespace(), $linkTarget->getDBkey() );
		} else {
			wfDebug( "Warning: LinkBatch::addObj got invalid LinkTarget object\n" );
		}
	}

	/**
	 * @param int $ns
	 * @param string $dbkey
	 */
	public function add( $ns, $dbkey ) {
		if ( $ns < 0 || $dbkey === '' ) {
			return; // T137083
		}
		if ( !array_key_exists( $ns, $this->data ) ) {
			$this->data[$ns] = [];
		}

		$this->data[$ns][strtr( $dbkey, ' ', '_' )] = 1;
	}

	/**
	 * Set the link list to a given 2-d array
	 * First key is the namespace, second is the DB key, value arbitrary
	 *
	 * @param array $array
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
		return $this->getSize() == 0;
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
	 * @return array Mapping PDBK to ID
	 */
	public function execute() {
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();

		return $this->executeInto( $linkCache );
	}

	/**
	 * Do the query and add the results to a given LinkCache object
	 * Return an array mapping PDBK to ID
	 *
	 * @param LinkCache &$cache
	 * @return array Remaining IDs
	 */
	protected function executeInto( &$cache ) {
		$res = $this->doQuery();
		$this->doGenderQuery();
		$ids = $this->addResultToCache( $cache, $res );

		return $ids;
	}

	/**
	 * Add a result wrapper containing IDs and titles to a LinkCache object.
	 * As normal, titles will go into the static Title cache field.
	 * This function *also* stores extra fields of the title used for link
	 * parsing to avoid extra DB queries.
	 *
	 * @param LinkCache $cache
	 * @param IResultWrapper $res
	 * @return array Array of remaining titles
	 */
	public function addResultToCache( $cache, $res ) {
		if ( !$res ) {
			return [];
		}

		$titleFormatter = MediaWikiServices::getInstance()->getTitleFormatter();
		// For each returned entry, add it to the list of good links, and remove it from $remaining

		$ids = [];
		$remaining = $this->data;
		foreach ( $res as $row ) {
			$title = new TitleValue( (int)$row->page_namespace, $row->page_title );
			$cache->addGoodLinkObjFromRow( $title, $row );
			$pdbk = $titleFormatter->getPrefixedDBkey( $title );
			$ids[$pdbk] = $row->page_id;
			unset( $remaining[$row->page_namespace][$row->page_title] );
		}

		// The remaining links in $data are bad links, register them as such
		foreach ( $remaining as $ns => $dbkeys ) {
			foreach ( $dbkeys as $dbkey => $unused ) {
				$title = new TitleValue( (int)$ns, (string)$dbkey );
				$cache->addBadLinkObj( $title );
				$pdbk = $titleFormatter->getPrefixedDBkey( $title );
				$ids[$pdbk] = 0;
			}
		}

		return $ids;
	}

	/**
	 * Perform the existence test query, return a result wrapper with page_id fields
	 * @return bool|IResultWrapper
	 */
	public function doQuery() {
		if ( $this->isEmpty() ) {
			return false;
		}

		// This is similar to LinkHolderArray::replaceInternal
		$dbr = wfGetDB( DB_REPLICA );
		$table = 'page';
		$fields = array_merge(
			LinkCache::getSelectFields(),
			[ 'page_namespace', 'page_title' ]
		);

		$conds = $this->constructSet( 'page', $dbr );

		// Do query
		$caller = __METHOD__;
		if ( strval( $this->caller ) !== '' ) {
			$caller .= " (for {$this->caller})";
		}
		$res = $dbr->select( $table, $fields, $conds, $caller );

		return $res;
	}

	/**
	 * Do (and cache) {{GENDER:...}} information for userpages in this LinkBatch
	 *
	 * @return bool Whether the query was successful
	 */
	public function doGenderQuery() {
		if ( $this->isEmpty() ) {
			return false;
		}
		$services = MediaWikiServices::getInstance();

		if ( !$services->getContentLanguage()->needsGenderDistinction() ) {
			return false;
		}

		$genderCache = $services->getGenderCache();
		$genderCache->doLinkBatch( $this->data, $this->caller );

		return true;
	}

	/**
	 * Construct a WHERE clause which will match all the given titles.
	 *
	 * @param string $prefix The appropriate table's field name prefix ('page', 'pl', etc)
	 * @param IDatabase $db DB object to use
	 * @return string|bool String with SQL where clause fragment, or false if no items.
	 */
	public function constructSet( $prefix, $db ) {
		return $db->makeWhereFrom2d( $this->data, "{$prefix}_namespace", "{$prefix}_title" );
	}
}
