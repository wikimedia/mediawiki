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
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * A foreign repository with a MediaWiki database accessible via the configured LBFactory
 *
 * @ingroup FileRepo
 */
class ForeignDBViaLBRepo extends LocalRepo {
	/** @var string */
	protected $wiki;

	/** @var string */
	protected $dbName;

	/** @var string */
	protected $tablePrefix;

	/** @var array */
	protected $fileFactory = [ ForeignDBFile::class, 'newFromTitle' ];

	/** @var array */
	protected $fileFromRowFactory = [ ForeignDBFile::class, 'newFromRow' ];

	/** @var bool */
	protected $hasSharedCache;

	/**
	 * @param array|null $info
	 */
	function __construct( $info ) {
		parent::__construct( $info );
		$this->wiki = $info['wiki'];
		list( $this->dbName, $this->tablePrefix ) = wfSplitWikiID( $this->wiki );
		$this->hasSharedCache = $info['hasSharedCache'];
	}

	/**
	 * @return IDatabase
	 */
	function getMasterDB() {
		return $this->getDBLoadBalancer()->getConnectionRef( DB_MASTER, [], $this->wiki );
	}

	/**
	 * @return IDatabase
	 */
	function getReplicaDB() {
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
	 * @return LoadBalancer
	 */
	protected function getDBLoadBalancer() {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		return $lbFactory->getMainLB( $this->wiki );
	}

	function hasSharedCache() {
		return $this->hasSharedCache;
	}

	/**
	 * Get a key on the primary cache for this repository.
	 * Returns false if the repository's cache is not accessible at this site.
	 * The parameters are the parts of the key, as for wfMemcKey().
	 * @return bool|string
	 */
	function getSharedCacheKey( /*...*/ ) {
		if ( $this->hasSharedCache() ) {
			$args = func_get_args();
			array_unshift( $args, $this->wiki );

			return implode( ':', $args );
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
