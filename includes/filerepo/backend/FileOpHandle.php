<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * FileBackendStore helper class for performing asynchronous file operations.
 * For example, calling FileBackendStore::createInternal( $params ) with the
 * "async" flag may result in a Status that contains this object as a value.
 * This class is largely backend-specific and is mostly just "magic" to be
 * passed to FileBackendStore::executeOpHandlesInternal().
 */
abstract class FileOpHandle {
	/** @var Array */
	public $params = array(); // params to caller functions
	/** @var FileBackendStore */
	public $backend;

	public $call; // string; name that identifies the function called

	/** @var Array */
	private $resourcesToClose = array();

	/**
	 * Set a file handle or handles to be closed on completion
	 *
	 * @param $resource resource|Array
	 * @return void
	 */
	public function registerResources( $resource ) {
		$this->resourcesToClose = array_merge( $this->resourcesToClose, (array)$resource );
	}

	/**
	 * Close all open file handles
	 *
	 * @return void
	 */
	public function closeResources() {
		array_map( 'fclose', $this->resourcesToClose );
	}
}
