<?php
/**
 * Dump output filter class.
 * This just does output filtering and streaming; XML formatting is done
 * higher up, so be careful in what you do.
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
class DumpFilter {
	/**
	 * @var DumpOutput
	 * FIXME will need to be made protected whenever legacy code
	 * is updated.
	 */
	public $sink;

	/**
	 * @var bool
	 */
	protected $sendingThisPage;

	/**
	 * @param DumpOutput &$sink
	 */
	public function __construct( &$sink ) {
		$this->sink =& $sink;
	}

	/**
	 * @param string $string
	 */
	public function writeOpenStream( $string ) {
		$this->sink->writeOpenStream( $string );
	}

	/**
	 * @param string $string
	 */
	public function writeCloseStream( $string ) {
		$this->sink->writeCloseStream( $string );
	}

	/**
	 * @param stdClass|null $page
	 * @param string $string
	 */
	public function writeOpenPage( $page, $string ) {
		$this->sendingThisPage = $this->pass( $page );
		if ( $this->sendingThisPage ) {
			$this->sink->writeOpenPage( $page, $string );
		}
	}

	/**
	 * @param string $string
	 */
	public function writeClosePage( $string ) {
		if ( $this->sendingThisPage ) {
			$this->sink->writeClosePage( $string );
			$this->sendingThisPage = false;
		}
	}

	/**
	 * @param stdClass|null $rev
	 * @param string $string
	 */
	public function writeRevision( $rev, $string ) {
		if ( $this->sendingThisPage ) {
			$this->sink->writeRevision( $rev, $string );
		}
	}

	/**
	 * @param stdClass $rev
	 * @param string $string
	 */
	public function writeLogItem( $rev, $string ) {
		$this->sink->writeRevision( $rev, $string );
	}

	/**
	 * @see DumpOutput::closeRenameAndReopen()
	 * @param string|string[] $newname
	 */
	public function closeRenameAndReopen( $newname ) {
		$this->sink->closeRenameAndReopen( $newname );
	}

	/**
	 * @see DumpOutput::closeAndRename()
	 * @param string|string[] $newname
	 * @param bool $open
	 */
	public function closeAndRename( $newname, $open = false ) {
		$this->sink->closeAndRename( $newname, $open );
	}

	/**
	 * @return array
	 */
	public function getFilenames() {
		return $this->sink->getFilenames() ?? [];
	}

	/**
	 * Override for page-based filter types.
	 * @param stdClass|null $page
	 * @return bool
	 */
	protected function pass( $page ) {
		return true;
	}
}
