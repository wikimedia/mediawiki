<?php
/**
 * MediaWiki page data importer.
 *
 * Copyright © 2003,2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

/**
 * This is a horrible hack used to keep source compatibility.
 * @ingroup SpecialPage
 */
class UploadSourceAdapter {
	/** @var ImportSource[] */
	public static $sourceRegistrations = [];

	/** @var resource|null Must exists on stream wrapper class */
	public $context;

	/** @var ImportSource */
	private $mSource;

	/** @var string */
	private $mBuffer = '';

	/** @var int */
	private $mPosition;

	/**
	 * @param ImportSource $source
	 * @return string
	 */
	public static function registerSource( ImportSource $source ) {
		$id = wfRandomString();

		self::$sourceRegistrations[$id] = $source;

		return $id;
	}

	/**
	 * @param string $id
	 * @return bool
	 */
	public static function isSeekableSource( string $id ) {
		if ( !isset( self::$sourceRegistrations[$id] ) ) {
			return false;
		}
		return self::$sourceRegistrations[$id]->isSeekable();
	}

	/**
	 * @param string $id
	 * @param int $offset
	 * @return int|false
	 */
	public static function seekSource( string $id, int $offset ) {
		if ( !isset( self::$sourceRegistrations[$id] ) ) {
			return false;
		}
		return self::$sourceRegistrations[$id]->seek( $offset );
	}

	/**
	 * @param string $path
	 * @param string $mode
	 * @param int $options
	 * @param string &$opened_path
	 * @return bool
	 */
	public function stream_open( $path, $mode, $options, &$opened_path ) {
		$url = parse_url( $path );
		if ( !isset( $url['host'] ) ) {
			return false;
		}
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
	public function stream_read( $count ) {
		$return = '';
		$leave = false;

		while ( !$leave && !$this->mSource->atEnd() &&
			strlen( $this->mBuffer ) < $count
		) {
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
	 * @return false
	 */
	public function stream_write( $data ) {
		return false;
	}

	/**
	 * @return int
	 */
	public function stream_tell() {
		return $this->mPosition;
	}

	/**
	 * @return bool
	 */
	public function stream_eof() {
		return $this->mSource->atEnd();
	}

	/**
	 * @return int[]
	 */
	public function url_stat() {
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
