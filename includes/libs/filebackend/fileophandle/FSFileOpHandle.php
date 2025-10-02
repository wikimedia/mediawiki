<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

namespace Wikimedia\FileBackend\FileOpHandle;

use Wikimedia\FileBackend\FSFileBackend;

class FSFileOpHandle extends FileBackendStoreOpHandle {
	/** @var string Shell command */
	public $cmd;
	/** @var callback Post-operation success/error handling and cleanup function */
	public $callback;

	/**
	 * @param FSFileBackend $backend
	 * @param array $params
	 * @param callable $call
	 * @param string $cmd
	 */
	public function __construct( FSFileBackend $backend, array $params, callable $call, $cmd ) {
		$this->backend = $backend;
		$this->params = $params;
		$this->callback = $call;
		$this->cmd = $cmd;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( FSFileOpHandle::class, 'FSFileOpHandle' );
