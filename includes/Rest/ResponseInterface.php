<?php

/**
 * Copyright (c) 2019 Wikimedia Foundation.
 *
 * This file is partly derived from PSR-7, which requires the following copyright notice:
 *
 * Copyright (c) 2014 PHP Framework Interoperability Group
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @file
 */

namespace MediaWiki\Rest;

use Psr\Http\Message\StreamInterface;

/**
 * An interface similar to PSR-7's ResponseInterface, the primary difference
 * being that it is mutable.
 */
interface ResponseInterface {
	// ResponseInterface

	/**
	 * Gets the response status code.
	 *
	 * The status code is a 3-digit integer result code of the server's attempt
	 * to understand and satisfy the request.
	 *
	 * @return int Status code.
	 */
	function getStatusCode();

	/**
	 * Gets the response reason phrase associated with the status code.
	 *
	 * Because a reason phrase is not a required element in a response
	 * status line, the reason phrase value MAY be empty. Implementations MAY
	 * choose to return the default RFC 7231 recommended reason phrase (or those
	 * listed in the IANA HTTP Status Code Registry) for the response's
	 * status code.
	 *
	 * @see http://tools.ietf.org/html/rfc7231#section-6
	 * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
	 * @return string Reason phrase; must return an empty string if none present.
	 */
	function getReasonPhrase();

	// ResponseInterface mutation

	/**
	 * Set the status code and, optionally, reason phrase.
	 *
	 * If no reason phrase is specified, implementations MAY choose to default
	 * to the RFC 7231 or IANA recommended reason phrase for the response's
	 * status code.
	 *
	 * @see http://tools.ietf.org/html/rfc7231#section-6
	 * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
	 * @param int $code The 3-digit integer result code to set.
	 * @param string $reasonPhrase The reason phrase to use with the
	 *     provided status code; if none is provided, implementations MAY
	 *     use the defaults as suggested in the HTTP specification.
	 * @throws \InvalidArgumentException For invalid status code arguments.
	 */
	function setStatus( $code, $reasonPhrase = '' );

	// MessageInterface

	/**
	 * Retrieves the HTTP protocol version as a string.
	 *
	 * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
	 *
	 * @return string HTTP protocol version.
	 */
	function getProtocolVersion();

	/**
	 * Retrieves all message header values.
	 *
	 * The keys represent the header name as it will be sent over the wire, and
	 * each value is an array of strings associated with the header.
	 *
	 *     // Represent the headers as a string
	 *     foreach ($message->getHeaders() as $name => $values) {
	 *         echo $name . ': ' . implode(', ', $values);
	 *     }
	 *
	 *     // Emit headers iteratively:
	 *     foreach ($message->getHeaders() as $name => $values) {
	 *         foreach ($values as $value) {
	 *             header(sprintf('%s: %s', $name, $value), false);
	 *         }
	 *     }
	 *
	 * While header names are not case-sensitive, getHeaders() will preserve the
	 * exact case in which headers were originally specified.
	 *
	 * @return string[][] Returns an associative array of the message's headers.
	 *     Each key MUST be a header name, and each value MUST be an array of
	 *     strings for that header.
	 */
	function getHeaders();

	/**
	 * Checks if a header exists by the given case-insensitive name.
	 *
	 * @param string $name Case-insensitive header field name.
	 * @return bool Returns true if any header names match the given header
	 *     name using a case-insensitive string comparison. Returns false if
	 *     no matching header name is found in the message.
	 */
	function hasHeader( $name );

	/**
	 * Retrieves a message header value by the given case-insensitive name.
	 *
	 * This method returns an array of all the header values of the given
	 * case-insensitive header name.
	 *
	 * If the header does not appear in the message, this method MUST return an
	 * empty array.
	 *
	 * @param string $name Case-insensitive header field name.
	 * @return string[] An array of string values as provided for the given
	 *    header. If the header does not appear in the message, this method MUST
	 *    return an empty array.
	 */
	function getHeader( $name );

	/**
	 * Retrieves a comma-separated string of the values for a single header.
	 *
	 * This method returns all of the header values of the given
	 * case-insensitive header name as a string concatenated together using
	 * a comma.
	 *
	 * NOTE: Not all header values may be appropriately represented using
	 * comma concatenation. For such headers, use getHeader() instead
	 * and supply your own delimiter when concatenating.
	 *
	 * If the header does not appear in the message, this method MUST return
	 * an empty string.
	 *
	 * @param string $name Case-insensitive header field name.
	 * @return string A string of values as provided for the given header
	 *    concatenated together using a comma. If the header does not appear in
	 *    the message, this method MUST return an empty string.
	 */
	function getHeaderLine( $name );

	/**
	 * Gets the body of the message.
	 *
	 * @return StreamInterface Returns the body as a stream.
	 */
	function getBody();

	// MessageInterface mutation

	/**
	 * Set the HTTP protocol version.
	 *
	 * The version string MUST contain only the HTTP version number (e.g.,
	 * "1.1", "1.0").
	 *
	 * @param string $version HTTP protocol version
	 */
	function setProtocolVersion( $version );

	/**
	 * Set or replace the specified header.
	 *
	 * While header names are case-insensitive, the casing of the header will
	 * be preserved by this function, and returned from getHeaders().
	 *
	 * @param string $name Case-insensitive header field name.
	 * @param string|string[] $value Header value(s).
	 * @throws \InvalidArgumentException for invalid header names or values.
	 */
	function setHeader( $name, $value );

	/**
	 * Append the given value to the specified header.
	 *
	 * Existing values for the specified header will be maintained. The new
	 * value(s) will be appended to the existing list. If the header did not
	 * exist previously, it will be added.
	 *
	 * @param string $name Case-insensitive header field name to add.
	 * @param string|string[] $value Header value(s).
	 * @throws \InvalidArgumentException for invalid header names.
	 * @throws \InvalidArgumentException for invalid header values.
	 */
	function addHeader( $name, $value );

	/**
	 * Remove the specified header.
	 *
	 * Header resolution MUST be done without case-sensitivity.
	 *
	 * @param string $name Case-insensitive header field name to remove.
	 */
	function removeHeader( $name );

	/**
	 * Set the message body
	 *
	 * The body MUST be a StreamInterface object.
	 *
	 * @param StreamInterface $body Body.
	 * @throws \InvalidArgumentException When the body is not valid.
	 */
	function setBody( StreamInterface $body );

	// MediaWiki extensions to PSR-7

	/**
	 * Get the full header lines including colon-separated name and value, for
	 * passing directly to header(). Not including the status line.
	 *
	 * @return string[]
	 */
	function getRawHeaderLines();

	/**
	 * Set a cookie
	 *
	 * The name will have the cookie prefix added to it before it is sent over
	 * the network.
	 *
	 * @param string $name The name of the cookie, not including prefix.
	 * @param string $value The value to be stored in the cookie.
	 * @param int|null $expire Unix timestamp (in seconds) when the cookie should expire.
	 *        0 (the default) causes it to expire $wgCookieExpiration seconds from now.
	 *        null causes it to be a session cookie.
	 * @param array $options Assoc of additional cookie options:
	 *     prefix: string, name prefix ($wgCookiePrefix)
	 *     domain: string, cookie domain ($wgCookieDomain)
	 *     path: string, cookie path ($wgCookiePath)
	 *     secure: bool, secure attribute ($wgCookieSecure)
	 *     httpOnly: bool, httpOnly attribute ($wgCookieHttpOnly)
	 */
	public function setCookie( $name, $value, $expire = 0, $options = [] );

	/**
	 * Get all previously set cookies as a list of associative arrays with
	 * the following keys:
	 *
	 *  - name: The cookie name
	 *  - value: The cookie value
	 *  - expire: The requested expiry time
	 *  - options: An associative array of further options
	 *
	 * @return array
	 */
	public function getCookies();
}
