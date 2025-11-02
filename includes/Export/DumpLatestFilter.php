<?php
/**
 * Dump output filter to include only the last revision in each page sequence.
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
class DumpLatestFilter extends DumpFilter {
	/** @var stdClass|null */
	public $page;

	/** @var string|null */
	public $pageString;

	/** @var stdClass|null */
	public $rev;

	/** @var string|null */
	public $revString;

	/**
	 * @param stdClass $page
	 * @param string $string
	 */
	public function writeOpenPage( $page, $string ) {
		$this->page = $page;
		$this->pageString = $string;
	}

	/**
	 * @param string $string
	 */
	public function writeClosePage( $string ) {
		if ( $this->rev ) {
			$this->sink->writeOpenPage( $this->page, $this->pageString );
			$this->sink->writeRevision( $this->rev, $this->revString );
			$this->sink->writeClosePage( $string );
		}
		$this->rev = null;
		$this->revString = null;
		$this->page = null;
		$this->pageString = null;
	}

	/**
	 * @param stdClass $rev
	 * @param string $string
	 */
	public function writeRevision( $rev, $string ) {
		if ( $rev->rev_id == $this->page->page_latest ) {
			$this->rev = $rev;
			$this->revString = $string;
		}
	}
}
