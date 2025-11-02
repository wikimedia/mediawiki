<?php
/**
 * Helper class for representing operations with transaction support.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

namespace Wikimedia\FileBackend\FileOps;

/**
 * Placeholder operation that has no params and does nothing
 */
class NullFileOp extends FileOp {
}

/** @deprecated class alias since 1.43 */
class_alias( NullFileOp::class, 'NullFileOp' );
