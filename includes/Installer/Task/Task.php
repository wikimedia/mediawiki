<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Installer\ConnectionStatus;
use MediaWiki\Installer\DatabaseCreator;
use MediaWiki\Language\RawMessage;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Status\Status;
use RuntimeException;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * Base class for installer tasks
 *
 * @stable to extend
 * @since 1.44
 */
abstract class Task {
	/** @var ITaskContext|null */
	private $context;
	/** @var string|null */
	private $schemaBasePath;

	/**
	 * Execute the task.
	 *
	 * Notes for implementors:
	 *  - Unless the task is registered with a specific profile, tasks will run
	 *    in both installPreConfigured.php and the traditional unconfigured
	 *    environment. The global state differs between these environments.
	 *
	 *  - Tasks almost always have dependencies. Override getDependencies().
	 *
	 *  - If you need MediaWikiServices, declare a dependency on 'services' and
	 *    use getServices(). The dependency ensures that the task is run when
	 *    the global service container is functional.
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
	 * Get a human-readable description of what this task does, for use as a
	 * progress message. This may either be English text or a MessageSpecifier.
	 * It is unsafe to use an extension message.
	 *
	 * @stable to override
	 * @return MessageSpecifier|string
	 */
	public function getDescription() {
		// Messages: config-install-database, config-install-tables, config-install-interwiki,
		// config-install-stats, config-install-keys, config-install-sysop, config-install-mainpage,
		// config-install-extensions
		$msg = wfMessage( "config-install-" . $this->getName() );
		if ( $msg->exists() ) {
			return $msg;
		} else {
			return wfMessage( "config-install-generic", $this->getName() );
		}
	}

	/**
	 * Get the description as a Message object
	 *
	 * @internal
	 * @return Message
	 */
	final public function getDescriptionMessage() {
		$msg = $this->getDescription();
		if ( $msg instanceof Message ) {
			return $msg;
		} elseif ( $msg instanceof MessageSpecifier ) {
			return new Message( $msg );
		} else {
			return new RawMessage( $msg );
		}
	}

	/**
	 * Override this to return true to skip the task. If this returns true,
	 * execute() will not be called, and start/end messages will not be
	 * produced.
	 *
	 * @stable to override
	 * @return bool
	 */
	public function isSkipped(): bool {
		return false;
	}

	/**
	 * Get alternative names of this task. These aliases can be used to fulfill
	 * dependencies of other tasks.
	 *
	 * @stable to override
	 * @return string|string[]
	 */
	public function getAliases() {
		return [];
	}

	/**
	 * Get a list of names or aliases of tasks that must be done prior to this task.
	 *
	 * @stable to override
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
	 * @stable to override
	 * @return string|string[]
	 */
	public function getProvidedNames() {
		return [];
	}

	/**
	 * If this returns true, the task will be scheduled after tasks for which
	 * it returns false. Subclasses can override this to return true for tasks
	 * that respond to the successful complete installation of the wiki.
	 *
	 * @stable to override
	 * @return bool
	 */
	public function isPostInstall() {
		return false;
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
	 * @param string $type One of the ITaskContext::CONN_* constants.
	 * @return ConnectionStatus
	 */
	protected function getConnection( string $type ): ConnectionStatus {
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
	protected function definitelyGetConnection( string $type ): IMaintainableDatabase {
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
	protected function applySourceFile( IMaintainableDatabase $conn, string $relPath ) {
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
	 * For core tasks, this is $IP/sql. For extension tasks, this will
	 * be sql/ under the extension directory.
	 *
	 * It would be possible to make the extension path be configurable, but it
	 * is currently not needed since extensions typically do not create their
	 * tables by this mechanism.
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
			// Some extensions (and core before T382030) store the MySQL schema in the base schema directory.
			return "$base/$filename";
		}
	}

	/**
	 * Get a helper for creating databases
	 *
	 * @return DatabaseCreator
	 */
	protected function getDatabaseCreator() {
		return DatabaseCreator::createInstance( $this->getContext() );
	}

	/**
	 * Get the restored services. Subclasses that want to call this must declare
	 * a dependency on "services".
	 */
	public function getServices(): MediaWikiServices {
		$this->assertDependsOn( 'services' );
		return $this->getContext()->getProvision( 'services' );
	}

	/**
	 * Get a HookContainer suitable for calling LoadExtensionSchemaUpdates.
	 * Subclasses that want to call this must declare a dependency on
	 * "HookContainer".
	 */
	public function getHookContainer(): HookContainer {
		$this->assertDependsOn( 'HookContainer' );
		return $this->getContext()->getProvision( 'HookContainer' );
	}

	/*
	 * Get the array of database virtual domains declared in extensions.
	 * Subclasses that want to call this must declare a dependency on
	 * "VirtualDomains".
	 */
	public function getVirtualDomains(): array {
		$this->assertDependsOn( 'VirtualDomains' );
		return $this->getContext()->getProvision( 'VirtualDomains' );
	}

	/**
	 * @param string $dependency
	 */
	private function assertDependsOn( $dependency ) {
		$deps = (array)$this->getDependencies();
		if ( !in_array( $dependency, $deps, true ) ) {
			throw new \RuntimeException( 'Task class "' . static::class . '" ' .
				"does not declare a dependency on \"$dependency\"" );
		}
	}
}
