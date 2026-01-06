<?php
/**
 * Sends dump output via the lbzip2 compressor.
 *
 * Copyright © 2003, 2005, 2006 Brooke Vibber <bvibber@wikimedia.org>
 * Copyright © 2019 Wikimedia Foundation Inc.
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Export;

/**
 * @ingroup Dump
 * @since 1.33
 */
class DumpLBZip2Output extends DumpPipeOutput {
	/**
	 * @param string $file
	 */
	public function __construct( $file ) {
		# use only one core
		parent::__construct( "lbzip2 -n 1", $file );
	}
}

/** @deprecated class alias since 1.46 */
class_alias( DumpLBZip2Output::class, 'DumpLBZip2Output' );
