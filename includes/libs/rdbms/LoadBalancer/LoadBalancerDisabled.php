<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use RuntimeException;

/**
 * Placeholder LoadBalancer that throws an error upon attempts to access connections
 *
 * This is useful when running code with no config file present, e.g. during installation.
 *
 * @since 1.40
 * @ingroup Database
 */
class LoadBalancerDisabled extends LoadBalancer {

	public function __construct( array $params = [] ) {
		parent::__construct( [
			'servers' => [ [
				'type' => 'disabled',
				'host' => '(disabled)',
				'dbname' => null,
				'load' => 1,
			] ],
			'trxProfiler' => $params['trxProfiler'] ?? null,
			'srvCache' => $params['srvCache'] ?? null,
			'wanCache' => $params['wanCache'] ?? null,
			'localDomain' => $params['localDomain'] ?? '(disabled)',
			'readOnlyReason' => $params['readOnlyReason'] ?? false,
			'clusterName' => $params['clusterName'] ?? null,
		] );
	}

	/**
	 * @param int $i
	 * @param DatabaseDomain $domain
	 * @param array $lbInfo
	 *
	 * @return never
	 */
	protected function reallyOpenConnection( $i, DatabaseDomain $domain, array $lbInfo ): never {
		throw new RuntimeException( 'Database backend disabled' );
	}

	/**
	 * @param int $i Specific (overrides $groups) or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants
	 *
	 * @return never
	 */
	public function getConnection( $i, $groups = [], string|false $domain = false, $flags = 0 ): never {
		throw new RuntimeException( 'Database backend disabled' );
	}

	/**
	 * @internal Only to be used by DBConnRef
	 * @param int $i Specific (overrides $groups) or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants (e.g. CONN_TRX_AUTOCOMMIT)
	 * @return never
	 */
	public function getConnectionInternal( $i, $groups = [], $domain = false, $flags = 0 ): never {
		throw new RuntimeException( 'Database backend disabled' );
	}

	/**
	 * @param int $i Specific (overrides $groups) or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants
	 *
	 * @return never
	 */
	public function getMaintenanceConnectionRef( $i, $groups = [], $domain = false, $flags = 0 ): never {
		throw new RuntimeException( 'Database backend disabled' );
	}

}
