<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use GuzzleHttp\Psr7\StreamDecoratorTrait;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;

/**
 * Callback-aware stream.  Allows using a callback function to receive data in contexts where
 * a PSR-7 stream is required.  This was created so that GuzzleHttpRequest can support our
 * "callback" option, for backward compatibility.  Newer code that uses GuzzleHttpRequest
 * should consider using the "sink" option instead.
 *
 * @internal for use by GuzzleHttpRequest only
 * @since 1.33
 * @property StreamInterface $stream Defined in StreamDecoratorTrait via `@property`, not read
 *  by phan
 */
class MWCallbackStream implements StreamInterface {
	use StreamDecoratorTrait;

	/** @var callable */
	private $callback;

	/** @var StreamInterface */
	protected $stream;

	public function __construct( callable $cb ) {
		$this->stream = Utils::streamFor();
		$this->callback = $cb;
	}

	/** @inheritDoc */
	public function write( $string ) {
		return ( $this->callback )( $this, $string );
	}
}
