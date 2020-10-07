<?php
/**
 * Base class for output stream; prints to stdout or buffer or wherever.
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
	 * @param object $page
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
	 * @param object $rev
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
