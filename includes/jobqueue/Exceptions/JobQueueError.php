<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue\Exceptions;

use Exception;

/**
 * @newable
 * @since 1.22
 * @ingroup JobQueue
 */
class JobQueueError extends Exception {
}

/** @deprecated class alias since 1.44 */
class_alias( JobQueueError::class, 'JobQueueError' );
