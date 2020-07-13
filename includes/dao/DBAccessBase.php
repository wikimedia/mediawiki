<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Base class for objects that allow access to other wiki's databases using
 * the foreign database access mechanism implemented by LBFactoryMulti.
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
 * @since 1.21
 *
 * @file
 * @ingroup Database
 *
 * @stable to extend
 * @license GPL-2.0-or-later
 * @author Daniel Kinzler
 */
abstract class DBAccessBase implements IDBAccessObject {
	/** @var ILoadBalancer */
	private $lb;

	/** @var string|bool The target wiki's DB domain */
	protected $dbDomain = false;

	/**
	 * @stable to call
	 *
	 * @param string|bool $dbDomain The target wiki's DB domain
	 */
	public function __construct( $dbDomain = false ) {
		$this->dbDomain = $dbDomain;
		$this->lb = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()
			->getMainLB( $dbDomain );
	}

	/**
	 * Returns a database connection.
	 *
	 * @see LoadBalancer::getConnection()
	 *
	 * @since 1.21
	 *
	 * @param int $id Which connection to use
	 * @param array $groups Query groups
	 *
	 * @return IDatabase
	 */
	protected function getConnection( $id, array $groups = [] ) {
		return $this->getLoadBalancer()->getConnectionRef( $id, $groups, $this->dbDomain );
	}

	/**
	 * Releases a database connection and makes it available for recycling.
	 *
	 * @see LoadBalancer::reuseConnection()
	 *
	 * @since 1.21
	 *
	 * @param IDatabase $db The database connection to release.
	 * @deprecated Since 1.34
	 */
	protected function releaseConnection( IDatabase $db ) {
		// no-op
	}

	/**
	 * Get the database type used for read operations.
	 *
	 * @see MediaWikiServices::getInstance()->getDBLoadBalancer
	 *
	 * @since 1.21
	 *
	 * @return ILoadBalancer The database load balancer object
	 */
	protected function getLoadBalancer() {
		return $this->lb;
	}
}
