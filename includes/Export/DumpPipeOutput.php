<?php
/**
 * Stream outputter to send data to a file via some filter program.
 * Even if compression is available in a library, using a separate
 * program can allow us to make use of a multi-processor system.
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
class DumpPipeOutput extends DumpFileOutput {
	/** @var string */
	protected $command;
	/** @var string|null */
	protected $filename;
	/** @var resource|false */
	protected $procOpenResource = false;

	/**
	 * @param string $command
	 * @param string|null $file
	 */
	public function __construct( $command, $file = null ) {
		if ( $file !== null ) {
			$command .= " > " . Shell::escape( $file );
		}

		$this->startCommand( $command );
		$this->command = $command;
		$this->filename = $file;
	}

	/**
	 * @param string $string
	 */
	public function writeCloseStream( $string ) {
		parent::writeCloseStream( $string );
		if ( $this->procOpenResource ) {
			proc_close( $this->procOpenResource );
			$this->procOpenResource = false;
		}
	}

	/**
	 * @param string $command
	 */
	public function startCommand( $command ) {
		$spec = [
			0 => [ "pipe", "r" ],
		];
		$pipes = [];
		$this->procOpenResource = proc_open( $command, $spec, $pipes );
		$this->handle = $pipes[0];
	}

	/**
	 * @inheritDoc
	 */
	public function closeRenameAndReopen( $newname ) {
		$this->closeAndRename( $newname, true );
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
			if ( $this->procOpenResource ) {
				proc_close( $this->procOpenResource );
				$this->procOpenResource = false;
			}
			$this->renameOrException( $newname );
			if ( $open ) {
				$command = $this->command;
				$command .= " > " . Shell::escape( $this->filename );
				$this->startCommand( $command );
			}
		}
	}
}
