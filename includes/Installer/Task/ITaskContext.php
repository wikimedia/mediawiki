<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Installer\ConnectionStatus;

/**
 * Dependency bundle and execution context for installer tasks.
 *
 * For most things in here, the Task base class provides more convenient access.
 *
 * @since 1.44
 */
interface ITaskContext {
	/**
	 * A connection for creating DBs, suitable for pre-installation.
	 */
	public const CONN_CREATE_DATABASE = 'create-database';

	/**
	 * A connection to the new DB, for creating schemas and other similar
	 * objects in the new DB.
	 */
	public const CONN_CREATE_SCHEMA = 'create-schema';

	/**
	 * A connection with a role suitable for creating tables.
	 */
	public const CONN_CREATE_TABLES = 'create-tables';

	/**
	 * Legacy default connection type. Before MW 1.43, getConnection() with no
	 * parameters would return the cached connection. The state (especially the
	 * selected domain) would depend on the previously executed install steps.
	 * Using this constant tries to reproduce this behaviour.
	 *
	 * @deprecated since 1.43
	 */
	public const CONN_DONT_KNOW = 'dont-know';

	/**
	 * Get a MediaWiki configuration value for the wiki being created.
	 * The name should not have a "wg" prefix.
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getConfigVar( string $name );

	/**
	 * Get a named installer option
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getOption( string $name );

	/**
	 * Connect to the database for a specified purpose
	 *
	 * @param string $type One of the self::CONN_* constants. Using CONN_DONT_KNOW
	 *   is deprecated and will cause an exception to be thrown in a future release.
	 * @return ConnectionStatus
	 */
	public function getConnection( $type = self::CONN_DONT_KNOW ): ConnectionStatus;

	/**
	 * Get the selected database type name.
	 *
	 * @return string
	 */
	public function getDbType(): string;

	/**
	 * Store an object to be used by a later task
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function provide( string $name, $value );

	/**
	 * Get the object stored by provide()
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getProvision( string $name );

	/**
	 * Get schema variables
	 *
	 * @return array
	 */
	public function getSchemaVars();
}
