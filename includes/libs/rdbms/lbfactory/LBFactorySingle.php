<?php
/**
 * Simple generator of database connections that always returns the same object.
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
 * @ingroup Database
 */

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use BadMethodCallException;

/**
 * An LBFactory class that always returns a single database object.
 */
class LBFactorySingle extends LBFactory {
	/** @var LoadBalancerSingle */
	private $lb;

	/**
	 * @param array $conf An associative array with one member:
	 *  - connection: The IDatabase connection object
	 */
	public function __construct( array $conf ) {
		parent::__construct( $conf );

		if ( !isset( $conf['connection'] ) ) {
			throw new InvalidArgumentException( "Missing 'connection' argument." );
		}

		$lb = new LoadBalancerSingle( array_merge( $this->baseLoadBalancerParams(), $conf ) );
		$this->initLoadBalancer( $lb );
		$this->lb = $lb;
	}

	/**
	 * @param IDatabase $db Live connection handle
	 * @param array $params Parameter map to LBFactorySingle::__constructs()
	 * @return LBFactorySingle
	 * @since 1.28
	 */
	public static function newFromConnection( IDatabase $db, array $params = [] ) {
		return new static( [ 'connection' => $db ] + $params );
	}

	/**
	 * @param bool|string $domain (unused)
	 * @return LoadBalancerSingle
	 */
	public function newMainLB( $domain = false ) {
		return $this->lb;
	}

	/**
	 * @param bool|string $domain (unused)
	 * @return LoadBalancerSingle
	 */
	public function getMainLB( $domain = false ) {
		return $this->lb;
	}

	public function newExternalLB( $cluster ) {
		throw new BadMethodCallException( "Method is not supported." );
	}

	public function getExternalLB( $cluster ) {
		throw new BadMethodCallException( "Method is not supported." );
	}

	/**
	 * @return LoadBalancerSingle[] Map of (cluster name => LoadBalancer)
	 */
	public function getAllMainLBs() {
		return [ 'DEFAULT' => $this->lb ];
	}

	/**
	 * @return LoadBalancerSingle[] Map of (cluster name => LoadBalancer)
	 */
	public function getAllExternalLBs() {
		return [];
	}

	/**
	 * @param string|callable $callback
	 * @param array $params
	 */
	public function forEachLB( $callback, array $params = [] ) {
		if ( isset( $this->lb ) ) { // may not be set during _destruct()
			call_user_func_array( $callback, array_merge( [ $this->lb ], $params ) );
		}
	}
}
