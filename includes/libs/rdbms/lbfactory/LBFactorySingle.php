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
 * LoadBalancer manager for sites with one "main" cluster using only injected database connections
 *
 * This class assumes that there are no "external" clusters.
 *
 * LoadBalancerDisabled will be used if a null connection handle is injected.
 *
 * @see ILBFactory
 * @ingroup Database
 */
class LBFactorySingle extends LBFactory {
	/** @var LoadBalancerSingle|LoadBalancerDisabled */
	private $mainLB;

	/**
	 * @note Use of {@link newFromConnection} is preferable
	 *
	 * @param array $conf An associative array containing one of the following:
	 *  - connection: The IDatabase connection handle to use; null to disable access
	 */
	public function __construct( array $conf ) {
		parent::__construct( $conf );

		if ( !array_key_exists( 'connection', $conf ) ) {
			throw new InvalidArgumentException( "Missing 'connection' argument." );
		}

		$conn = $conf['connection'];
		if ( $conn ) {
			$mainLB = new LoadBalancerSingle( array_merge(
				$this->baseLoadBalancerParams(),
				[ 'connection' => $conn ]
			) );
		} else {
			$mainLB = new LoadBalancerDisabled( $this->baseLoadBalancerParams() );
		}
		$this->initLoadBalancer( $mainLB );

		$this->mainLB = $mainLB;
	}

	/**
	 * @param IDatabase $db Live connection handle
	 * @param array $params Parameter map to LBFactorySingle::__construct()
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

	/**
	 * @param array $params Parameter map to LBFactorySingle::__construct()
	 * @return LBFactorySingle
	 * @since 1.40
	 */
	public static function newDisabled( array $params = [] ) {
		return new static( array_merge(
			$params,
			[ 'connection' => null ]
		) );
	}

	/** @inheritDoc */
	public function newMainLB( $domain = false ): ILoadBalancerForOwner {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new BadMethodCallException( "Method is not supported." );
	}

	/** @inheritDoc */
	public function getMainLB( $domain = false ): ILoadBalancer {
		return $this->mainLB;
	}

	/** @inheritDoc */
	public function newExternalLB( $cluster ): ILoadBalancerForOwner {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new BadMethodCallException( "Method is not supported." );
	}

	/** @inheritDoc */
	public function getExternalLB( $cluster ): ILoadBalancer {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new BadMethodCallException( "Method is not supported." );
	}

	public function getAllMainLBs(): array {
		return [ self::CLUSTER_MAIN_DEFAULT => $this->mainLB ];
	}

	public function getAllExternalLBs(): array {
		return [];
	}

	/** @inheritDoc */
	protected function getLBsForOwner() {
		if ( $this->mainLB !== null ) {
			yield $this->mainLB;
		}
	}

	public function __destruct() {
		// do nothing since the connection was injected
	}
}
