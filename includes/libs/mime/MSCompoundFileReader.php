<?php
/*
 * Copyright 2019 Wikimedia Foundation
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed
 * under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 */

/**
 * Read the directory of a Microsoft Compound File Binary file, a.k.a. an OLE
 * file, and detect the MIME type.
 *
 * References:
 *  - MS-CFB https://msdn.microsoft.com/en-us/library/dd942138.aspx
 *  - MS-XLS https://msdn.microsoft.com/en-us/library/cc313154.aspx
 *  - MS-PPT https://msdn.microsoft.com/en-us/library/cc313106.aspx
 *  - MS-DOC https://msdn.microsoft.com/en-us/library/cc313153.aspx
 *  - Python olefile https://github.com/decalage2/olefile
 *  - OpenOffice.org's Documentation of the Microsoft Compound Document
 *    File Format https://www.openoffice.org/sc/compdocfileformat.pdf
 *
 * @since 1.33
 */
class MSCompoundFileReader {
	private $file;
	private $header;
	private $mime;
	private $mimeFromClsid;
	private $error;
	private $errorCode;
	private $valid = false;

	private $sectorLength;
	private $difat;
	private $fat = [];

	private const TYPE_UNALLOCATED = 0;
	private const TYPE_STORAGE = 1;
	private const TYPE_STREAM = 2;
	private const TYPE_ROOT = 5;

	public const ERROR_FILE_OPEN = 1;
	public const ERROR_SEEK = 2;
	public const ERROR_READ = 3;
	public const ERROR_INVALID_SIGNATURE = 4;
	public const ERROR_READ_PAST_END = 5;
	public const ERROR_INVALID_FORMAT = 6;

	private static $mimesByClsid = [
		// From http://justsolve.archiveteam.org/wiki/Microsoft_Compound_File
		'00020810-0000-0000-C000-000000000046' => 'application/vnd.ms-excel',
		'00020820-0000-0000-C000-000000000046' => 'application/vnd.ms-excel',
		'00020906-0000-0000-C000-000000000046' => 'application/msword',
		'64818D10-4F9B-11CF-86EA-00AA00B929E8' => 'application/vnd.ms-powerpoint',
	];

	/**
	 * Read a file by name
	 *
	 * @param string $fileName The full path to the file
	 * @return array An associative array of information about the file:
	 *    - valid: true if the file is valid, false otherwise
	 *    - error: An error message in English, should be present if valid=false
	 *    - errorCode: One of the self::ERROR_* constants
	 *    - mime: The MIME type detected from the directory contents
	 *    - mimeFromClsid: The MIME type detected from the CLSID on the root
	 *      directory entry
	 */
	public static function readFile( $fileName ) {
		$handle = fopen( $fileName, 'r' );
		if ( $handle === false ) {
			return [
				'valid' => false,
				'error' => 'file does not exist',
				'errorCode' => self::ERROR_FILE_OPEN
			];
		}
		return self::readHandle( $handle );
	}

	/**
	 * Read from an open seekable handle
	 *
	 * @param resource $fileHandle The file handle
	 * @return array An associative array of information about the file:
	 *    - valid: true if the file is valid, false otherwise
	 *    - error: An error message in English, should be present if valid=false
	 *    - errorCode: One of the self::ERROR_* constants
	 *    - mime: The MIME type detected from the directory contents
	 *    - mimeFromClsid: The MIME type detected from the CLSID on the root
	 *      directory entry
	 */
	public static function readHandle( $fileHandle ) {
		$reader = new self( $fileHandle );
		$info = [
			'valid' => $reader->valid,
			'mime' => $reader->mime,
			'mimeFromClsid' => $reader->mimeFromClsid
		];
		if ( $reader->error ) {
			$info['error'] = $reader->error;
			$info['errorCode'] = $reader->errorCode;
		}
		return $info;
	}

	private function __construct( $fileHandle ) {
		$this->file = $fileHandle;
		try {
			$this->init();
		} catch ( RuntimeException $e ) {
			$this->valid = false;
			$this->error = $e->getMessage();
			$this->errorCode = $e->getCode();
		}
	}

	private function init() {
		$this->header = $this->unpackOffset( 0, [
			'header_signature' => 8,
			'header_clsid' => 16,
			'minor_version' => 2,
			'major_version' => 2,
			'byte_order' => 2,
			'sector_shift' => 2,
			'mini_sector_shift' => 2,
			'reserved' => 6,
			'num_dir_sectors' => 4,
			'num_fat_sectors' => 4,
			'first_dir_sector' => 4,
			'transaction_signature_number' => 4,
			'mini_stream_cutoff_size' => 4,
			'first_mini_fat_sector' => 4,
			'num_mini_fat_sectors' => 4,
			'first_difat_sector' => 4,
			'num_difat_sectors' => 4,
			'difat' => 436,
		] );
		if ( $this->header['header_signature'] !== "\xd0\xcf\x11\xe0\xa1\xb1\x1a\xe1" ) {
			$this->error( 'invalid signature: ' . bin2hex( $this->header['header_signature'] ),
				self::ERROR_INVALID_SIGNATURE );
		}
		// @phan-suppress-next-line PhanTypeInvalidRightOperandOfIntegerOp
		$this->sectorLength = 1 << $this->header['sector_shift'];
		$this->readDifat();
		$this->readDirectory();

		$this->valid = true;
	}

	private function sectorOffset( $sectorId ) {
		return $this->sectorLength * ( $sectorId + 1 );
	}

	private function decodeClsid( $binaryClsid ) {
		$parts = unpack( 'Va/vb/vc/C8d', $binaryClsid );
		return sprintf( "%08X-%04X-%04X-%02X%02X-%02X%02X%02X%02X%02X%02X",
			$parts['a'],
			$parts['b'],
			$parts['c'],
			$parts['d1'],
			$parts['d2'],
			$parts['d3'],
			$parts['d4'],
			$parts['d5'],
			$parts['d6'],
			$parts['d7'],
			$parts['d8']
		);
	}

	/**
	 * @param int $offset
	 * @param int[] $struct
	 * @return array
	 */
	private function unpackOffset( $offset, $struct ) {
		$block = $this->readOffset( $offset, array_sum( $struct ) );
		return $this->unpack( $block, 0, $struct );
	}

	/**
	 * @param string $block
	 * @param int $offset
	 * @param int[] $struct
	 * @return array
	 */
	private function unpack( $block, $offset, $struct ) {
		$data = [];
		foreach ( $struct as $key => $length ) {
			if ( $length > 4 ) {
				$data[$key] = substr( $block, $offset, $length );
			} else {
				$data[$key] = $this->bin2dec( $block, $offset, $length );
			}
			$offset += $length;
		}
		return $data;
	}

	private function bin2dec( $str, $offset, $length ) {
		$value = 0;
		for ( $i = $length - 1; $i >= 0; $i-- ) {
			$value *= 256;
			$value += ord( $str[$offset + $i] );
		}
		return $value;
	}

	private function readOffset( $offset, $length ) {
		$this->fseek( $offset );
		Wikimedia\suppressWarnings();
		$block = fread( $this->file, $length );
		Wikimedia\restoreWarnings();
		if ( $block === false ) {
			$this->error( 'error reading from file', self::ERROR_READ );
		}
		if ( strlen( $block ) !== $length ) {
			$this->error( 'unable to read the required number of bytes from the file',
				self::ERROR_READ_PAST_END );
		}
		return $block;
	}

	private function readSector( $sectorId ) {
		// @phan-suppress-next-line PhanTypeInvalidRightOperandOfIntegerOp
		return $this->readOffset( $this->sectorOffset( $sectorId ), 1 << $this->header['sector_shift'] );
	}

	private function error( $message, $code ) {
		throw new RuntimeException( $message, $code );
	}

	private function fseek( $offset ) {
		Wikimedia\suppressWarnings();
		$result = fseek( $this->file, $offset );
		Wikimedia\restoreWarnings();
		if ( $result !== 0 ) {
			$this->error( "unable to seek to offset $offset", self::ERROR_SEEK );
		}
	}

	private function readDifat() {
		$binaryDifat = $this->header['difat'];
		$nextDifatSector = $this->header['first_difat_sector'];
		for ( $i = 0; $i < $this->header['num_difat_sectors']; $i++ ) {
			$block = $this->readSector( $nextDifatSector );
			$binaryDifat .= substr( $block, 0, $this->sectorLength - 4 );
			$nextDifatSector = $this->bin2dec( $block, $this->sectorLength - 4, 4 );
			if ( $nextDifatSector == 0xFFFFFFFE ) {
				break;
			}
		}

		$this->difat = [];
		for ( $pos = 0; $pos < strlen( $binaryDifat ); $pos += 4 ) {
			$fatSector = $this->bin2dec( $binaryDifat, $pos, 4 );
			if ( $fatSector < 0xFFFFFFFC ) {
				$this->difat[] = $fatSector;
			} else {
				break;
			}
		}
	}

	private function getNextSectorIdFromFat( $sectorId ) {
		$entriesPerSector = intdiv( $this->sectorLength, 4 );
		$fatSectorId = intdiv( $sectorId, $entriesPerSector );
		$fatSectorArray = $this->getFatSector( $fatSectorId );
		return $fatSectorArray[$sectorId % $entriesPerSector];
	}

	private function getFatSector( $fatSectorId ) {
		if ( !isset( $this->fat[$fatSectorId] ) ) {
			$fat = [];
			if ( !isset( $this->difat[$fatSectorId] ) ) {
				$this->error( 'FAT sector requested beyond the end of the DIFAT', self::ERROR_INVALID_FORMAT );
			}
			$absoluteSectorId = $this->difat[$fatSectorId];
			$block = $this->readSector( $absoluteSectorId );
			for ( $pos = 0; $pos < strlen( $block ); $pos += 4 ) {
				$fat[] = $this->bin2dec( $block, $pos, 4 );
			}
			$this->fat[$fatSectorId] = $fat;
		}
		return $this->fat[$fatSectorId];
	}

	private function readDirectory() {
		$dirSectorId = $this->header['first_dir_sector'];
		$binaryDir = '';
		$seenSectorIds = [];
		while ( $dirSectorId !== 0xFFFFFFFE ) {
			if ( isset( $seenSectorIds[$dirSectorId] ) ) {
				$this->error( 'FAT loop detected', self::ERROR_INVALID_FORMAT );
			}
			$seenSectorIds[$dirSectorId] = true;

			$binaryDir .= $this->readSector( $dirSectorId );
			$dirSectorId = $this->getNextSectorIdFromFat( $dirSectorId );
		}

		$struct = [
			'name_raw' => 64,
			'name_length' => 2,
			'object_type' => 1,
			'color' => 1,
			'sid_left' => 4,
			'sid_right' => 4,
			'sid_child' => 4,
			'clsid' => 16,
			'state_bits' => 4,
			'create_time_low' => 4,
			'create_time_high' => 4,
			'modify_time_low' => 4,
			'modify_time_high' => 4,
			'first_sector' => 4,
			'size_low' => 4,
			'size_high' => 4,
		];
		$entryLength = array_sum( $struct );

		for ( $pos = 0; $pos < strlen( $binaryDir ); $pos += $entryLength ) {
			$entry = $this->unpack( $binaryDir, $pos, $struct );

			// According to [MS-CFB] size_high may contain garbage due to a
			// bug in a writer, it's best to pretend it is zero
			$entry['size_high'] = 0;

			$type = $entry['object_type'];
			if ( $type == self::TYPE_UNALLOCATED ) {
				continue;
			}

			$name = iconv( 'UTF-16LE', 'UTF-8', substr( $entry['name_raw'], 0, $entry['name_length'] - 2 ) );

			$clsid = $this->decodeClsid( $entry['clsid'] );
			if ( $type == self::TYPE_ROOT && isset( self::$mimesByClsid[$clsid] ) ) {
				$this->mimeFromClsid = self::$mimesByClsid[$clsid];
			}

			if ( $name === 'Workbook' ) {
				$this->mime = 'application/vnd.ms-excel';
			} elseif ( $name === 'WordDocument' ) {
				$this->mime = 'application/msword';
			} elseif ( $name === 'PowerPoint Document' ) {
				$this->mime = 'application/vnd.ms-powerpoint';
			}
		}
	}
}
