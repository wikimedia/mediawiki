<?php
/**
 * Stream outputter to send data to a file.
 *
 * Copyright © 2003, 2005, 2006 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Exception\MWException;

/**
 * @ingroup Dump
 */
class DumpFileOutput extends DumpOutput {
	/** @var resource|false */
	protected $handle = false;
	/** @var string */
	protected $filename;

	/**
	 * @param string $file
	 */
	public function __construct( $file ) {
		$this->handle = fopen( $file, "wt" );
		$this->filename = $file;
	}

	/**
	 * @param string $string
	 */
	public function writeCloseStream( $string ) {
		parent::writeCloseStream( $string );
		if ( $this->handle ) {
			fclose( $this->handle );
			$this->handle = false;
		}
	}

	/**
	 * @param string $string
	 */
	public function write( $string ) {
		fputs( $this->handle, $string );
	}

	/**
	 * @inheritDoc
	 */
	public function closeRenameAndReopen( $newname ) {
		$this->closeAndRename( $newname, true );
	}

	/**
	 * @param string $newname
	 */
	protected function renameOrException( $newname ) {
		if ( !rename( $this->filename, $newname ) ) {
			throw new RuntimeException( __METHOD__ . ": rename of file {$this->filename} to $newname failed\n" );
		}
	}

	/**
	 * @param string|string[] $newname
	 * @return string
	 * @throws MWException
	 */
	protected function checkRenameArgCount( $newname ) {
		if ( is_array( $newname ) ) {
			if ( count( $newname ) > 1 ) {
				throw new MWException( __METHOD__ . ": passed multiple arguments for rename of single file\n" );
			}
			$newname = $newname[0];
		}
		return $newname;
	}

	/**
	 * @inheritDoc
	 */
	public function closeAndRename( $newname, $open = false ) {
		$newname = $this->checkRenameArgCount( $newname );
		if ( $newname ) {
			if ( $this->handle ) {
				fclose( $this->handle );
				$this->handle = false;
			}
			$this->renameOrException( $newname );
			if ( $open ) {
				$this->handle = fopen( $this->filename, "wt" );
			}
		}
	}

	/**
	 * @return string|null
	 */
	public function getFilenames() {
		return $this->filename;
	}
}
