<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use InvalidArgumentException;

/**
 * Trivial LoadBalancer that always returns an injected connection handle.
 *
 * @ingroup Database
 */
class LoadBalancerSingle extends LoadBalancer {
	/** @var Database */
	private $conn;

	/**
	 * You probably want to use {@link newFromConnection} instead.
	 *
	 * @param array $params An associative array with one member:
	 *   - connection: An IDatabase connection object
	 */
	public function __construct( array $params ) {
		/** @var Database $conn */
		$conn = $params['connection'] ?? null;
		if ( !$conn ) {
			throw new InvalidArgumentException( "Missing 'connection' argument." );
		}

		$this->conn = $conn;

		parent::__construct( [
			'servers' => [ [
				'type' => $conn->getType(),
				'host' => $conn->getServer(),
				'dbname' => $conn->getDBname(),
				'load' => 1,
			] ],
			'trxProfiler' => $params['trxProfiler'] ?? null,
			'srvCache' => $params['srvCache'] ?? null,
			'wanCache' => $params['wanCache'] ?? null,
			'localDomain' => $params['localDomain'] ?? $this->conn->getDomainID(),
			'readOnlyReason' => $params['readOnlyReason'] ?? false,
			'clusterName' => $params['clusterName'] ?? null,
		] );

		if ( isset( $params['readOnlyReason'] ) ) {
			$conn->setLBInfo( $conn::LB_READ_ONLY_REASON, $params['readOnlyReason'] );
		}
	}

	/**
	 * @param IDatabase $db Live connection handle
	 * @param array $params Parameter map to LoadBalancerSingle::__constructs()
	 * @since 1.28
	 */
	public static function newFromConnection( IDatabase $db, array $params = [] ): static {
		return new static( [
			'localDomain' => $db->getDomainID(),
			...$params,
			'connection' => $db,
		] );
	}

	/** @inheritDoc */
	protected function sanitizeConnectionFlags( $flags, $domain ) {
		// There is only one underlying connection handle. Also, this class is only meant to
		// be used during situations like site installation, where there should be no contenting
		// connections, and integration testing, where everything uses temporary tables.
		$flags &= ~self::CONN_TRX_AUTOCOMMIT;

		return $flags;
	}

	/** @inheritDoc */
	protected function reallyOpenConnection( $i, DatabaseDomain $domain, array $lbInfo ) {
		foreach ( $lbInfo as $k => $v ) {
			$this->conn->setLBInfo( $k, $v );
		}

		return $this->conn;
	}

	public function __destruct() {
		// do nothing since the connection was injected
	}
}
