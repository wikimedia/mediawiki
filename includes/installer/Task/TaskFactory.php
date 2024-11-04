<?php

namespace MediaWiki\Installer\Task;

use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Factory for installer tasks
 *
 * @internal For use by the installer
 */
class TaskFactory {
	/**
	 * This list is roughly in order of execution, although the declared
	 * dependencies take precedence over the order in the input array.
	 */
	private const CORE_SPECS = [
		[ 'class' => ExtensionsProvider::class ],
		[ 'class' => MysqlCreateDatabaseTask::class, 'db' => 'mysql' ],
		[ 'class' => MysqlCreateUserTask::class, 'db' => 'mysql' ],
		[ 'class' => PostgresCreateDatabaseTask::class, 'db' => 'postgres' ],
		[ 'class' => PostgresCreateUserTask::class, 'db' => 'postgres' ],
		[ 'class' => PostgresPlTask::class, 'db' => 'postgres' ],
		[ 'class' => PostgresCreateSchemaTask::class, 'db' => 'postgres' ],
		[ 'class' => SqliteCreateDatabaseTask::class, 'db' => 'sqlite' ],
		[ 'class' => SqliteCreateSearchIndexTask::class, 'db' => 'sqlite' ],
		[ 'class' => CreateTablesTask::class ],
		[ 'class' => PopulateSiteStatsTask::class ],
		[ 'class' => PopulateInterwikiTask::class ],
		[ 'class' => InsertUpdateKeysTask::class ],
		[ 'class' => RestoredServicesProvider::class ],
		[ 'class' => InitialContentTask::class ],
		[ 'class' => CreateSysopTask::class ],
		[ 'class' => MailingListSubscribeTask::class ],
		[ 'class' => ExtensionTablesTask::class ],
	];

	/** @var ObjectFactory */
	private $objectFactory;
	/** @var ITaskContext */
	private $context;

	public function __construct( ObjectFactory $objectFactory, ITaskContext $context ) {
		$this->objectFactory = $objectFactory;
		$this->context = $context;
	}

	/**
	 * Populate the task list with core installer tasks which are shared by the
	 * various installation methods.
	 *
	 * @param TaskList $list
	 */
	public function registerMainInstallerTasks( TaskList $list ) {
		$dbType = $this->context->getDbType();
		foreach ( self::CORE_SPECS as $spec ) {
			if ( isset( $spec['db'] ) && $spec['db'] !== $dbType ) {
				continue;
			}
			$list->add( $this->create( $spec ) );
		}
	}

	/**
	 * Create a task object
	 *
	 * @param array $spec Associative array of options. If "callback" is present,
	 *   then the spec is a callback spec. Otherwise, the spec is an
	 *   ObjectFactory spec and must contain "class" or "factory".
	 *     - callback: A callable to call when the task is executed
	 *     - name: The task name (callback only)
	 *     - after: A task or list of tasks that this task must run after (callback only)
	 *     - before: A task or list of tasks that this task must run before (callback only)
	 *     - class: The class name (ObjectFactory only)
	 *     - factory: A factory function (ObjectFactory only)
	 *     - args: Arguments to pass to the constructor (ObjectFactory only)
	 * @return Task
	 */
	public function create( array $spec ): Task {
		if ( isset( $spec['callback'] ) ) {
			$task = new CallbackTask( $spec );
		} else {
			$allowedParamNames = [ 'factory', 'class', 'args' ];
			$factorySpec = array_intersect_key( $spec, array_fill_keys( $allowedParamNames, true ) );
			$task = $this->objectFactory->createObject( $factorySpec );
			if ( !( $task instanceof Task ) ) {
				throw new \RuntimeException( 'Invalid task type' );
			}
		}

		$task->initBase(
			$this->context,
			// TODO: determine extension base path from $spec
			$this->getCoreSchemaBasePath(),
		);
		return $task;
	}

	private function getCoreSchemaBasePath() {
		return MW_INSTALL_PATH . '/maintenance';
	}
}
