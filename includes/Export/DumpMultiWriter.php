<?php
/**
 * Base class for output stream; prints to stdout or buffer or wherever.
 *
 * Copyright © 2003, 2005, 2006 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * @ingroup Dump
 */
class DumpMultiWriter {
	/** @var array */
	private $sinks;
	/** @var int */
	private $count;

	/**
	 * @param array $sinks
	 */
	public function __construct( $sinks ) {
		$this->sinks = $sinks;
		$this->count = count( $sinks );
	}

	/**
	 * @param string $string
	 */
	public function writeOpenStream( $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeOpenStream( $string );
		}
	}

	/**
	 * @param string $string
	 */
	public function writeCloseStream( $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeCloseStream( $string );
		}
	}

	/**
	 * @param stdClass $page
	 * @param string $string
	 */
	public function writeOpenPage( $page, $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeOpenPage( $page, $string );
		}
	}

	/**
	 * @param string $string
	 */
	public function writeClosePage( $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeClosePage( $string );
		}
	}

	/**
	 * @param stdClass $rev
	 * @param string $string
	 */
	public function writeRevision( $rev, $string ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->writeRevision( $rev, $string );
		}
	}

	/**
	 * @param array $newnames
	 */
	public function closeRenameAndReopen( $newnames ) {
		$this->closeAndRename( $newnames, true );
	}

	/**
	 * @param array $newnames
	 * @param bool $open
	 */
	public function closeAndRename( $newnames, $open = false ) {
		for ( $i = 0; $i < $this->count; $i++ ) {
			$this->sinks[$i]->closeAndRename( $newnames[$i], $open );
		}
	}

	/**
	 * @return array
	 */
	public function getFilenames() {
		$filenames = [];
		for ( $i = 0; $i < $this->count; $i++ ) {
			$filenames[] = $this->sinks[$i]->getFilenames();
		}
		return $filenames;
	}
}
