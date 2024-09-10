<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Installer\ConnectionStatus;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use RuntimeException;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * Base class for installer tasks
 *
 * @internal For use by the installer
 */
abstract class Task {
	/** @var ITaskContext|null */
	private $context;
	/** @var string|null */
	private $schemaBasePath;

	/**
	 * Execute the task
	 *
	 * @return Status
	 */
	abstract public function execute(): Status;

	/**
	 * Get the symbolic name of the task.
	 *
	 * @return string
	 */
	abstract public function getName();

	/**
	 * Override this to return true to skip the task. If this returns true,
	 * execute() will not be called, and start/end messages will not be
	 * produced.
	 *
	 * @return bool
	 */
	public function isSkipped(): bool {
		return false;
	}

	/**
	 * Get alternative names of this task. These aliases can be used to fulfill
	 * dependencies of other tasks.
	 *
	 * @return string|string[]
	 */
	public function getAliases() {
		return [];
	}

	/**
	 * Get a list of names or aliases of tasks that must be done prior to this task.
	 *
	 * @return string|string[]
	 */
	public function getDependencies() {
		return [];
	}

	/**
	 * Get a list of names of objects that this task promises to provide
	 * via $this->getContext()->provide().
	 *
	 * If this is non-empty, the task is a scheduled provider, which means that
	 * it is not persistently complete after it has been run. If installation
	 * is interrupted, it might need to be run again.
	 *
	 * @return string|string[]
	 */
	public function getProvidedNames() {
		return [];
	}

	/**
	 * Inject the base class dependencies and configuration
	 *
	 * @param ITaskContext $context
	 * @param string $schemaBasePath
	 */
	final public function initBase(
		ITaskContext $context,
		string $schemaBasePath
	) {
		$this->context = $context;
		$this->schemaBasePath = $schemaBasePath;
	}

	/**
	 * Get the execution context. This will throw if initBase() has not been called.
	 *
	 * @return ITaskContext
	 */
	protected function getContext(): ITaskContext {
		return $this->context;
	}

	/**
	 * Get a configuration variable for the wiki being created.
	 * The name should not have a "wg" prefix.
	 *
	 * @param string $name
	 * @return mixed
	 */
	protected function getConfigVar( string $name ) {
		return $this->getContext()->getConfigVar( $name );
	}

	/**
	 * Get an installer option value.
	 *
	 * @param string $name
	 * @return mixed
	 */
	protected function getOption( string $name ) {
		return $this->getContext()->getOption( $name );
	}

	/**
	 * Connect to the database for a specified purpose
	 *
	 * @param string $type One of the self::CONN_* constants. Using CONN_DONT_KNOW
	 *   is deprecated and will cause an exception to be thrown in a future release.
	 * @return ConnectionStatus
	 */
	protected function getConnection( $type = ITaskContext::CONN_DONT_KNOW ): ConnectionStatus {
		return $this->getContext()->getConnection( $type );
	}

	/**
	 * Get a database connection, and throw if a connection could not be
	 * obtained. This is for the convenience of callers which expect a
	 * connection to already be cached.
	 *
	 * @param string $type
	 * @return IMaintainableDatabase
	 */
	protected function definitelyGetConnection( $type ): IMaintainableDatabase {
		$status = $this->getConnection( $type );
		if ( !$status->isOK() ) {
			throw new RuntimeException( __METHOD__ . ': unexpected DB connection error' );
		}
		return $status->getDB();
	}

	/**
	 * Apply a SQL source file to the database as part of running an installation step.
	 *
	 * @param IMaintainableDatabase $conn
	 * @param string $relPath
	 * @return Status
	 */
	protected function applySourceFile( $conn, $relPath ) {
		$path = $this->getSqlFilePath( $relPath );
		$status = Status::newGood();
		try {
			$conn->doAtomicSection( __METHOD__,
				static function () use ( $conn, $path ) {
					$conn->sourceFile( $path );
				},
				IDatabase::ATOMIC_CANCELABLE
			);
		} catch ( DBQueryError $e ) {
			$status->fatal( "config-install-tables-failed", $e->getMessage() );
		}
		return $status;
	}

	/**
	 * Get the absolute base path for SQL schema files.
	 *
	 * For core tasks, this is $IP/maintenance. For extension tasks, this will
	 * be some directory in the extension's source tree.
	 *
	 * @return string
	 */
	protected function getSchemaBasePath(): string {
		return $this->schemaBasePath;
	}

	/**
	 * Return a path to the DBMS-specific SQL file if it exists,
	 * otherwise default SQL file. The path should be relative to the core or
	 * extension schema base path.
	 *
	 * @param string $filename
	 * @return string
	 */
	protected function getSqlFilePath( string $filename ) {
		$type = $this->getContext()->getDbType();
		$base = $this->getSchemaBasePath();
		$dbmsSpecificFilePath = "$base/$type/$filename";
		if ( file_exists( $dbmsSpecificFilePath ) ) {
			return $dbmsSpecificFilePath;
		} else {
			return "$base/$filename";
		}
	}

	/**
	 * Get the restored services. Subclasses that want to call this must declare
	 * a dependency on "services".
	 *
	 * @return MediaWikiServices
	 */
	public function getServices(): MediaWikiServices {
		$this->assertDependsOn( 'services' );
		return $this->getContext()->getProvision( 'services' );
	}

	/**
	 * Get a HookContainer suitable for calling LoadExtensionSchemaUpdates.
	 * Subclasses that want to call this must declare a dependency on
	 * "HookContainer".
	 *
	 * @return HookContainer
	 */
	public function getHookContainer(): HookContainer {
		$this->assertDependsOn( 'HookContainer' );
		return $this->getContext()->getProvision( 'HookContainer' );
	}

	/*
	 * Get the array of database virtual domains declared in extensions.
	 * Subclasses that want to call this must declare a dependency on
	 * "VirtualDomains".
	 *
	 * @return array
	 */
	public function getVirtualDomains(): array {
		$this->assertDependsOn( 'VirtualDomains' );
		return $this->getContext()->getProvision( 'VirtualDomains' );
	}

	private function assertDependsOn( $dependency ) {
		$deps = (array)$this->getDependencies();
		if ( !in_array( $dependency, $deps, true ) ) {
			throw new \RuntimeException( 'Task class "' . static::class . '" ' .
				"does not declare a dependency on \"$dependency\"" );
		}
	}
}
