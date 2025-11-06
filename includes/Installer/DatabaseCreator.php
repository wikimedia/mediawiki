<?php

namespace MediaWiki\Installer;

use MediaWiki\Installer\Task\ITaskContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Helper for creating databases
 *
 * @since 1.44
 */
abstract class DatabaseCreator {
	/** @var ITaskContext */
	protected $context;

	/**
	 * @internal use Task::getDatabaseCreator()
	 *
	 * @param ITaskContext $context
	 * @return DatabaseCreator
	 */
	public static function createInstance( ITaskContext $context ): DatabaseCreator {
		$type = $context->getConfigVar( MainConfigNames::DBtype );

		switch ( $type ) {
			case 'mysql':
				return new MysqlDatabaseCreator( $context );
			case 'postgres':
				return new PostgresDatabaseCreator( $context );
			case 'sqlite':
				return new SqliteDatabaseCreator( $context );
			default:
				throw new \InvalidArgumentException( "Unknown DBMS type \"$type\"" );
		}
	}

	protected function __construct( ITaskContext $context ) {
		$this->context = $context;
	}

	/**
	 * Check if a database exists on the local cluster or context
	 *
	 * @param string $database
	 * @return bool
	 */
	abstract public function existsLocally( $database );

	/**
	 * Check if a database exists in the specified LoadBalancer which may be
	 * for an external cluster.
	 *
	 * @param ILoadBalancer $loadBalancer
	 * @param string $database
	 * @return bool
	 */
	abstract public function existsInLoadBalancer( ILoadBalancer $loadBalancer, $database );

	/**
	 * Create a database in the local cluster or install context
	 *
	 * @param string $database
	 * @return Status
	 */
	abstract public function createLocally( $database ): Status;

	/**
	 * Create a database in the specified LoadBalancer which may be for an
	 * external cluster.
	 *
	 * @param ILoadBalancer $loadBalancer
	 * @param string $database
	 * @return Status
	 */
	abstract public function createInLoadBalancer( ILoadBalancer $loadBalancer, $database ): Status;
}
