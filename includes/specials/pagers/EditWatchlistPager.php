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
 * @ingroup Pager
 */

namespace MediaWiki\Pager;

use MediaWiki\Context\IContextSource;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\ResultWrapper;

/**
 * @ingroup Pager
 */
class EditWatchlistPager extends AlphabeticPager {
	/**
	 * @codeCoverageIgnore
	 * @param IContextSource $context
	 * @param WatchedItemStoreInterface $wis
	 * @param NamespaceInfo $namespaceInfo
	 */
	public function __construct(
		IContextSource $context, protected WatchedItemStoreInterface $wis, protected NamespaceInfo $namespaceInfo,
	) {
		parent::__construct( $context, null );
	}

	/**
	 * @codeCoverageIgnore
	 * @inheritDoc
	 */
	public function formatRow( $row ): string {
		// never used, return an empty string for method signature compatibility
		return '';
	}

	/**
	 * Not used because we override reallyDoQuery(), but parent insists that it is implemented
	 *
	 * @inheritDoc
	 */
	public function getQueryInfo(): array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function getIndexField(): array {
		return [
			'title' => [ 'wl_namespace', 'wl_title' ],
		];
	}

	private function getNamespacesList(): array {
		$namespace = $this->mRequest->getIntOrNull( 'namespace' );
		if ( $namespace !== null ) {
			$namespaces = [ $namespace ];
		} else {
			$namespaces = array_values( $this->namespaceInfo->getSubjectNamespaces() );
		}
		return $namespaces;
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $offset Index offset, inclusive
	 * @param int $limit Exact query limit
	 * @param bool $order IndexPager::QUERY_ASCENDING or IndexPager::QUERY_DESCENDING
	 * @return ResultWrapper
	 */
	public function reallyDoQuery( $offset, $limit, $order ) {
		$options = [
			'limit' => $limit,
			'sort' => $order == IndexPager::QUERY_DESCENDING
				? WatchedItemStoreInterface::SORT_DESC
				: WatchedItemStoreInterface::SORT_ASC,
			'namespaces' => $this->getNamespacesList(),
		];
		[ $offsetConds, ] = $this->getOffsetCondsAndSortOptions( $offset, $limit, $order );
		$options['offsetConds'] = is_array( $offsetConds ) ? $offsetConds : [ $offsetConds ];
		$watchedItems = $this->wis->getWatchedItemsForUser( $this->getUser(), $options );
		return $this->watchedItemArrayToResults( $watchedItems );
	}

	/**
	 * Prune and re-sort the results
	 *
	 * @return IResultWrapper
	 */
	public function getOrderedResult(): IResultWrapper {
		$rows = [];
		# Don't use any extra rows returned by the query
		$numRows = min( $this->mResult->numRows(), $this->mLimit );
		if ( $numRows ) {
			if ( $this->mIsBackwards ) {
				for ( $i = $numRows - 1; $i >= 0; $i-- ) {
					$this->mResult->seek( $i );
					$rows[] = $this->mResult->fetchObject();
				}
			} else {
				$this->mResult->seek( 0 );
				for ( $i = 0; $i < $numRows; $i++ ) {
					$rows[] = $this->mResult->fetchObject();
				}
			}
		}
		return new FakeResultWrapper( $rows );
	}

	/**
	 * @param array $watchedItems
	 * @return ResultWrapper
	 */
	private function watchedItemArrayToResults( array $watchedItems ): ResultWrapper {
		$titles = [];
		foreach ( $watchedItems as $watchedItem ) {
			$titles[] = [
				'wl_namespace' => $watchedItem->getTarget()->getNamespace(),
				'wl_title' => $watchedItem->getTarget()->getDBkey(),
				'we_expiry' => $watchedItem->getExpiry(),
				'expiryInDaysText' => $watchedItem->getExpiryInDaysText( $this->getContext() )
			];
		}
		return new FakeResultWrapper( $titles );
	}
}
