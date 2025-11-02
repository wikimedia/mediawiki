<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileRepo
 */

namespace MediaWiki\FileRepo;

use LogicException;

/**
 * File repository with no files, for testing purposes.
 *
 * @internal
 * @ingroup FileRepo
 */
class NullRepo extends FileRepo {
	/**
	 * @param array|null $info
	 */
	public function __construct( $info ) {
	}

	protected function assertWritableRepo(): never {
		throw new LogicException( static::class . ': write operations are not supported.' );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( NullRepo::class, 'NullRepo' );
