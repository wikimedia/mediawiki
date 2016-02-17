<?php
/**
 * MediaWiki page data importer.
 *
 * Copyright © 2003,2005 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 * @ingroup SpecialPage
 */

/**
 * This is a horrible hack used to keep source compatibility.
 * @ingroup SpecialPage
 */
class UploadSourceAdapter {
	/** @var array */
	public static $sourceRegistrations = [];

	/** @var string */
	private $mSource;

	/** @var string */
	private $mBuffer;

	/** @var int */
	private $mPosition;

	/**
	 * @param ImportSource $source
	 * @return string
	 */
	static function registerSource( ImportSource $source ) {
		$id = wfRandomString();

		self::$sourceRegistrations[$id] = $source;

		return $id;
	}

	/**
	 * @param string $path
	 * @param string $mode
	 * @param array $options
	 * @param string $opened_path
	 * @return bool
	 */
	function stream_open( $path, $mode, $options, &$opened_path ) {
		$url = parse_url( $path );
		$id = $url['host'];

		if ( !isset( self::$sourceRegistrations[$id] ) ) {
			return false;
		}

		$this->mSource = self::$sourceRegistrations[$id];

		return true;
	}

	/**
	 * @param int $count
	 * @return string
	 */
	function stream_read( $count ) {
		$return = '';
		$leave = false;

		while ( !$leave && !$this->mSource->atEnd() &&
				strlen( $this->mBuffer ) < $count ) {
			$read = $this->mSource->readChunk();

			if ( !strlen( $read ) ) {
				$leave = true;
			}

			$this->mBuffer .= $read;
		}

		if ( strlen( $this->mBuffer ) ) {
			$return = substr( $this->mBuffer, 0, $count );
			$this->mBuffer = substr( $this->mBuffer, $count );
		}

		$this->mPosition += strlen( $return );

		return $return;
	}

	/**
	 * @param string $data
	 * @return bool
	 */
	function stream_write( $data ) {
		return false;
	}

	/**
	 * @return mixed
	 */
	function stream_tell() {
		return $this->mPosition;
	}

	/**
	 * @return bool
	 */
	function stream_eof() {
		return $this->mSource->atEnd();
	}

	/**
	 * @return array
	 */
	function url_stat() {
		$result = [];

		$result['dev'] = $result[0] = 0;
		$result['ino'] = $result[1] = 0;
		$result['mode'] = $result[2] = 0;
		$result['nlink'] = $result[3] = 0;
		$result['uid'] = $result[4] = 0;
		$result['gid'] = $result[5] = 0;
		$result['rdev'] = $result[6] = 0;
		$result['size'] = $result[7] = 0;
		$result['atime'] = $result[8] = 0;
		$result['mtime'] = $result[9] = 0;
		$result['ctime'] = $result[10] = 0;
		$result['blksize'] = $result[11] = 0;
		$result['blocks'] = $result[12] = 0;

		return $result;
	}
}
