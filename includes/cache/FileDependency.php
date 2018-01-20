<?php
/**
 * File dependency for data caching with dependencies.
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
 * @ingroup Cache
 */
use MediaWiki\MediaWikiServices;

/**
 * @ingroup Cache
 */
class FileDependency extends CacheDependency {
	private $filename;
	private $timestamp;

	/**
	 * Create a file dependency
	 *
	 * @param string $filename The name of the file, preferably fully qualified
	 * @param null|bool|int $timestamp The unix last modified timestamp, or false if the
	 *        file does not exist. If omitted, the timestamp will be loaded from
	 *        the file.
	 *
	 * A dependency on a nonexistent file will be triggered when the file is
	 * created. A dependency on an existing file will be triggered when the
	 * file is changed.
	 */
	function __construct( $filename, $timestamp = null ) {
		$this->filename = $filename;
		$this->timestamp = $timestamp;
	}

	/**
	 * @return array
	 */
	function __sleep() {
		$this->loadDependencyValues();

		return [ 'filename', 'timestamp' ];
	}

	function loadDependencyValues() {
		if ( is_null( $this->timestamp ) ) {
			MediaWiki\suppressWarnings();
			# Dependency on a non-existent file stores "false"
			# This is a valid concept!
			$this->timestamp = filemtime( $this->filename );
			MediaWiki\restoreWarnings();
		}
	}

	/**
	 * @return bool
	 */
	function isExpired() {
		MediaWiki\suppressWarnings();
		$lastmod = filemtime( $this->filename );
		MediaWiki\restoreWarnings();
		if ( $lastmod === false ) {
			if ( $this->timestamp === false ) {
				# Still nonexistent
				return false;
			} else {
				# Deleted
				wfDebug( "Dependency triggered: {$this->filename} deleted.\n" );

				return true;
			}
		} else {
			if ( $lastmod > $this->timestamp ) {
				# Modified or created
				wfDebug( "Dependency triggered: {$this->filename} changed.\n" );

				return true;
			} else {
				# Not modified
				return false;
			}
		}
	}
}
