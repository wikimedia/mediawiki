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
 */

namespace MediaWiki\Page;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Permissions\PermissionManager;
use MovePage;
use NamespaceInfo;
use RepoGroup;
use Title;
use WatchedItemStoreInterface;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @since 1.34
 */
class MovePageFactory {
	/** @var ServiceOptions */
	private $options;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var NamespaceInfo */
	private $nsInfo;

	/** @var WatchedItemStoreInterface */
	private $watchedItems;

	/** @var PermissionManager */
	private $permMgr;

	/** @var RepoGroup */
	private $repoGroup;

	/**
	 * @todo Make this a const when we drop HHVM support (T192166)
	 * @var array
	 */
	public static $constructorOptions = [
		'CategoryCollation',
		'ContentHandlerUseDB',
	];

	public function __construct(
		ServiceOptions $options,
		ILoadBalancer $loadBalancer,
		NamespaceInfo $nsInfo,
		WatchedItemStoreInterface $watchedItems,
		PermissionManager $permMgr,
		RepoGroup $repoGroup
	) {
		$options->assertRequiredOptions( self::$constructorOptions );

		$this->options = $options;
		$this->loadBalancer = $loadBalancer;
		$this->nsInfo = $nsInfo;
		$this->watchedItems = $watchedItems;
		$this->permMgr = $permMgr;
		$this->repoGroup = $repoGroup;
	}

	/**
	 * @param Title $from
	 * @param Title $to
	 * @return MovePage
	 */
	public function newMovePage( Title $from, Title $to ) : MovePage {
		return new MovePage( $from, $to, $this->options, $this->loadBalancer, $this->nsInfo,
			$this->watchedItems, $this->permMgr, $this->repoGroup );
	}
}
