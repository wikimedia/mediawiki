<?php
/**
 * A foreign repository with a MediaWiki database accessible via the configured LBFactory.
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
 * @ingroup FileRepo
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * A foreign repository with a MediaWiki database accessible via the configured LBFactory
 *
 * @ingroup FileRepo
 */
class ForeignDBViaLBRepo extends LocalRepo {
	/** @var string */
	protected $wiki;

	/** @var array */
	protected $fileFactory = [ ForeignDBFile::class, 'newFromTitle' ];

	/** @var array */
	protected $fileFromRowFactory = [ ForeignDBFile::class, 'newFromRow' ];

	/** @var bool */
	protected $hasSharedCache;

	/**
	 * @param array|null $info
	 */
	public function __construct( $info ) {
		parent::__construct( $info );
		'@phan-var array $info';
		$this->wiki = $info['wiki'];
		$this->hasSharedCache = $info['hasSharedCache'];
	}

	public function getMasterDB() {
		return $this->getDBLoadBalancer()->getConnectionRef( DB_MASTER, [], $this->wiki );
	}

	public function getReplicaDB() {
		return $this->getDBLoadBalancer()->getConnectionRef( DB_REPLICA, [], $this->wiki );
	}

	/**
	 * @return Closure
	 */
	protected function getDBFactory() {
		return function ( $index ) {
			return $this->getDBLoadBalancer()->getConnectionRef( $index, [], $this->wiki );
		};
	}

	/**
	 * @return ILoadBalancer
	 */
	protected function getDBLoadBalancer() {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		return $lbFactory->getMainLB( $this->wiki );
	}

	private function hasSharedCache() {
		return $this->hasSharedCache;
	}

	public function getSharedCacheKey( ...$args ) {
		if ( $this->hasSharedCache() ) {
			return $this->wanCache->makeGlobalKey( $this->wiki, ...$args );
		} else {
			return false;
		}
	}

	protected function assertWritableRepo() {
		throw new MWException( static::class . ': write operations are not supported.' );
	}

	public function getInfo() {
		return FileRepo::getInfo();
	}
}
