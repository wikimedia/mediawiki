<?php
/**
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

use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7\StreamDecoratorTrait;

/**
 * Callback-aware stream.  Allows using a callback function to receive data in contexts where
 * a PSR-7 stream is required.  This was created so that GuzzleHttpRequest can support our
 * "callback" option, for backward compatibility.  Newer code that uses GuzzleHttpRequest
 * should consider using the "sink" option instead.
 *
 * @private for use by GuzzleHttpRequest only
 * @since 1.33
 */
class MWCallbackStream implements StreamInterface {
	use StreamDecoratorTrait;

	private $callback;

	public function __construct( callable $cb ) {
		$this->stream = GuzzleHttp\Psr7\stream_for();
		$this->callback = $cb;
	}

	public function write( $string ) {
		return call_user_func( $this->callback, $this, $string );
	}
}
