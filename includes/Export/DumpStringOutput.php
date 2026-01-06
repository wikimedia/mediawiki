<?php
/**
 * Stream outputter that buffers and returns data as a string.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Export;

use Stringable;

/**
 * @ingroup Dump
 * @since 1.28
 */
class DumpStringOutput extends DumpOutput implements Stringable {
	/** @var string */
	private $output = '';

	/**
	 * @param string $string
	 */
	public function write( $string ) {
		$this->output .= $string;
	}

	/**
	 * Get the string containing the output.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->output;
	}
}

/** @deprecated class alias since 1.46 */
class_alias( DumpStringOutput::class, 'DumpStringOutput' );
