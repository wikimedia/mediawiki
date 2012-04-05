<?php
/**
 * File repository with no files.
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * File repository with no files, for performance testing
 * @ingroup FileRepo
 */
class NullRepo extends FileRepo {
	function __construct( $info ) {}

	protected function assertWritableRepo() {
		throw new MWException( get_class( $this ) . ': write operations are not supported.' );
	}
}
