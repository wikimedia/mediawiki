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
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;

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
	 * @var LinkCache|null
	 */
	private $linkCache;

	/**
	 * @var TitleFormatter|null
	 */
	private $titleFormatter;

	/**
	 * @var Language|null
	 */
	private $contentLanguage;

	/**
	 * @var GenderCache|null
	 */
	private $genderCache;

	/**
	 * @var ILoadBalancer|null
	 */
	private $loadBalancer;

	/**
	 * @param Traversable|LinkTarget[] $arr Initial items to be added to the batch
	 * @param LinkCache|null $linkCache
	 * @param TitleFormatter|null $titleFormatter
	 * @param Language|null $contentLanguage
	 * @param GenderCache|null $genderCache
	 * @param ILoadBalancer|null $loadBalancer
	 * @deprecated 1.35 Use makeLinkBatch of the LinkBatchFactory service instead
	 */
	public function __construct(
		iterable $arr = [],
		?LinkCache $linkCache = null,
		?TitleFormatter $titleFormatter = null,
		?Language $contentLanguage = null,
		?GenderCache $genderCache = null,
		?ILoadBalancer $loadBalancer = null
	) {
		$services = MediaWikiServices::getInstance();

		$this->linkCache = $linkCache ?? $services->getLinkCache();
		$this->titleFormatter = $titleFormatter ?? $services->getTitleFormatter();
		$this->contentLanguage = $contentLanguage ?? $services->getContentLanguage();
		$this->genderCache = $genderCache ?? $services->getGenderCache();
		$this->loadBalancer = $loadBalancer ?? $services->getDBLoadBalancer();

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
			wfDebug( "Warning: LinkBatch::addObj got invalid LinkTarget object" );
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
		return $this->executeInto( $this->linkCache );
	}

	/**
	 * Do the query and add the results to a given LinkCache object
	 * Return an array mapping PDBK to ID
	 *
	 * @param LinkCache $cache
	 * @return array Remaining IDs
	 */
	protected function executeInto( $cache ) {
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

		// For each returned entry, add it to the list of good links, and remove it from $remaining

		$ids = [];
		$remaining = $this->data;
		foreach ( $res as $row ) {
			$title = TitleValue::tryNew( (int)$row->page_namespace, $row->page_title );
			if ( $title ) {
				$cache->addGoodLinkObjFromRow( $title, $row );
				$pdbk = $this->titleFormatter->getPrefixedDBkey( $title );
				$ids[$pdbk] = $row->page_id;
			} else {
				wfLogWarning( __METHOD__ . ': encountered invalid title: ' .
					$row->page_namespace . '-' . $row->page_title );
			}

			unset( $remaining[$row->page_namespace][$row->page_title] );
		}

		// The remaining links in $data are bad links, register them as such
		foreach ( $remaining as $ns => $dbkeys ) {
			foreach ( $dbkeys as $dbkey => $unused ) {
				$title = TitleValue::tryNew( (int)$ns, (string)$dbkey );
				if ( $title ) {
					$cache->addBadLinkObj( $title );
					$pdbk = $this->titleFormatter->getPrefixedDBkey( $title );
					$ids[$pdbk] = 0;
				} else {
					wfLogWarning( __METHOD__ . ': encountered invalid title: ' . $ns . '-' . $dbkey );
				}
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
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
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

		if ( !$this->contentLanguage->needsGenderDistinction() ) {
			return false;
		}

		$this->genderCache->doLinkBatch( $this->data, $this->caller );

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
