<?php
/**
 * A repository for files accessible via the local filesystem.
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * A repository for files accessible via the local filesystem. Does not support
 * database access or registration.
 * @ingroup FileRepo
 * @deprecated since 1.19
 */
class FSRepo extends FileRepo {
	function __construct( $info ) {
		parent::__construct( $info );
		if ( !( $this->backend instanceof FSFileBackend ) ) {
			throw new MWException( "FSRepo only supports FSFileBackend." );
		}
	}
}
