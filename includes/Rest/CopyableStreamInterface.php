<?php

namespace MediaWiki\Rest;

/**
 * An interface for a stream with a copyToStream() function.
 */
interface CopyableStreamInterface extends \Psr\Http\Message\StreamInterface {
	/**
	 * Copy this stream to a specified stream resource. For some streams,
	 * this can be implemented without a tight loop in PHP code.
	 *
	 * Note that $stream is not a StreamInterface object.
	 *
	 * @param resource $stream Destination
	 */
	function copyToStream( $stream );
}
