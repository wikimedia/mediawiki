<?php
/**
 * Foreign file with an accessible MediaWiki database
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * Foreign file with an accessible MediaWiki database
 *
 * @ingroup FileRepo
 */
class ForeignDBFile extends LocalFile {

	/**
	 * @param $title
	 * @param $repo
	 * @param $unused
	 * @return ForeignDBFile
	 */
	static function newFromTitle( $title, $repo, $unused = null ) {
		return new self( $title, $repo );
	}

	/**
	 * Create a ForeignDBFile from a title
	 * Do not call this except from inside a repo class.
	 *
	 * @param $row
	 * @param $repo
	 *
	 * @return ForeignDBFile
	 */
	static function newFromRow( $row, $repo ) {
		$title = Title::makeTitle( NS_FILE, $row->img_name );
		$file = new self( $title, $repo );
		$file->loadFromRow( $row );
		return $file;
	}

	function publish( $srcPath, $flags = 0 ) {
		$this->readOnlyError();
	}

	function recordUpload( $oldver, $desc, $license = '', $copyStatus = '', $source = '',
		$watch = false, $timestamp = false ) {
		$this->readOnlyError();
	}

	function restore( $versions = array(), $unsuppress = false ) {
		$this->readOnlyError();
	}

	function delete( $reason, $suppress = false ) {
		$this->readOnlyError();
	}

	function move( $target ) {
		$this->readOnlyError();
	}

	/**
	 * @return string
	 */
	function getDescriptionUrl() {
		// Restore remote behaviour
		return File::getDescriptionUrl();
	}

	/**
	 * @return string
	 */
	function getDescriptionText() {
		// Restore remote behaviour
		return File::getDescriptionText();
	}
}
