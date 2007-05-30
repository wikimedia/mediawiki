<?php
/**
 * A repository that stores files in the local filesystem and registers them
 * in the wiki's own database. This is the most commonly used repository class.
 */
class LocalRepo extends FSRepo {
	var $fileFactory = array( 'LocalFile', 'newFromTitle' );

	function getSlaveDB() {
		return wfGetDB( DB_SLAVE );
	}

	function getMasterDB() {
		return wfGetDB( DB_MASTER );
	}

	function newFileFromRow( $row ) {
		if ( isset( $row->img_name ) ) {
			return LocalFile::newFromRow( $row, $this );
		} elseif ( isset( $row->oi_name ) ) {
			return OldLocalFile::newFromRow( $row, $this );
		} else {
			throw new MWException( __METHOD__.': invalid row' );
		}
	}
}
