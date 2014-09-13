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
	 * @param Title $title
	 * @param FileRepo $repo
	 * @param null $unused
	 * @return ForeignDBFile
	 */
	static function newFromTitle( $title, $repo, $unused = null ) {
		return new self( $title, $repo );
	}

	/**
	 * Create a ForeignDBFile from a title
	 * Do not call this except from inside a repo class.
	 *
	 * @param stdClass $row
	 * @param FileRepo $repo
	 * @return ForeignDBFile
	 */
	static function newFromRow( $row, $repo ) {
		$title = Title::makeTitle( NS_FILE, $row->img_name );
		$file = new self( $title, $repo );
		$file->loadFromRow( $row );

		return $file;
	}

	/**
	 * @param string $srcPath
	 * @param int $flags
	 * @param array $options
	 * @return FileRepoStatus
	 * @throws MWException
	 */
	function publish( $srcPath, $flags = 0, array $options = array() ) {
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
	 * @param $user User object or null to use $wgUser
	 * @return bool
	 * @throws MWException
	 */
	function recordUpload( $oldver, $desc, $license = '', $copyStatus = '', $source = '',
		$watch = false, $timestamp = false, User $user = null ) {
		$this->readOnlyError();
	}

	/**
	 * @param array $versions
	 * @param bool $unsuppress
	 * @return FileRepoStatus
	 * @throws MWException
	 */
	function restore( $versions = array(), $unsuppress = false ) {
		$this->readOnlyError();
	}

	/**
	 * @param string $reason
	 * @param bool $suppress
	 * @return FileRepoStatus
	 * @throws MWException
	 */
	function delete( $reason, $suppress = false ) {
		$this->readOnlyError();
	}

	/**
	 * @param Title $target
	 * @return FileRepoStatus
	 * @throws MWException
	 */
	function move( $target ) {
		$this->readOnlyError();
	}

	/**
	 * @return string
	 */
	function getDescriptionUrl() {
		// Restore remote behavior
		return File::getDescriptionUrl();
	}

	/**
	 * @param bool|Language $lang Optional language to fetch description in.
	 * @return string
	 */
	function getDescriptionText( $lang = false ) {
		// Restore remote behavior
		return File::getDescriptionText( $lang );
	}
}
