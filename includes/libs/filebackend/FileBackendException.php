<?php
/**
 * Generic file backend exception for checked and unexpected (e.g. config) exceptions
 *
 * @ingroup FileBackend
 * @since 1.23
 */
class FileBackendException extends Exception {
}

/**
 * File backend exception for checked exceptions (e.g. I/O errors)
 *
 * @ingroup FileBackend
 * @since 1.22
 */
class FileBackendError extends FileBackendException {
}
