<?php

namespace MediaWiki\Installer\Task;

use ArrayIterator;
use RuntimeException;

/**
 * A container for tasks, with sorting of tasks by their declared dependencies.
 *
 * @internal For use by the installer
 */
class TaskList implements \IteratorAggregate {
	/** @var Task[] */
	private $unsortedTasks = [];

	/** @var Task[]|null */
	private $sortedTasks;

	/**
	 * Add a task to the list
	 */
	public function add( Task $task ) {
		$this->unsortedTasks[] = $task;
		$this->sortedTasks = null;
	}

	/**
	 * Get the sorted task list as an iterator
	 *
	 * @return ArrayIterator<Task>
	 */
	public function getIterator(): ArrayIterator {
		return new ArrayIterator( $this->getSortedTasks() );
	}

	/**
	 * Sort the tasks so that dependencies are done before dependant tasks.
	 * Tasks with the same dependencies will be done in the order they were added.
	 *
	 * @return Task[]
	 */
	private function getSortedTasks() {
		if ( $this->sortedTasks === null ) {
			$tasksByName = $this->indexTasksByName( $this->unsortedTasks );
			$unresolvedTasks = [];
			$resolvedTasks = [];
			foreach ( $this->unsortedTasks as $task ) {
				$this->processDependencies( $unresolvedTasks, $resolvedTasks, $tasksByName, $task );
			}
			$this->sortedTasks = array_values( $resolvedTasks );
		}
		return $this->sortedTasks;
	}

	/**
	 * @param Task[] $tasks
	 * @return array<string,Task[]>
	 */
	private function indexTasksByName( $tasks ) {
		$tasksByName = [];
		foreach ( $tasks as $task ) {
			$name = $task->getName();
			$tasksByName[$name][] = $task;

			foreach ( (array)$task->getAliases() as $alias ) {
				$tasksByName[$alias][] = $task;
			}

			foreach ( (array)$task->getProvidedNames() as $alias ) {
				$tasksByName[$alias][] = $task;
			}

			if ( !$task->isPostInstall() ) {
				$tasksByName['install'][] = $task;
			}
		}
		return $tasksByName;
	}

	/**
	 * Recursively add the dependencies of $task to $resolvedTasks, then add
	 * $task itself. Track circular references by temporarily adding $task to
	 * $unresolvedTasks. Use $tasksByName to find the named dependencies.
	 *
	 * @param array<int,Task> &$unresolvedTasks
	 * @param array<int,Task> &$resolvedTasks
	 * @param array<string,Task[]> $tasksByName
	 * @param Task $task
	 */
	private function processDependencies(
		array &$unresolvedTasks,
		array &$resolvedTasks,
		array $tasksByName,
		Task $task
	) {
		$name = $task->getName();
		$id = spl_object_id( $task );
		$unresolvedTasks[$id] = $task;
		$deps = (array)$task->getDependencies();

		// Post-install tasks implicitly depend on the install alias, which all
		// other tasks have
		if ( $task->isPostInstall() ) {
			$deps[] = 'install';
		}

		foreach ( $deps as $depName ) {
			if ( !isset( $tasksByName[$depName] ) ) {
				throw new RuntimeException(
					"Can't find dependency \"$depName\" required by task \"$name\"" );
			}
			$dependencies = $tasksByName[$depName];
			foreach ( $dependencies as $dependency ) {
				$depId = spl_object_id( $dependency );
				if ( isset( $unresolvedTasks[$depId] ) ) {
					throw new RuntimeException(
						"Circular reference due to task \"$name\" depending on \"$depName\"" );
				}
				$this->processDependencies(
					$unresolvedTasks, $resolvedTasks, $tasksByName, $dependency );
			}
		}
		$resolvedTasks[$id] = $task;
		unset( $unresolvedTasks[$id] );
	}
}
