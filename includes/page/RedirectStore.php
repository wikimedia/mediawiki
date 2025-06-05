<?php
/**
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
 * @author Derick Alangi
 */

namespace MediaWiki\Page;

use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleParser;
use MediaWiki\Title\TitleValue;
use Psr\Log\LoggerInterface;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Service for storing and retrieving page redirect information.
 *
 * @unstable
 * @since 1.38
 */
class RedirectStore implements RedirectLookup {
	private IConnectionProvider $dbProvider;
	private PageLookup $pageLookup;
	private TitleParser $titleParser;
	private RepoGroup $repoGroup;
	private LoggerInterface $logger;
	private MapCacheLRU $procCache;

	public function __construct(
		IConnectionProvider $dbProvider,
		PageLookup $pageLookup,
		TitleParser $titleParser,
		RepoGroup $repoGroup,
		LoggerInterface $logger
	) {
		$this->dbProvider = $dbProvider;
		$this->pageLookup = $pageLookup;
		$this->titleParser = $titleParser;
		$this->repoGroup = $repoGroup;
		$this->logger = $logger;
		// Must be 500+ for QueryPage and Pager uses to be effective
		$this->procCache = new MapCacheLRU( 1_000 );
	}

	public function getRedirectTarget( PageIdentity $page ): ?LinkTarget {
		$cacheKey = self::makeCacheKey( $page );
		$cachedValue = $this->procCache->get( $cacheKey );
		if ( $cachedValue !== null ) {
			return $cachedValue ?: null;
		}

		// Handle redirects for files included from foreign image repositories.
		if ( $page->getNamespace() === NS_FILE ) {
			$file = $this->repoGroup->findFile( $page );
			if ( $file && !$file->isLocal() ) {
				$from = $file->getRedirected();
				$to = $file->getName();
				if ( $from === null || $from === $to ) {
					$this->procCache->set( $cacheKey, false );
					return null;
				}

				$target = new TitleValue( NS_FILE, $to );
				$this->procCache->set( $cacheKey, $target );
				return $target;
			}
		}

		$page = $this->pageLookup->getPageByReference( $page );
		if ( $page === null || !$page->isRedirect() ) {
			$this->procCache->set( $cacheKey, false );
			return null;
		}

		$dbr = $this->dbProvider->getReplicaDatabase();
		$row = $dbr->newSelectQueryBuilder()
			->select( [ 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ] )
			->from( 'redirect' )
			->where( [ 'rd_from' => $page->getId() ] )
			->caller( __METHOD__ )
			->fetchRow();

		if ( !$row ) {
			$this->logger->info(
				'Found inconsistent redirect status; probably the page was deleted after it was loaded'
			);
			$this->procCache->set( $cacheKey, false );
			return null;
		}

		$target = $this->createRedirectTarget(
			$row->rd_namespace,
			$row->rd_title,
			$row->rd_fragment,
			$row->rd_interwiki
		);

		$this->procCache->set( $cacheKey, $target );
		return $target;
	}

	/**
	 * Update the redirect target for a page.
	 *
	 * @param PageIdentity $page The page to update the redirect target for.
	 * @param LinkTarget|null $target The new redirect target, or `null` if this is not a redirect.
	 * @param bool|null $lastRevWasRedirect Whether the last revision was a redirect, or `null`
	 * if not known. If set, this allows eliding writes to the redirect table.
	 *
	 * @return bool `true` on success, `false` on failure.
	 */
	public function updateRedirectTarget(
		PageIdentity $page,
		?LinkTarget $target,
		?bool $lastRevWasRedirect = null
	) {
		// Always update redirects (target link might have changed)
		// Update/Insert if we don't know if the last revision was a redirect or not
		// Delete if changing from redirect to non-redirect
		$isRedirect = $target !== null;
		$cacheKey = self::makeCacheKey( $page );

		if ( !$isRedirect && $lastRevWasRedirect === false ) {
			$this->procCache->set( $cacheKey, false );
			return true;
		}

		if ( $isRedirect ) {
			$rt = Title::newFromLinkTarget( $target );
			if ( !$rt->isValidRedirectTarget() ) {
				// Don't put a bad redirect into the database (T278367)
				$this->procCache->set( $cacheKey, false );
				return false;
			}

			$dbw = $this->dbProvider->getPrimaryDatabase();
			$dbw->startAtomic( __METHOD__ );

			$truncatedFragment = self::truncateFragment( $rt->getFragment() );
			$dbw->newInsertQueryBuilder()
				->insertInto( 'redirect' )
				->row( [
					'rd_from' => $page->getId(),
					'rd_namespace' => $rt->getNamespace(),
					'rd_title' => $rt->getDBkey(),
					'rd_fragment' => $truncatedFragment,
					'rd_interwiki' => $rt->getInterwiki(),
				] )
				->onDuplicateKeyUpdate()
				->uniqueIndexFields( [ 'rd_from' ] )
				->set( [
					'rd_namespace' => $rt->getNamespace(),
					'rd_title' => $rt->getDBkey(),
					'rd_fragment' => $truncatedFragment,
					'rd_interwiki' => $rt->getInterwiki(),
				] )
				->caller( __METHOD__ )
				->execute();

			$dbw->endAtomic( __METHOD__ );

			$this->procCache->set(
				$cacheKey,
				$this->createRedirectTarget(
					$rt->getNamespace(),
					$rt->getDBkey(),
					$truncatedFragment,
					$rt->getInterwiki()
				)
			);
		} else {
			$dbw = $this->dbProvider->getPrimaryDatabase();
			// This is not a redirect, remove row from redirect table
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'redirect' )
				->where( [ 'rd_from' => $page->getId() ] )
				->caller( __METHOD__ )
				->execute();

			$this->procCache->set( $cacheKey, false );
		}

		if ( $page->getNamespace() === NS_FILE ) {
			$this->repoGroup->getLocalRepo()->invalidateImageRedirect( $page );
		}

		return true;
	}

	/**
	 * Clear process-cached redirect information for a page.
	 *
	 * @param LinkTarget|PageIdentity $page The page to clear the cache for.
	 * @return void
	 */
	public function clearCache( $page ) {
		$this->procCache->clear( self::makeCacheKey( $page ) );
	}

	/**
	 * Create a process cache key for the given page.
	 * @param LinkTarget|PageIdentity $page The page to create a cache key for.
	 * @return string Cache key.
	 */
	private static function makeCacheKey( $page ) {
		return "{$page->getNamespace()}:{$page->getDBkey()}";
	}

	/**
	 * Create a LinkTarget appropriate for use as a redirect target.
	 *
	 * @param int $namespace The namespace of the article
	 * @param string $title Database key form
	 * @param string $fragment The link fragment (after the "#")
	 * @param string $interwiki Interwiki prefix
	 *
	 * @return LinkTarget|null `LinkTarget`, or `null` if this is not a valid redirect
	 */
	private function createRedirectTarget( $namespace, $title, $fragment, $interwiki ): ?LinkTarget {
		// (T203942) We can't redirect to Media namespace because it's virtual.
		// We don't want to modify Title objects farther down the
		// line. So, let's fix this here by changing to File namespace.
		if ( $namespace == NS_MEDIA ) {
			$namespace = NS_FILE;
		}

		// mimic behaviour of self::insertRedirectEntry for fragments that didn't
		// come from the redirect table
		$fragment = self::truncateFragment( $fragment );

		// T261347: be defensive when fetching data from the redirect table.
		// Use Title::makeTitleSafe(), and if that returns null, ignore the
		// row. In an ideal world, the DB would be cleaned up after a
		// namespace change, but nobody could be bothered to do that.
		$target = $this->titleParser->makeTitleValueSafe( $namespace, $title, $fragment, $interwiki );
		if ( $target !== null && Title::newFromLinkTarget( $target )->isValidRedirectTarget() ) {
			return $target;
		}

		return null;
	}

	/**
	 * Truncate link fragment to maximum storable value
	 *
	 * @param string $fragment The link fragment (after the "#")
	 * @return string
	 */
	private static function truncateFragment( $fragment ) {
		return mb_strcut( $fragment, 0, 255 );
	}
}
