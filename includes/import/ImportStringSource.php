<?php
/**
 * MediaWiki page data importer.
 *
 * Copyright © 2003,2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

/**
 * Used for importing XML dumps where the content of the dump is in a string.
 * This class is inefficient, and should only be used for small dumps.
 * For larger dumps, ImportStreamSource should be used instead.
 *
 * @ingroup SpecialPage
 */
class ImportStringSource implements ImportSource {
	/** @var string */
	private $mString;

	/** @var bool */
	private $mRead = false;

	/**
	 * @param string $string
	 */
	public function __construct( $string ) {
		$this->mString = $string;
	}

	/**
	 * @return bool
	 */
	public function atEnd() {
		return $this->mRead;
	}

	/**
	 * @return bool|string
	 */
	public function readChunk() {
		if ( $this->atEnd() ) {
			return false;
		}
		$this->mRead = true;
		return $this->mString;
	}

	/**
	 * @return bool
	 */
	public function isSeekable() {
		return true;
	}

	/**
	 * @param int $offset
	 * @return int
	 */
	public function seek( int $offset ) {
		$this->mRead = false;
		return 0;
	}
}
