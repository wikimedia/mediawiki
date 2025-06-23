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

namespace MediaWiki\Cache;

use InvalidArgumentException;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IConnectionProvider;
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
	 * @var array<int,array<string,mixed>> 2-d array, first index namespace, second index dbkey, value arbitrary
	 */
	public $data = [];

	/**
	 * @var UserIdentity[] Users to preload temporary account expiration status for
	 */
	private array $users = [];

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
	 * @var IConnectionProvider
	 */
	private $dbProvider;

	/** @var LinksMigration */
	private $linksMigration;

	private TempUserDetailsLookup $tempUserDetailsLookup;

	/** @var LoggerInterface */
	private $logger;

	/**
	 * @see \MediaWiki\Cache\LinkBatchFactory
	 *
	 * @internal
	 * @param iterable<LinkTarget>|iterable<PageReference> $arr Initial items to be added to the batch
	 * @param LinkCache $linkCache
	 * @param TitleFormatter $titleFormatter
	 * @param Language $contentLanguage
	 * @param GenderCache $genderCache
	 * @param IConnectionProvider $dbProvider
	 * @param LinksMigration $linksMigration
	 * @param TempUserDetailsLookup $tempUserDetailsLookup
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		iterable $arr,
		LinkCache $linkCache,
		TitleFormatter $titleFormatter,
		Language $contentLanguage,
		GenderCache $genderCache,
		IConnectionProvider $dbProvider,
		LinksMigration $linksMigration,
		TempUserDetailsLookup $tempUserDetailsLookup,
		LoggerInterface $logger
	) {
		$this->linkCache = $linkCache;
		$this->titleFormatter = $titleFormatter;
		$this->contentLanguage = $contentLanguage;
		$this->genderCache = $genderCache;
		$this->dbProvider = $dbProvider;
		$this->linksMigration = $linksMigration;
		$this->tempUserDetailsLookup = $tempUserDetailsLookup;
		$this->logger = $logger;

		foreach ( $arr as $item ) {
			$this->addObj( $item );
		}
	}

	/**
	 * Set the function name to attribute database queries to, in debug logs.
	 *
	 * @see Wikimedia\Rdbms\SelectQueryBuilder::caller
	 * @since 1.17
	 * @param string $caller
	 * @return self (since 1.32)
	 */
	public function setCaller( $caller ): self {
		$this->caller = $caller;

		return $this;
	}

	/**
	 * Convenience function to add user and user talk pages for a given user to this batch.
	 * Calling {@link execute} will also prefetch the expiration status of temporary accounts
	 * added this way, which is needed for the efficient rendering of user links via UserLinkRenderer.
	 *
	 * @since 1.44
	 */
	public function addUser( UserIdentity $user ): void {
		$this->users[$user->getName()] = $user;

		$this->add( NS_USER, $user->getName() );
		$this->add( NS_USER_TALK, $user->getName() );
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
	 * @param array<int,array<string,mixed>> $array
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

		// Prefetch expiration status for temporary accounts added to this batch via addUser()
		// for efficient user link rendering (T358469).
		if ( count( $this->users ) > 0 ) {
			$this->tempUserDetailsLookup->preloadExpirationStatus( $this->users );
		}

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

				$pageIdentity = PageIdentityValue::localIdentity(
					(int)$row->page_id,
					(int)$row->page_namespace,
					$row->page_title
				);

				$key = CacheKeyHelper::getKeyForPage( $pageIdentity );
				$this->pageIdentities[$key] = $pageIdentity;
			} catch ( InvalidArgumentException ) {
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

					$pageIdentity = PageIdentityValue::localIdentity( 0, (int)$ns, $dbkey );
					$key = CacheKeyHelper::getKeyForPage( $pageIdentity );
					$this->pageIdentities[$key] = $pageIdentity;
				} catch ( InvalidArgumentException ) {
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

		$caller = __METHOD__;
		if ( strval( $this->caller ) !== '' ) {
			$caller .= " (for {$this->caller})";
		}

		// This is similar to LinkHolderArray::replaceInternal
		$dbr = $this->dbProvider->getReplicaDatabase();
		return $dbr->newSelectQueryBuilder()
			->select( LinkCache::getSelectFields() )
			->from( 'page' )
			->where( $this->constructSet( 'page', $dbr ) )
			->caller( $caller )
			->fetchResultSet();
	}

	/**
	 * Do (and cache) {{GENDER:...}} information for userpages in this LinkBatch
	 *
	 * @return bool Whether the query was successful
	 */
	public function doGenderQuery() {
		if ( $this->isEmpty() || !$this->contentLanguage->needsGenderDistinction() ) {
			return false;
		}

		$this->genderCache->doLinkBatch( $this->data, $this->caller );

		return true;
	}

	/**
	 * Construct a WHERE clause which will match all the given titles.
	 *
	 * It is the caller's responsibility to only call this if the LinkBatch is
	 * not empty, because there is no safe way to represent a SQL conditional
	 * for the empty set.
	 *
	 * @param string $prefix The appropriate table's field name prefix ('page', 'pl', etc)
	 * @param ISQLPlatform $db DB object to use
	 * @return string String with SQL where clause fragment
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

/** @deprecated class alias since 1.42 */
class_alias( LinkBatch::class, 'LinkBatch' );
