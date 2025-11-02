<?php
/**
 * OpenStack Swift based file backend.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 * @author Russ Nelson
 */

namespace Wikimedia\FileBackend\FileOpHandle;

use Closure;
use Wikimedia\FileBackend\SwiftFileBackend;

class SwiftFileOpHandle extends FileBackendStoreOpHandle {
	/** @var array[] List of HTTP request maps for SwiftFileBackend::requestWithAuth */
	public $httpOp;
	/** @var Closure Function to run after each HTTP request finishes */
	public $callback;

	/** @var int Class CONTINUE_* constant */
	public $state = self::CONTINUE_IF_OK;

	/** @var int Continue with the next requests stages if no errors occurred */
	public const CONTINUE_IF_OK = 0;
	/** @var int Cancel the next requests stages */
	public const CONTINUE_NO = 1;

	/**
	 * Construct a handle to be use with SwiftFileOpHandle::doExecuteOpHandlesInternal()
	 *
	 * The callback returns a class CONTINUE_* constant and takes the following parameters:
	 *   - An HTTP request map array with 'response' filled
	 *   - A StatusValue instance to be updated as needed
	 *
	 * @param SwiftFileBackend $backend
	 * @param Closure $callback
	 * @param array $httpOp Request to send via SwiftFileBackend::requestWithAuth()
	 */
	public function __construct( SwiftFileBackend $backend, Closure $callback, array $httpOp ) {
		$this->backend = $backend;
		$this->callback = $callback;
		$this->httpOp = $httpOp;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( SwiftFileOpHandle::class, 'SwiftFileOpHandle' );
