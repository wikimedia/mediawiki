<?php

namespace MediaWiki\Installer\Task;

use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Factory for installer tasks
 *
 * @internal For use by the installer
 */
class TaskFactory {
	public const PROFILE_INSTALLER = 'installer';
	public const PROFILE_ADD_WIKI = 'installPreConfigured';
	public const PROFILE_WEB_UPGRADE = 'web-upgrade';

	/**
	 * This list is roughly in order of execution, although the declared
	 * dependencies take precedence over the order in the input array.
	 */
	private const CORE_SPECS = [
		[ 'class' => ExtensionsProvider::class, 'profile' => self::PROFILE_INSTALLER ],
		[ 'class' => CreateDatabaseTask::class, 'db' => 'mysql' ],
		[ 'class' => MysqlCreateUserTask::class, 'db' => 'mysql' ],
		[ 'class' => CreateDatabaseTask::class, 'db' => 'postgres' ],
		[ 'class' => PostgresCreateUserTask::class, 'db' => 'postgres' ],
		[ 'class' => PostgresPlTask::class, 'db' => 'postgres' ],
		[ 'class' => PostgresCreateSchemaTask::class, 'db' => 'postgres' ],
		[ 'class' => SqliteCreateDatabaseTask::class, 'db' => 'sqlite' ],
		[ 'class' => CreateTablesTask::class ],
		[ 'class' => SqliteCreateSearchIndexTask::class, 'db' => 'sqlite' ],
		[ 'class' => PopulateSiteStatsTask::class ],
		[ 'class' => PopulateInterwikiTask::class ],
		[ 'class' => InsertUpdateKeysTask::class ],
		[ 'class' => RestoredServicesProvider::class, 'profile' => self::PROFILE_INSTALLER ],
		[ 'class' => AddWikiRestoredServicesProvider::class, 'profile' => self::PROFILE_ADD_WIKI ],
		[ 'class' => CreateExternalDomainsTask::class, 'profile' => self::PROFILE_ADD_WIKI ],
		[ 'class' => ExtensionTablesTask::class ],
		[ 'class' => InitialContentTask::class ],
		[ 'class' => CreateSysopTask::class, 'profile' => self::PROFILE_INSTALLER ],
		[ 'class' => MailingListSubscribeTask::class, 'profile' => self::PROFILE_INSTALLER ],
	];

	private const WEB_UPGRADE_SPECS = [
		[ 'class' => WebUpgradeExtensionsProvider::class ],
		[ 'class' => RestoredServicesProvider::class ],
		[ 'class' => WebUpgradeTask::class ],
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
	 * @param string $profile One of the PROFILE_xxx constants
	 */
	public function registerMainTasks( TaskList $list, string $profile ) {
		$this->registerTasks( $list, $profile, self::CORE_SPECS );
	}

	/**
	 * Populate the task list with extension installer tasks provided by the
	 * current task context.
	 *
	 * @param TaskList $list
	 * @param string $profile One of the PROFILE_xxx constants
	 */
	public function registerExtensionTasks( TaskList $list, string $profile ) {
		$specs = $this->context->getProvision( 'ExtensionTaskSpecs' );
		if ( !is_array( $specs ) ) {
			throw new \RuntimeException( 'Invalid value for ExtensionTaskSpecs' );
		}
		$this->registerTasks( $list, $profile, $specs );
	}

	public function registerWebUpgradeTasks( TaskList $list ) {
		$this->registerTasks( $list, self::PROFILE_WEB_UPGRADE, self::WEB_UPGRADE_SPECS );
	}

	/**
	 * Register tasks from a spec array
	 *
	 * @param TaskList $list
	 * @param string $profile
	 * @param array $specs
	 */
	private function registerTasks( TaskList $list, string $profile, array $specs ) {
		$dbType = $this->context->getDbType();
		foreach ( $specs as $spec ) {
			if ( isset( $spec['db'] ) && $spec['db'] !== $dbType ) {
				continue;
			}
			if ( isset( $spec['profile'] ) && $spec['profile'] !== $profile ) {
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
	 *     - description: The task description (callback only)
	 *     - after: A task or list of tasks that this task must run after (callback only)
	 *     - postInstall: If true, the task will run after all install tasks (callback only)
	 *     - class: The class name (ObjectFactory only)
	 *     - factory: A factory function (ObjectFactory only)
	 *     - args: Arguments to pass to the constructor (ObjectFactory only)
	 *     - schemaBasePath: The base path used for SQL files. This is populated by
	 *       ExtensionProcessor if the spec comes from an extension.
	 * @return Task
	 */
	public function create( array $spec ): Task {
		if ( isset( $spec['callback'] ) ) {
			$task = new CallbackTask( $spec );
		} else {
			$allowedParamNames = [ 'factory', 'class', 'args' ];
			$factorySpec = array_intersect_key( $spec, array_fill_keys( $allowedParamNames, true ) );
			$task = $this->objectFactory->createObject(
				$factorySpec,
				[ 'assertClass' => Task::class ]
			);
		}

		$schemaBasePath = $spec['schemaBasePath'] ?? $this->getCoreSchemaBasePath();

		$task->initBase(
			$this->context,
			$schemaBasePath,
		);
		return $task;
	}

	private function getCoreSchemaBasePath(): string {
		return MW_INSTALL_PATH . '/sql';
	}
}
