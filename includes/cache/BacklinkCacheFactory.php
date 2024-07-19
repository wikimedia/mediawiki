<?php
/**
 * Remember the page that was previously loaded.
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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Page\PageReference;
use Psr\Log\LoggerInterface;
use WANObjectCache;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @since 1.37
 */
class BacklinkCacheFactory {
	/** @var BacklinkCache */
	private $latestBacklinkCache;

	/** @var WANObjectCache */
	private $wanCache;

	/** @var HookContainer */
	private $hookContainer;

	private IConnectionProvider $dbProvider;
	private ServiceOptions $options;
	private LinksMigration $linksMigration;
	private LoggerInterface $logger;

	public function __construct(
		ServiceOptions $options,
		LinksMigration $linksMigration,
		WANObjectCache $wanCache,
		HookContainer $hookContainer,
		IConnectionProvider $dbProvider,
		LoggerInterface $logger
	) {
		$this->options = $options;
		$this->linksMigration = $linksMigration;
		$this->wanCache = $wanCache;
		$this->hookContainer = $hookContainer;
		$this->dbProvider = $dbProvider;
		$this->logger = $logger;
	}

	/**
	 * Returns a BacklinkCache for $page. May re-use previously
	 * created instances.
	 *
	 * Currently, only one cache instance can exist; callers that
	 * need multiple backlink cache objects should keep them in scope.
	 *
	 * @param PageReference $page Page to get a backlink cache for
	 * @return BacklinkCache
	 */
	public function getBacklinkCache( PageReference $page ): BacklinkCache {
		if ( !$this->latestBacklinkCache || !$this->latestBacklinkCache->getPage()->isSamePageAs( $page ) ) {
			$this->latestBacklinkCache = new BacklinkCache(
				$this->options,
				$this->linksMigration,
				$this->wanCache,
				$this->hookContainer,
				$this->dbProvider,
				$this->logger,
				$page
			);
		}
		return $this->latestBacklinkCache;
	}
}
