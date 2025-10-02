<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

namespace Wikimedia\FileBackend\FileOpHandle;

use Wikimedia\FileBackend\FileBackendStore;

/**
 * FileBackendStore helper class for performing asynchronous file operations.
 *
 * For example, calling FileBackendStore::createInternal() with the "async"
 * param flag may result in a StatusValue that contains this object as a value.
 * This class is largely backend-specific and is mostly just "magic" to be
 * passed to FileBackendStore::executeOpHandlesInternal().
 *
 * @stable to extend
 */
abstract class FileBackendStoreOpHandle {
	/** @var array */
	public $params = []; // params to caller functions
	/** @var FileBackendStore */
	public $backend;
	/** @var array */
	public $resourcesToClose = [];
	/** @var callable name that identifies the function called */
	public $call;

	/**
	 * Close all open file handles
	 */
	public function closeResources() {
		// @phan-suppress-next-line PhanPluginUseReturnValueInternalKnown
		array_map( 'fclose', $this->resourcesToClose );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( FileBackendStoreOpHandle::class, 'FileBackendStoreOpHandle' );
