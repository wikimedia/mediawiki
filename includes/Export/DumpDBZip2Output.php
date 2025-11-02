<?php
/**
 * Sends dump output via the bgzip2 compressor.
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
class DumpDBZip2Output extends DumpPipeOutput {
	/**
	 * @param string $file
	 */
	public function __construct( $file ) {
		parent::__construct( "dbzip2", $file );
	}
}
