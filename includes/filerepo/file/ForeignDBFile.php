<?php
/**
 * Foreign file with an accessible MediaWiki database.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup FileAbstraction
 */

/**
 * Foreign file with an accessible MediaWiki database
 *
 * @ingroup FileAbstraction
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

	/**
	 * @param $srcPath String
	 * @param $flags int
	 * @throws MWException
	 */
	function publish( $srcPath, $flags = 0 ) {
		$this->readOnlyError();
	}

	/**
	 * @param $oldver
	 * @param $desc string
	 * @param $license string
	 * @param $copyStatus string
	 * @param $source string
	 * @param $watch bool
	 * @param $timestamp bool|string
	 * @throws MWException
	 */
	function recordUpload( $oldver, $desc, $license = '', $copyStatus = '', $source = '',
		$watch = false, $timestamp = false ) {
		$this->readOnlyError();
	}

	/**
	 * @param $versions array
	 * @param $unsuppress bool
	 * @throws MWException
	 */
	function restore( $versions = array(), $unsuppress = false ) {
		$this->readOnlyError();
	}

	/**
	 * @param $reason string
	 * @param $suppress bool
	 * @throws MWException
	 */
	function delete( $reason, $suppress = false ) {
		$this->readOnlyError();
	}

	/**
	 * @param $target Title
	 * @throws MWException
	 */
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
