<?php

namespace MediaWiki\Installer\Task;

use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Factory for installer tasks
 *
 * @internal For use by the installer
 */
class TaskFactory {
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
	 */
	public function registerMainInstallerTasks( TaskList $list ) {
		$taskClasses = [
			CreateTablesTask::class,
			PopulateSiteStatsTask::class,
			PopulateInterwikiTask::class,
			InsertUpdateKeysTask::class,
		];
		switch ( $this->context->getDbType() ) {
			case 'mysql':
				$taskClasses[] = MysqlCreateDatabaseTask::class;
				$taskClasses[] = MysqlCreateUserTask::class;
				break;
			case 'postgres':
				$taskClasses[] = PostgresCreateDatabaseTask::class;
				$taskClasses[] = PostgresPlTask::class;
				$taskClasses[] = PostgresCreateUserTask::class;
				$taskClasses[] = PostgresCreateSchemaTask::class;
				break;
			case 'sqlite':
				$taskClasses[] = SqliteCreateDatabaseTask::class;
				$taskClasses[] = SqliteCreateSearchIndexTask::class;
		}
		foreach ( $taskClasses as $class ) {
			$list->add( $this->create( [ 'class' => $class ] ) );
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
	 *     - services: Services to locate and pass as constructor args (ObjectFactory only)
	 *     - optional_services: Services to locate and pass as constructor args if they exist
	 *       (ObjectFactory only)
	 * @return Task
	 */
	public function create( array $spec ): Task {
		if ( isset( $spec['callback'] ) ) {
			$task = new CallbackTask( $spec );
		} else {
			$allowedParamNames = [ 'factory', 'class', 'args', 'services', 'optional_services' ];
			$factorySpec = array_intersect_key( $spec, array_fill_keys( $allowedParamNames, true ) );
			$task = $this->objectFactory->createObject( $factorySpec );
			if ( !( $task instanceof Task ) ) {
				throw new \RuntimeException( 'Invalid task type' );
			}
		}

		// TODO: determine extension base path from $spec
		$task->initBase( $this->context, $this->getCoreSchemaBasePath() );
		return $task;
	}

	private function getCoreSchemaBasePath() {
		return MW_INSTALL_PATH . '/maintenance';
	}
}
