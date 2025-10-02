<?php
/**
 * Sends dump output via the gzip compressor.
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
class DumpGZipOutput extends DumpPipeOutput {
	/**
	 * @param string $file
	 */
	public function __construct( $file ) {
		parent::__construct( "gzip", $file );
	}
}
