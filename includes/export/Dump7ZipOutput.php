<?php
/**
 * Sends dump output via the p7zip compressor.
 *
 * Copyright © 2003, 2005, 2006 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Shell\Shell;

/**
 * @ingroup Dump
 */
class Dump7ZipOutput extends DumpPipeOutput {
	/**
	 * @var int
	 */
	protected $compressionLevel;

	/**
	 * @param string $file
	 * @param int $cmpLevel Compression level passed to 7za command's -mx
	 */
	public function __construct( $file, $cmpLevel = 4 ) {
		$this->compressionLevel = $cmpLevel;
		$command = $this->setup7zCommand( $file );
		parent::__construct( $command );
		$this->filename = $file;
	}

	/**
	 * @param string $file
	 * @return string
	 */
	private function setup7zCommand( $file ) {
		$command = "7za a -bd -si -mx=";
		$command .= Shell::escape( (string)$this->compressionLevel ) . ' ';
		$command .= Shell::escape( $file );
		// Suppress annoying useless crap from p7zip
		// Unfortunately this could suppress real error messages too
		$command .= ' >' . wfGetNull() . ' 2>&1';
		return $command;
	}

	/**
	 * @inheritDoc
	 */
	public function closeAndRename( $newname, $open = false ) {
		$newname = $this->checkRenameArgCount( $newname );
		if ( $newname ) {
			fclose( $this->handle );
			proc_close( $this->procOpenResource );
			$this->renameOrException( $newname );
			if ( $open ) {
				$command = $this->setup7zCommand( $this->filename );
				$this->startCommand( $command );
			}
		}
	}
}
