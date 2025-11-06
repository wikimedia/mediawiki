<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms\Database;

/**
 * @ingroup Database
 */
interface IDatabaseFlags {

	/** Do not remember the prior flags */
	public const REMEMBER_NOTHING = '';
	/** Remember the prior flags */
	public const REMEMBER_PRIOR = 'remember';
	/** Restore to the prior flag state */
	public const RESTORE_PRIOR = 'prior';
	/** Restore to the initial flag state */
	public const RESTORE_INITIAL = 'initial';

	/** Enable debug logging of all SQL queries */
	public const DBO_DEBUG = 1;
	/** Unused since 1.34 */
	public const DBO_NOBUFFER = 2;
	/** Unused since 1.31 */
	public const DBO_IGNORE = 4;
	/** Automatically start a transaction before running a query if none is active */
	public const DBO_TRX = 8;
	/** Join load balancer transaction rounds (which control DBO_TRX) in non-CLI mode */
	public const DBO_DEFAULT = 16;
	/** Use DB persistent connections if possible */
	public const DBO_PERSISTENT = 32;
	/** DBA session mode; was used by Oracle */
	public const DBO_SYSDBA = 64;
	/** Schema file mode; was used by Oracle */
	public const DBO_DDLMODE = 128;
	/**
	 * Enable SSL/TLS in connection protocol
	 * @deprecated since 1.39 use 'ssl' parameter
	 */
	public const DBO_SSL = 256;
	/** Enable compression in connection protocol */
	public const DBO_COMPRESS = 512;
	/** Optimize connection for guaging server state (e.g. ILoadBalancer::CONN_UNTRACKED_GAUGE) */
	public const DBO_GAUGE = 1024;

	/**
	 * Set a flag for this connection
	 *
	 * @param int $flag One of (IDatabase::DBO_DEBUG, IDatabase::DBO_TRX)
	 * @param string $remember IDatabase::REMEMBER_* constant [default: REMEMBER_NOTHING]
	 */
	public function setFlag( $flag, $remember = self::REMEMBER_NOTHING );

	/**
	 * Clear a flag for this connection
	 *
	 * @param int $flag One of (IDatabase::DBO_DEBUG, IDatabase::DBO_TRX)
	 * @param string $remember IDatabase::REMEMBER_* constant [default: REMEMBER_NOTHING]
	 */
	public function clearFlag( $flag, $remember = self::REMEMBER_NOTHING );

	/**
	 * Restore the flags to their prior state before the last setFlag/clearFlag call
	 *
	 * @param string $state IDatabase::RESTORE_* constant. [default: RESTORE_PRIOR]
	 * @since 1.28
	 */
	public function restoreFlags( $state = self::RESTORE_PRIOR );

	/**
	 * Returns a boolean whether the flag $flag is set for this connection
	 *
	 * @param int $flag One of the class IDatabase::DBO_* constants
	 * @return bool
	 */
	public function getFlag( $flag );
}
