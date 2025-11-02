<?php

namespace Wikimedia\FileBackend;

use Exception;

/**
 * File backend exception for checked exceptions (e.g. I/O errors)
 *
 * @newable
 * @stable to extend
 * @ingroup FileBackend
 * @since 1.22
 */
class FileBackendError extends Exception {
}

/** @deprecated class alias since 1.43 */
class_alias( FileBackendError::class, 'FileBackendError' );
