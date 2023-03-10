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

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\ProperPageIdentity;
use Psr\Log\LoggerInterface;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\Platform\ISQLPlatform;

/**
 * Class representing a list of titles
 * The execute() method checks them all for existence and adds them to a LinkCache object
 *
 * @ingroup Cache
 */
class LinkBatch {
	/**
	 * @var array[] 2-d array, first index namespace, second index dbkey, value arbitrary
	 */
	public $data = [];

	/**
	 * @var ProperPageIdentity[]|null page identity objects corresponding to the links in the batch
	 */
	private $pageIdentities = null;

	/**
	 * @var string|null For debugging which method is using this class.
	 */
	protected $caller;

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @var Language
	 */
	private $contentLanguage;

	/**
	 * @var GenderCache
	 */
	private $genderCache;

	/**
	 * @var ILoadBalancer
	 */
	private $loadBalancer;

	/** @var LinksMigration */
	private $linksMigration;

	/** @var LoggerInterface */
	private $logger;

	/**
	 * @param iterable<LinkTarget>|iterable<PageReference> $arr Initial items to be added to the batch
	 * @param LinkCache|null $linkCache
	 * @param TitleFormatter|null $titleFormatter
	 * @param Language|null $contentLanguage
	 * @param GenderCache|null $genderCache
	 * @param ILoadBalancer|null $loadBalancer
	 * @param LinksMigration|null $linksMigration
	 * @param LoggerInterface|null $logger
	 * @deprecated since 1.35 Use newLinkBatch of the LinkBatchFactory service instead, Hard-deprecated in 1.40
	 */
	public function __construct(
		iterable $arr = [],
		?LinkCache $linkCache = null,
		?TitleFormatter $titleFormatter = null,
		?Language $contentLanguage = null,
		?GenderCache $genderCache = null,
		?ILoadBalancer $loadBalancer = null,
		?LinksMigration $linksMigration = null,
		?LoggerInterface $logger = null
	) {
		if ( !$linkCache ) {
			wfDeprecatedMsg(
				__METHOD__ . ' without providing all services is deprecated',
				'1.35'
			);
		}

		$getServices = static function () {
			// BC hack. Use a closure so this can be unit-tested.
			return MediaWikiServices::getInstance();
		};

		$this->linkCache = $linkCache ?? $getServices()->getLinkCache();
		$this->titleFormatter = $titleFormatter ?? $getServices()->getTitleFormatter();
		$this->contentLanguage = $contentLanguage ?? $getServices()->getContentLanguage();
		$this->genderCache = $genderCache ?? $getServices()->getGenderCache();
		$this->loadBalancer = $loadBalancer ?? $getServices()->getDBLoadBalancer();
		$this->linksMigration = $linksMigration ?? $getServices()->getLinksMigration();
		$this->logger = $logger ?? LoggerFactory::getInstance( 'LinkBatch' );

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
	 * @param LinkTarget|PageReference $link
	 */
	public function addObj( $link ) {
		if ( !$link ) {
			// Don't die if we got null, just skip. There is nothing to do anyway.
			// For now, let's avoid things like T282180. We should be more strict in the future.
			$this->logger->warning(
				'Skipping null link, probably due to a bad title.',
				[ 'exception' => new RuntimeException() ]
			);
			return;
		}
		if ( $link instanceof LinkTarget && $link->isExternal() ) {
			$this->logger->warning(
				'Skipping interwiki link',
				[ 'exception' => new RuntimeException() ]
			);
			return;
		}

		Assert::parameterType( [ LinkTarget::class, PageReference::class ], $link, '$link' );
		$this->add( $link->getNamespace(), $link->getDBkey() );
	}

	/**
	 * @param int $ns
	 * @param string $dbkey
	 */
	public function add( $ns, $dbkey ) {
		if ( $ns < 0 || $dbkey === '' ) {
			// T137083
			return;
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
	 * @return int[] Mapping PDBK to ID
	 */
	public function execute() {
		return $this->executeInto( $this->linkCache );
	}

	/**
	 * Do the query, add the results to the LinkCache object,
	 * and return ProperPageIdentity instances corresponding to the pages in the batch.
	 *
	 * @since 1.37
	 * @return ProperPageIdentity[] A list of ProperPageIdentities
	 */
	public function getPageIdentities(): array {
		if ( $this->pageIdentities === null ) {
			$this->execute();
		}

		return $this->pageIdentities;
	}

	/**
	 * Do the query and add the results to a given LinkCache object
	 * Return an array mapping PDBK to ID
	 *
	 * @param LinkCache $cache
	 * @return int[] Remaining IDs
	 */
	protected function executeInto( $cache ) {
		$res = $this->doQuery();
		$this->doGenderQuery();
		return $this->addResultToCache( $cache, $res );
	}

	/**
	 * Add a result wrapper containing IDs and titles to a LinkCache object.
	 * As normal, titles will go into the static Title cache field.
	 * This function *also* stores extra fields of the title used for link
	 * parsing to avoid extra DB queries.
	 *
	 * @param LinkCache $cache
	 * @param IResultWrapper $res
	 * @return int[] Array of remaining titles
	 */
	public function addResultToCache( $cache, $res ) {
		if ( !$res ) {
			return [];
		}

		// For each returned entry, add it to the list of good links, and remove it from $remaining

		$this->pageIdentities ??= [];

		$ids = [];
		$remaining = $this->data;
		foreach ( $res as $row ) {
			try {
				$title = new TitleValue( (int)$row->page_namespace, $row->page_title );

				$cache->addGoodLinkObjFromRow( $title, $row );
				$pdbk = $this->titleFormatter->getPrefixedDBkey( $title );
				$ids[$pdbk] = $row->page_id;

				$pageIdentity = new PageIdentityValue(
					(int)$row->page_id,
					(int)$row->page_namespace,
					$row->page_title,
					ProperPageIdentity::LOCAL
				);

				$key = CacheKeyHelper::getKeyForPage( $pageIdentity );
				$this->pageIdentities[$key] = $pageIdentity;
			} catch ( InvalidArgumentException $ex ) {
				$this->logger->warning(
					'Encountered invalid title',
					[ 'title_namespace' => $row->page_namespace, 'title_dbkey' => $row->page_title ]
				);
			}

			unset( $remaining[$row->page_namespace][$row->page_title] );
		}

		// The remaining links in $data are bad links, register them as such
		foreach ( $remaining as $ns => $dbkeys ) {
			foreach ( $dbkeys as $dbkey => $unused ) {
				try {
					$title = new TitleValue( (int)$ns, (string)$dbkey );

					$cache->addBadLinkObj( $title );
					$pdbk = $this->titleFormatter->getPrefixedDBkey( $title );
					$ids[$pdbk] = 0;

					$pageIdentity = new PageIdentityValue( 0, (int)$ns, $dbkey, ProperPageIdentity::LOCAL );
					$key = CacheKeyHelper::getKeyForPage( $pageIdentity );
					$this->pageIdentities[$key] = $pageIdentity;
				} catch ( InvalidArgumentException $ex ) {
					$this->logger->warning(
						'Encountered invalid title',
						[ 'title_namespace' => $ns, 'title_dbkey' => $dbkey ]
					);
				}
			}
		}

		return $ids;
	}

	/**
	 * Perform the existence test query, return a result wrapper with page_id fields
	 * @return IResultWrapper|false
	 */
	public function doQuery() {
		if ( $this->isEmpty() ) {
			return false;
		}

		// This is similar to LinkHolderArray::replaceInternal
		$dbr = $this->loadBalancer->getConnection( DB_REPLICA );
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( LinkCache::getSelectFields() )
			->from( 'page' )
			->where( $this->constructSet( 'page', $dbr ) );

		$caller = __METHOD__;
		if ( strval( $this->caller ) !== '' ) {
			$caller .= " (for {$this->caller})";
		}

		return $queryBuilder->caller( $caller )->fetchResultSet();
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
	 * @param ISQLPlatform $db DB object to use
	 * @return string|false String with SQL where clause fragment, or false if no items.
	 */
	public function constructSet( $prefix, $db ) {
		if ( isset( $this->linksMigration::$prefixToTableMapping[$prefix] ) ) {
			[ $blNamespace, $blTitle ] = $this->linksMigration->getTitleFields(
				$this->linksMigration::$prefixToTableMapping[$prefix]
			);
		} else {
			$blNamespace = "{$prefix}_namespace";
			$blTitle = "{$prefix}_title";
		}
		return $db->makeWhereFrom2d( $this->data, $blNamespace, $blTitle );
	}
}
