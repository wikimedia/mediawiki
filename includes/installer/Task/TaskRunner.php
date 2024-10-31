<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Status\Status;

/**
 * @internal For use by the installer
 */
class TaskRunner {
	/** @var TaskList */
	private $tasks;
	/** @var callable[] */
	private $taskStartListeners;
	/** @var callable[] */
	private $taskEndListeners;

	public function __construct( TaskList $tasks ) {
		$this->tasks = $tasks;
	}

	public function execute() {
		$overallStatus = Status::newGood();
		/** @var Task $task */
		foreach ( $this->tasks as $task ) {
			if ( $task->isSkipped() ) {
				continue;
			}

			$name = $task->getName();
			foreach ( $this->taskStartListeners as $listener ) {
				$listener( $name );
			}

			$status = $task->execute();

			// Output and save the results
			foreach ( $this->taskEndListeners as $listener ) {
				$listener( $name, $status );
			}
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
	 * @param callable $listener
	 */
	public function addTaskStartListener( callable $listener ) {
		$this->taskStartListeners[] = $listener;
	}

	/**
	 * @param callable $listener
	 */
	public function addTaskEndListener( callable $listener ) {
		$this->taskEndListeners[] = $listener;
	}
}
