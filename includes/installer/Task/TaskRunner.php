<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Status\Status;

/**
 * @internal For use by the installer
 */
class TaskRunner {
	/** @var TaskList|Task[] */
	private $tasks;
	/** @var TaskFactory */
	private $taskFactory;
	/** @var string */
	private $profile;
	/** @var callable[] */
	private $taskStartListeners = [];
	/** @var callable[] */
	private $taskEndListeners = [];
	/** @var array<string,bool> */
	private $skippedTasks = [];
	/** @var array<string,bool> */
	private $completedTasks = [];
	/** @var string|null */
	private $currentTaskName;

	public function __construct( TaskList $tasks, TaskFactory $taskFactory, string $profile ) {
		$this->tasks = $tasks;
		$this->taskFactory = $taskFactory;
		$this->profile = $profile;
	}

	/**
	 * Run all non-skipped tasks and return a merged Status
	 *
	 * @return Status
	 */
	public function execute() {
		$overallStatus = Status::newGood();
		$overallStatus->merge( $this->loadExtensions() );
		if ( !$overallStatus->isOK() ) {
			return $overallStatus;
		}

		/** @var Task $task */
		foreach ( $this->tasks as $task ) {
			if ( $this->isSkipped( $task ) || $this->isComplete( $task ) ) {
				continue;
			}

			$status = $this->runTask( $task );
			$overallStatus->merge( $status );

			// If we've hit some sort of fatal, we need to bail.
			// Callback already had a chance to do output above.
			if ( !$status->isOK() ) {
				break;
			}
		}
		return $overallStatus;
	}

	/**
	 * Run a single specified task (and its scheduled providers)
	 *
	 * @param string $name
	 * @return Status
	 */
	public function runNamedTask( string $name ) {
		$mainTask = $this->findNamedTask( $name );
		if ( !$mainTask ) {
			throw new \RuntimeException( "Can't find task named \"$name\"" );
		}
		$deps = (array)$mainTask->getDependencies();

		$status = Status::newGood();
		foreach ( $this->tasks as $subTask ) {
			if ( array_intersect( (array)$subTask->getProvidedNames(), $deps ) ) {
				$status->merge( $this->runTask( $subTask ) );
			}
		}
		$status->merge( $this->runTask( $mainTask ) );

		return $status;
	}

	/**
	 * Run the extensions provider (if it is registered) and load any extension tasks.
	 *
	 * @return Status
	 */
	public function loadExtensions() {
		$task = $this->findNamedTask( 'extensions' );
		if ( $task ) {
			$status = $this->runTask( $task );
			if ( $status->isOK() ) {
				$this->taskFactory->registerExtensionTasks( $this->tasks, $this->profile );
			}
		} else {
			$status = Status::newGood();
		}
		return $status;
	}

	/**
	 * @param string $name
	 * @return Task|null
	 */
	private function findNamedTask( string $name ): ?Task {
		foreach ( $this->tasks as $task ) {
			if ( $this->isSkipped( $task ) ) {
				continue;
			}

			if ( $name === $task->getName() ) {
				return $task;
			}
		}
		return null;
	}

	/**
	 * Determine whether a task is skipped
	 *
	 * @param Task $task
	 * @return bool
	 */
	private function isSkipped( Task $task ) {
		return $task->isSkipped() || isset( $this->skippedTasks[$task->getName()] );
	}

	/**
	 * Determine whether a task has already completed
	 *
	 * @param Task $task
	 * @return bool
	 */
	private function isComplete( Task $task ) {
		return isset( $this->completedTasks[$task->getName()] );
	}

	/**
	 * Run a task and call the listeners
	 *
	 * @param Task $task
	 * @return Status
	 */
	private function runTask( Task $task ) {
		$this->currentTaskName = $task->getName();
		foreach ( $this->taskStartListeners as $listener ) {
			$listener( $task );
		}

		$status = $task->execute();

		$this->completedTasks[$task->getName()] = true;
		foreach ( $this->taskEndListeners as $listener ) {
			$listener( $task, $status );
		}
		return $status;
	}

	/**
	 * Add a callback to be called before each task is executed. The callback
	 * takes one parameter: the task object.
	 */
	public function addTaskStartListener( callable $listener ) {
		$this->taskStartListeners[] = $listener;
	}

	/**
	 * Add a callback to be called after each task completes. The callback
	 * takes two parameters: the task object and the Status returned by the
	 * task.
	 */
	public function addTaskEndListener( callable $listener ) {
		$this->taskEndListeners[] = $listener;
	}

	/**
	 * Set a list of task names to be skipped
	 *
	 * @param string[] $taskNames
	 */
	public function setSkippedTasks( array $taskNames ) {
		$this->skippedTasks = array_fill_keys( $taskNames, true );
	}

	/**
	 * Get the name of the last task to start execution. This is valid during
	 * callbacks and after execute() returns.
	 *
	 * @return string|null
	 */
	public function getCurrentTaskName(): ?string {
		return $this->currentTaskName;
	}

	/**
	 * Provide a summary of the tasks to be executed, for debugging.
	 */
	public function dumpTaskList(): string {
		$ret = '';
		$i = 0;
		foreach ( $this->tasks as $task ) {
			$ret .= ( ++$i ) . '. ' . $task->getName();
			if ( $task->isSkipped() ) {
				$ret .= ' [SKIPPED]';
			}
			$ret .= ': ' . $task->getDescriptionMessage()->text();
			$ret .= "\n";
		}
		return $ret;
	}
}
