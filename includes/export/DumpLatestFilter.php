<?php
/**
 * Dump output filter to include only the last revision in each page sequence.
 *
 * Copyright © 2003, 2005, 2006 Brion Vibber <brion@pobox.com>
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
 */

/**
 * @ingroup Dump
 */
class DumpLatestFilter extends DumpFilter {
	public $page;

	public $pageString;

	public $rev;

	public $revString;

	/**
	 * @param object $page
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
	 * @param object $rev
	 * @param string $string
	 */
	public function writeRevision( $rev, $string ) {
		if ( $rev->rev_id == $this->page->page_latest ) {
			$this->rev = $rev;
			$this->revString = $string;
		}
	}
}
