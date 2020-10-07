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
	 * Equivalent to reading from the object until EOF and writing the
	 * resulting data to $stream. The position will be advanced to the end.
	 *
	 * Note that $stream is not a StreamInterface object.
	 *
	 * @param resource $stream Destination
	 */
	public function copyToStream( $stream );
}
