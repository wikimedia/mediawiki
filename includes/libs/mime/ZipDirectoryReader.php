<?php
/**
 * ZIP file directories reader, for the purposes of upload verification.
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
 */

namespace Wikimedia\Mime;

use StatusValue;
use UnexpectedValueException;

/**
 * A class for reading ZIP file directories, for the purposes of upload
 * verification.
 *
 * Only a functional interface is provided: ZipFileReader::read(). No access is
 * given to object instances.
 */
class ZipDirectoryReader {
	/**
	 * Read a ZIP file and call a function for each file discovered in it.
	 *
	 * Because this class is aimed at verification, an error is raised on
	 * suspicious or ambiguous input, instead of emulating some standard
	 * behavior.
	 *
	 * @param string $fileName The archive file name
	 * @param callable $callback The callback function. It will be called for each file
	 *   with a single associative array each time, with members:
	 *
	 *      - name: The file name. Directories conventionally have a trailing
	 *        slash.
	 *
	 *      - mtime: The file modification time, in MediaWiki 14-char format
	 *
	 *      - size: The uncompressed file size
	 *
	 * @param array $options An associative array of read options, with the option
	 *    name in the key. This may currently contain:
	 *
	 *      - zip64: If this is set to true, then we will emulate a
	 *        library with ZIP64 support, like OpenJDK 7. If it is set to
	 *        false, then we will emulate a library with no knowledge of
	 *        ZIP64.
	 *
	 *        NOTE: The ZIP64 code is untested and probably doesn't work. It
	 *        turned out to be easier to just reject ZIP64 archive uploads,
	 *        since they are likely to be very rare. Confirming safety of a
	 *        ZIP64 file is fairly complex. What do you do with a file that is
	 *        ambiguous and broken when read with a non-ZIP64 reader, but valid
	 *        when read with a ZIP64 reader? This situation is normal for a
	 *        valid ZIP64 file, and working out what non-ZIP64 readers will make
	 *        of such a file is not trivial.
	 *
	 * @return StatusValue A StatusValue object. The following fatal errors are
	 *         defined:
	 *
	 *      - zip-file-open-error: The file could not be opened.
	 *
	 *      - zip-wrong-format: The file does not appear to be a ZIP file.
	 *
	 *      - zip-bad: There was something wrong or ambiguous about the file
	 *        data.
	 *
	 *      - zip-unsupported: The ZIP file uses features which
	 *        ZipDirectoryReader does not support.
	 *
	 * The default messages for those fatal errors are written in a way that
	 * makes sense for upload verification.
	 *
	 * If a fatal error is returned, more information about the error will be
	 * available in the debug log.
	 *
	 * Note that the callback function may be called any number of times before
	 * a fatal error is returned. If this occurs, the data sent to the callback
	 * function should be discarded.
	 */
	public static function read( $fileName, $callback, $options = [] ) {
		$file = fopen( $fileName, 'r' );
		$zdr = new self( $file, $callback, $options );
		return $zdr->execute();
	}

	/**
	 * Read an opened file handle presumed to be a ZIP and call a function for
	 * each file discovered in it.
	 *
	 * @see ZipDirectoryReader::read
	 *
	 * @param resource $file A seekable stream containing the archive
	 * @param callable $callback
	 * @param array $options
	 * @return StatusValue
	 */
	public static function readHandle( $file, $callback, $options = [] ) {
		$zdr = new self( $file, $callback, $options );
		return $zdr->execute();
	}

	/** @var resource The opened file resource */
	protected $file;

	/** @var int|null The cached length of the file, or null if it has not been loaded yet. */
	protected $fileLength;

	/** @var string[] A segmented cache of the file contents */
	protected $buffer;

	/** @var callable The file data callback */
	protected $callback;

	/** @var bool The ZIP64 mode */
	protected $zip64 = false;

	/** @var array Stored headers */
	protected $eocdr;
	/** @var array Stored headers */
	protected $eocdr64;
	/** @var array Stored headers */
	protected $eocdr64Locator;

	/** The "extra field" ID for ZIP64 central directory entries */
	private const ZIP64_EXTRA_HEADER = 0x0001;

	/** The segment size for the file contents cache */
	private const SEGSIZE = 16384;

	/** The index of the "general field" bit for UTF-8 file names */
	private const GENERAL_UTF8 = 11;

	/** The index of the "general field" bit for central directory encryption */
	private const GENERAL_CD_ENCRYPTED = 13;

	/**
	 * @param resource $file
	 * @param callable $callback
	 * @param array $options
	 */
	protected function __construct( $file, $callback, $options ) {
		$this->file = $file;
		$this->callback = $callback;

		if ( isset( $options['zip64'] ) ) {
			$this->zip64 = $options['zip64'];
		}
	}

	/**
	 * Read the directory according to settings in $this.
	 *
	 * @return StatusValue
	 */
	private function execute() {
		if ( !$this->file ) {
			return StatusValue::newFatal( 'zip-file-open-error' );
		}

		$status = StatusValue::newGood();
		try {
			$this->readEndOfCentralDirectoryRecord();
			if ( $this->zip64 ) {
				[ $offset, $size ] = $this->findZip64CentralDirectory();
				$this->readCentralDirectory( $offset, $size );
			} else {
				if ( $this->eocdr['CD size'] == 0xffffffff
					|| $this->eocdr['CD offset'] == 0xffffffff
					|| $this->eocdr['CD entries total'] == 0xffff
				) {
					$this->error( 'zip-unsupported', 'Central directory header indicates ZIP64, ' .
						'but we are in legacy mode. Rejecting this upload is necessary to avoid ' .
						'opening vulnerabilities on clients using OpenJDK 7 or later.' );
				}

				[ $offset, $size ] = $this->findOldCentralDirectory();
				$this->readCentralDirectory( $offset, $size );
			}
		} catch ( ZipDirectoryReaderError $e ) {
			$status->fatal( $e->getErrorCode() );
		}

		fclose( $this->file );

		return $status;
	}

	/**
	 * Throw an error, and log a debug message
	 * @param string $code
	 * @param string $debugMessage
	 * @throws ZipDirectoryReaderError
	 * @return never
	 */
	private function error( $code, $debugMessage ): never {
		wfDebug( __CLASS__ . ": Fatal error: $debugMessage" );
		throw new ZipDirectoryReaderError( $code );
	}

	/**
	 * Read the header which is at the end of the central directory,
	 * unimaginatively called the "end of central directory record" by the ZIP
	 * spec.
	 */
	private function readEndOfCentralDirectoryRecord() {
		$info = [
			'signature' => 4,
			'disk' => 2,
			'CD start disk' => 2,
			'CD entries this disk' => 2,
			'CD entries total' => 2,
			'CD size' => 4,
			'CD offset' => 4,
			'file comment length' => 2,
		];
		$structSize = $this->getStructSize( $info );
		$startPos = $this->getFileLength() - 65536 - $structSize;
		if ( $startPos < 0 ) {
			$startPos = 0;
		}

		if ( $this->getFileLength() === 0 ) {
			$this->error( 'zip-wrong-format', "The file is empty." );
		}

		$block = $this->getBlock( $startPos );
		$sigPos = strrpos( $block, "PK\x05\x06" );
		if ( $sigPos === false ) {
			$this->error( 'zip-wrong-format',
				"zip file lacks EOCDR signature. It probably isn't a zip file." );
		}

		$this->eocdr = $this->unpack( substr( $block, $sigPos ), $info );
		$this->eocdr['EOCDR size'] = $structSize + $this->eocdr['file comment length'];

		if ( $structSize + $this->eocdr['file comment length'] != strlen( $block ) - $sigPos ) {
			// T40432: MS binary documents frequently embed ZIP files
			$this->error( 'zip-wrong-format', 'there is a ZIP signature but it is not at ' .
				'the end of the file. It could be an OLE file with a ZIP file embedded.' );
		}
		if ( $this->eocdr['disk'] !== 0
			|| $this->eocdr['CD start disk'] !== 0
		) {
			$this->error( 'zip-unsupported', 'more than one disk (in EOCDR)' );
		}
		$this->eocdr += $this->unpack(
			$block,
			[ 'file comment' => [ 'string', $this->eocdr['file comment length'] ] ],
			$sigPos + $structSize );
		$this->eocdr['position'] = $startPos + $sigPos;
	}

	/**
	 * Read the header called the "ZIP64 end of central directory locator". An
	 * error will be raised if it does not exist.
	 */
	private function readZip64EndOfCentralDirectoryLocator() {
		$info = [
			'signature' => [ 'string', 4 ],
			'eocdr64 start disk' => 4,
			'eocdr64 offset' => 8,
			'number of disks' => 4,
		];
		$structSize = $this->getStructSize( $info );

		$start = $this->getFileLength() - $this->eocdr['EOCDR size'] - $structSize;
		$block = $this->getBlock( $start, $structSize );
		$this->eocdr64Locator = $data = $this->unpack( $block, $info );

		if ( $data['signature'] !== "PK\x06\x07" ) {
			// Note: Java will allow this and continue to read the
			// EOCDR64, so we have to reject the upload, we can't
			// just use the EOCDR header instead.
			$this->error( 'zip-bad', 'wrong signature on Zip64 end of central directory locator' );
		}
	}

	/**
	 * Read the header called the "ZIP64 end of central directory record". It
	 * may replace the regular "end of central directory record" in ZIP64 files.
	 */
	private function readZip64EndOfCentralDirectoryRecord() {
		if ( $this->eocdr64Locator['eocdr64 start disk'] != 0
			|| $this->eocdr64Locator['number of disks'] != 0
		) {
			$this->error( 'zip-unsupported', 'more than one disk (in EOCDR64 locator)' );
		}

		$info = [
			'signature' => [ 'string', 4 ],
			'EOCDR64 size' => 8,
			'version made by' => 2,
			'version needed' => 2,
			'disk' => 4,
			'CD start disk' => 4,
			'CD entries this disk' => 8,
			'CD entries total' => 8,
			'CD size' => 8,
			'CD offset' => 8
		];
		$structSize = $this->getStructSize( $info );
		$block = $this->getBlock( $this->eocdr64Locator['eocdr64 offset'], $structSize );
		$this->eocdr64 = $data = $this->unpack( $block, $info );
		if ( $data['signature'] !== "PK\x06\x06" ) {
			$this->error( 'zip-bad', 'wrong signature on Zip64 end of central directory record' );
		}
		if ( $data['disk'] !== 0
			|| $data['CD start disk'] !== 0
		) {
			$this->error( 'zip-unsupported', 'more than one disk (in EOCDR64)' );
		}
	}

	/**
	 * Find the location of the central directory, as would be seen by a
	 * non-ZIP64 reader.
	 *
	 * @return array List containing offset, size and end position.
	 */
	private function findOldCentralDirectory() {
		$size = $this->eocdr['CD size'];
		$offset = $this->eocdr['CD offset'];
		$endPos = $this->eocdr['position'];

		// Some readers use the EOCDR position instead of the offset field
		// to find the directory, so to be safe, we check if they both agree.
		if ( $offset + $size != $endPos ) {
			$this->error( 'zip-bad', 'the central directory does not immediately precede the end ' .
				'of central directory record' );
		}

		return [ $offset, $size ];
	}

	/**
	 * Find the location of the central directory, as would be seen by a
	 * ZIP64-compliant reader.
	 *
	 * @return array List containing offset, size and end position.
	 */
	private function findZip64CentralDirectory() {
		// The spec is ambiguous about the exact rules of precedence between the
		// ZIP64 headers and the original headers. Here we follow zip_util.c
		// from OpenJDK 7.
		$size = $this->eocdr['CD size'];
		$offset = $this->eocdr['CD offset'];
		$numEntries = $this->eocdr['CD entries total'];
		$endPos = $this->eocdr['position'];
		if ( $size == 0xffffffff
			|| $offset == 0xffffffff
			|| $numEntries == 0xffff
		) {
			$this->readZip64EndOfCentralDirectoryLocator();

			if ( isset( $this->eocdr64Locator['eocdr64 offset'] ) ) {
				$this->readZip64EndOfCentralDirectoryRecord();
				if ( isset( $this->eocdr64['CD offset'] ) ) {
					$size = $this->eocdr64['CD size'];
					$offset = $this->eocdr64['CD offset'];
					$endPos = $this->eocdr64Locator['eocdr64 offset'];
				}
			}
		}
		// Some readers use the EOCDR position instead of the offset field
		// to find the directory, so to be safe, we check if they both agree.
		if ( $offset + $size != $endPos ) {
			$this->error( 'zip-bad', 'the central directory does not immediately precede the end ' .
				'of central directory record' );
		}

		return [ $offset, $size ];
	}

	/**
	 * Read the central directory at the given location
	 * @param int $offset
	 * @param int $size
	 */
	private function readCentralDirectory( $offset, $size ) {
		$block = $this->getBlock( $offset, $size );

		$fixedInfo = [
			'signature' => [ 'string', 4 ],
			'version made by' => 2,
			'version needed' => 2,
			'general bits' => 2,
			'compression method' => 2,
			'mod time' => 2,
			'mod date' => 2,
			'crc-32' => 4,
			'compressed size' => 4,
			'uncompressed size' => 4,
			'name length' => 2,
			'extra field length' => 2,
			'comment length' => 2,
			'disk number start' => 2,
			'internal attrs' => 2,
			'external attrs' => 4,
			'local header offset' => 4,
		];
		$fixedSize = $this->getStructSize( $fixedInfo );

		$pos = 0;
		while ( $pos < $size ) {
			$data = $this->unpack( $block, $fixedInfo, $pos );
			$pos += $fixedSize;

			if ( $data['signature'] !== "PK\x01\x02" ) {
				$this->error( 'zip-bad', 'Invalid signature found in directory entry' );
			}

			$variableInfo = [
				'name' => [ 'string', $data['name length'] ],
				'extra field' => [ 'string', $data['extra field length'] ],
				'comment' => [ 'string', $data['comment length'] ],
			];
			$data += $this->unpack( $block, $variableInfo, $pos );
			$pos += $this->getStructSize( $variableInfo );

			if ( $this->zip64 && (
					$data['compressed size'] == 0xffffffff
					|| $data['uncompressed size'] == 0xffffffff
					|| $data['local header offset'] == 0xffffffff )
			) {
				$zip64Data = $this->unpackZip64Extra( $data['extra field'] );
				if ( $zip64Data ) {
					$data = $zip64Data + $data;
				}
			}

			if ( $this->testBit( $data['general bits'], self::GENERAL_CD_ENCRYPTED ) ) {
				$this->error( 'zip-unsupported', 'central directory encryption is not supported' );
			}

			// Convert the timestamp into MediaWiki format
			// For the format, please see the MS-DOS 2.0 Programmer's Reference,
			// pages 3-5 and 3-6.
			$time = $data['mod time'];
			$date = $data['mod date'];

			$year = 1980 + ( $date >> 9 );
			$month = ( $date >> 5 ) & 15;
			$day = $date & 31;
			$hour = ( $time >> 11 ) & 31;
			$minute = ( $time >> 5 ) & 63;
			$second = ( $time & 31 ) * 2;
			$timestamp = sprintf( "%04d%02d%02d%02d%02d%02d",
				$year, $month, $day, $hour, $minute, $second );

			// Convert the character set in the file name
			if ( $this->testBit( $data['general bits'], self::GENERAL_UTF8 ) ) {
				$name = $data['name'];
			} else {
				$name = iconv( 'CP437', 'UTF-8', $data['name'] );
			}

			// Compile a data array for the user, with a sensible format
			$userData = [
				'name' => $name,
				'mtime' => $timestamp,
				'size' => $data['uncompressed size'],
			];
			( $this->callback )( $userData );
		}
	}

	/**
	 * Interpret ZIP64 "extra field" data and return an associative array.
	 * @param string $extraField
	 * @return array|bool
	 */
	private function unpackZip64Extra( $extraField ) {
		$extraHeaderInfo = [
			'id' => 2,
			'size' => 2,
		];
		$extraHeaderSize = $this->getStructSize( $extraHeaderInfo );

		$zip64ExtraInfo = [
			'uncompressed size' => 8,
			'compressed size' => 8,
			'local header offset' => 8,
			'disk number start' => 4,
		];

		$extraPos = 0;
		while ( $extraPos < strlen( $extraField ) ) {
			$extra = $this->unpack( $extraField, $extraHeaderInfo, $extraPos );
			$extraPos += $extraHeaderSize;
			$extra += $this->unpack( $extraField,
				[ 'data' => [ 'string', $extra['size'] ] ],
				$extraPos );
			$extraPos += $extra['size'];

			if ( $extra['id'] == self::ZIP64_EXTRA_HEADER ) {
				return $this->unpack( $extra['data'], $zip64ExtraInfo );
			}
		}

		return false;
	}

	/**
	 * Get the length of the file.
	 * @return int
	 */
	private function getFileLength() {
		if ( $this->fileLength === null ) {
			$stat = fstat( $this->file );
			$this->fileLength = $stat['size'];
		}

		return $this->fileLength;
	}

	/**
	 * Get the file contents from a given offset. If there are not enough bytes
	 * in the file to satisfy the request, an exception will be thrown.
	 *
	 * @param int $start The byte offset of the start of the block.
	 * @param int|null $length The number of bytes to return. If omitted, the remainder
	 *    of the file will be returned.
	 *
	 * @return string
	 */
	private function getBlock( $start, $length = null ) {
		$fileLength = $this->getFileLength();
		if ( $start >= $fileLength ) {
			$this->error( 'zip-bad', "getBlock() requested position $start, " .
				"file length is $fileLength" );
		}
		$length ??= $fileLength - $start;
		$end = $start + $length;
		if ( $end > $fileLength ) {
			$this->error( 'zip-bad', "getBlock() requested end position $end, " .
				"file length is $fileLength" );
		}
		$startSeg = (int)floor( $start / self::SEGSIZE );
		$endSeg = (int)ceil( $end / self::SEGSIZE );

		$block = '';
		for ( $segIndex = $startSeg; $segIndex <= $endSeg; $segIndex++ ) {
			$block .= $this->getSegment( $segIndex );
		}

		$block = substr( $block,
			$start - $startSeg * self::SEGSIZE,
			$length );

		if ( strlen( $block ) < $length ) {
			$this->error( 'zip-bad', 'getBlock() returned an unexpectedly small amount of data' );
		}

		return $block;
	}

	/**
	 * Get a section of the file starting at position $segIndex * self::SEGSIZE,
	 * of length self::SEGSIZE. The result is cached. This is a helper function
	 * for getBlock().
	 *
	 * If there are not enough bytes in the file to satisfy the request, the
	 * return value will be truncated. If a request is made for a segment beyond
	 * the end of the file, an empty string will be returned.
	 *
	 * @param int $segIndex
	 *
	 * @return string
	 */
	private function getSegment( $segIndex ) {
		if ( !isset( $this->buffer[$segIndex] ) ) {
			$bytePos = $segIndex * self::SEGSIZE;
			if ( $bytePos >= $this->getFileLength() ) {
				$this->buffer[$segIndex] = '';

				return '';
			}
			if ( fseek( $this->file, $bytePos ) ) {
				$this->error( 'zip-bad', "seek to $bytePos failed" );
			}
			$seg = fread( $this->file, self::SEGSIZE );
			if ( $seg === false ) {
				$this->error( 'zip-bad', "read from $bytePos failed" );
			}
			$this->buffer[$segIndex] = $seg;
		}

		return $this->buffer[$segIndex];
	}

	/**
	 * Get the size of a structure in bytes. See unpack() for the format of $struct.
	 * @param array $struct
	 * @return int
	 */
	private function getStructSize( $struct ) {
		$size = 0;
		foreach ( $struct as $type ) {
			if ( is_array( $type ) ) {
				[ , $fieldSize ] = $type;
				$size += $fieldSize;
			} else {
				$size += $type;
			}
		}

		return $size;
	}

	/**
	 * Unpack a binary structure. This is like the built-in unpack() function
	 * except nicer.
	 *
	 * @param string $string The binary data input
	 *
	 * @param array $struct An associative array giving structure members and their
	 *    types. In the key is the field name. The value may be either an
	 *    integer, in which case the field is a little-endian unsigned integer
	 *    encoded in the given number of bytes, or an array, in which case the
	 *    first element of the array is the type name, and the subsequent
	 *    elements are type-dependent parameters. Only one such type is defined:
	 *       - "string": The second array element gives the length of string.
	 *          Not null terminated.
	 *
	 * @param int $offset The offset into the string at which to start unpacking.
	 * @return array Unpacked associative array. Note that large integers in the input
	 *    may be represented as floating point numbers in the return value, so
	 *    the use of weak comparison is advised.
	 */
	private function unpack( $string, $struct, $offset = 0 ) {
		$size = $this->getStructSize( $struct );
		if ( $offset + $size > strlen( $string ) ) {
			$this->error( 'zip-bad', 'unpack() would run past the end of the supplied string' );
		}

		$data = [];
		$pos = $offset;
		foreach ( $struct as $key => $type ) {
			if ( is_array( $type ) ) {
				[ $typeName, $fieldSize ] = $type;
				switch ( $typeName ) {
					case 'string':
						$data[$key] = substr( $string, $pos, $fieldSize );
						$pos += $fieldSize;
						break;
					default:
						throw new UnexpectedValueException( __METHOD__ . ": invalid type \"$typeName\"" );
				}
			} else {
				// Unsigned little-endian integer
				$length = intval( $type );

				// Calculate the value. Use an algorithm which automatically
				// upgrades the value to floating point if necessary.
				$value = 0;
				for ( $i = $length - 1; $i >= 0; $i-- ) {
					$value *= 256;
					$value += ord( $string[$pos + $i] );
				}

				// Throw an exception if there was loss of precision
				if ( $value > 2 ** 52 ) {
					$this->error( 'zip-unsupported', 'number too large to be stored in a double. ' .
						'This could happen if we tried to unpack a 64-bit structure ' .
						'at an invalid location.' );
				}
				$data[$key] = $value;
				$pos += $length;
			}
		}

		return $data;
	}

	/**
	 * Returns a bit from a given position in an integer value, converted to
	 * boolean.
	 *
	 * @param int $value
	 * @param int $bitIndex The index of the bit, where 0 is the LSB.
	 * @return bool
	 */
	private function testBit( $value, $bitIndex ) {
		return (bool)( ( $value >> $bitIndex ) & 1 );
	}
}
