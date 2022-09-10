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
namespace Wikimedia\Rdbms;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * Manage a single hardcoded database connection.
 *
 * @ingroup Database
 */
class LBFactorySingle extends LBFactory {
	/** @var LoadBalancerSingle */
	private $lb;

	/**
	 * You probably want to use {@link newFromConnection} instead.
	 *
	 * @param array $conf An associative array with one member:
	 *  - connection: The IDatabase connection object
	 */
	public function __construct( array $conf ) {
		parent::__construct( $conf );

		if ( !isset( $conf['connection'] ) ) {
			throw new InvalidArgumentException( "Missing 'connection' argument." );
		}

		$lb = new LoadBalancerSingle( array_merge(
			$this->baseLoadBalancerParams(),
			$conf
		) );
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
		return new static( array_merge(
			[ 'localDomain' => $db->getDomainID() ],
			$params,
			[ 'connection' => $db ]
		) );
	}

	public function newMainLB( $domain = false ): ILoadBalancerForOwner {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new BadMethodCallException( "Method is not supported." );
	}

	public function getMainLB( $domain = false ): ILoadBalancer {
		return $this->lb;
	}

	public function newExternalLB( $cluster ): ILoadBalancerForOwner {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new BadMethodCallException( "Method is not supported." );
	}

	public function getExternalLB( $cluster ): ILoadBalancer {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new BadMethodCallException( "Method is not supported." );
	}

	public function getAllMainLBs(): array {
		return [ self::CLUSTER_MAIN_DEFAULT => $this->lb ];
	}

	public function getAllExternalLBs(): array {
		return [];
	}

	public function forEachLB( $callback, array $params = [] ) {
		wfDeprecated( __METHOD__, '1.39' );
		if ( isset( $this->lb ) ) { // may not be set during _destruct()
			$callback( $this->lb, ...$params );
		}
	}

	protected function getLBsForOwner() {
		if ( isset( $this->lb ) ) { // may not be set during _destruct()
			yield $this->lb;
		}
	}

	public function __destruct() {
		// do nothing since the connection was injected
	}
}
