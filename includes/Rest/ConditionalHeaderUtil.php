<?php

namespace MediaWiki\Rest;

use MediaWiki\Rest\HeaderParser\HttpDate;
use MediaWiki\Rest\HeaderParser\IfNoneMatch;
use RuntimeException;
use Wikimedia\Timestamp\ConvertibleTimestamp;

class ConditionalHeaderUtil {
	/** @var bool */
	private $varnishETagHack = true;
	/** @var callback|string|null */
	private $eTag;
	/** @var callback|string|int|null */
	private $lastModified;
	/** @var callback|bool */
	private $hasRepresentation;

	private IfNoneMatch $eTagParser;

	private ?array $eTagParts = null;

	public function __construct() {
		$this->eTagParser = new IfNoneMatch;
	}

	/**
	 * Initialize the object with information about the requested resource.
	 *
	 * @param callable|string|null $eTag The entity-tag (including quotes), or null if
	 *   it is unknown. Can also be provided as a callback for later evaluation.
	 * @param callable|string|int|null $lastModified The Last-Modified date in a format
	 *   accepted by ConvertibleTimestamp, or null if it is unknown.
	 *   Can also be provided as a callback for later evaluation.
	 * @param callable|bool|null $hasRepresentation Whether the server can serve a
	 *   representation of the target resource. This should be true if the
	 *   resource exists, and false if it does not exist. It is used for
	 *   wildcard validators -- the intended use case is to abort a PUT if the
	 *   resource does (or does not) exist. If null is passed, we assume that
	 *   the resource exists if an ETag or last-modified data was specified for it.
	 *   Can also be provided as a callback for later evaluation.
	 */
	public function setValidators(
		$eTag,
		$lastModified,
		$hasRepresentation = null
	) {
		$this->eTag = $eTag;
		$this->lastModified = $lastModified;
		$this->hasRepresentation = $hasRepresentation;
	}

	/**
	 * If the Varnish ETag hack is disabled by calling this method,
	 * strong ETag comparison will follow RFC 7232, rejecting all weak
	 * ETags for If-Match comparison.
	 *
	 * @param bool $hack
	 */
	public function setVarnishETagHack( $hack ) {
		$this->varnishETagHack = $hack;
	}

	private function getETag(): ?string {
		if ( is_callable( $this->eTag ) ) {
			// resolve callback
			$this->eTag = ( $this->eTag )();
		}

		return $this->eTag;
	}

	private function getETagParts(): ?array {
		if ( $this->eTagParts !== null ) {
			return $this->eTagParts;
		}

		$eTag = $this->getETag();

		if ( $eTag === null ) {
			return null;
		}

		$this->eTagParts = $this->eTagParser->parseETag( $eTag );
		if ( !$this->eTagParts ) {
			throw new RuntimeException( 'Invalid ETag returned by handler: `' .
				$this->eTagParser->getLastError() . '`' );
		}

		return $this->eTagParts;
	}

	private function getLastModified(): ?int {
		if ( is_callable( $this->lastModified ) ) {
			// resolve callback
			$this->lastModified = ( $this->lastModified )();
		}

		if ( is_string( $this->lastModified ) ) {
			// normalize to int
			$this->lastModified = (int)ConvertibleTimestamp::convert(
				TS_UNIX,
				$this->lastModified
			);
		}

		// should be int or null now.
		return $this->lastModified;
	}

	private function hasRepresentation(): bool {
		if ( is_callable( $this->hasRepresentation ) ) {
			// resolve callback
			$this->hasRepresentation = ( $this->hasRepresentation )();
		}

		if ( $this->hasRepresentation === null ) {
			// apply fallback
			$this->hasRepresentation = $this->getETag() !== null
				|| $this->getLastModified() !== null;
		}

		return $this->hasRepresentation;
	}

	/**
	 * Check conditional request headers in the order required by RFC 7232 section 6.
	 *
	 * @param RequestInterface $request
	 * @return int|null The status code to immediately return, or null to
	 *   continue processing the request.
	 */
	public function checkPreconditions( RequestInterface $request ) {
		$getOrHead = in_array( $request->getMethod(), [ 'GET', 'HEAD' ] );
		if ( $request->hasHeader( 'If-Match' ) ) {
			$im = $request->getHeader( 'If-Match' );
			$match = false;
			foreach ( $this->eTagParser->parseHeaderList( $im ) as $tag ) {
				if ( ( $tag['whole'] === '*' && $this->hasRepresentation() ) ||
					$this->strongCompare( $this->getETagParts(), $tag )
				) {
					$match = true;
					break;
				}
			}
			if ( !$match ) {
				return 412;
			}
		} elseif ( $request->hasHeader( 'If-Unmodified-Since' ) ) {
			$requestDate = HttpDate::parse( $request->getHeader( 'If-Unmodified-Since' )[0] );
			$lastModified = $this->getLastModified();
			if ( $requestDate !== null
				&& ( $lastModified === null || $lastModified > $requestDate )
			) {
				return 412;
			}
		}
		if ( $request->hasHeader( 'If-None-Match' ) ) {
			$inm = $request->getHeader( 'If-None-Match' );
			foreach ( $this->eTagParser->parseHeaderList( $inm ) as $tag ) {
				if ( ( $tag['whole'] === '*' && $this->hasRepresentation() ) ||
					$this->weakCompare( $this->getETagParts(), $tag )
				) {
					return $getOrHead ? 304 : 412;
				}
			}
		} elseif ( $getOrHead && $request->hasHeader( 'If-Modified-Since' ) ) {
			$requestDate = HttpDate::parse( $request->getHeader( 'If-Modified-Since' )[0] );
			$lastModified = $this->getLastModified();
			if ( $requestDate !== null && $lastModified !== null
				&& $lastModified <= $requestDate
			) {
				return 304;
			}
		}
		// RFC 7232 states that If-Range should be evaluated here. However, the
		// purpose of If-Range is to cause the Range request header to be
		// conditionally ignored, not to immediately send a response, so it
		// doesn't fit here. RFC 7232 only requires that If-Range be checked
		// after the other conditional header fields, a requirement that is
		// satisfied if it is processed in Handler::execute().
		return null;
	}

	/**
	 * Set Last-Modified and ETag headers in the response according to the cached
	 * values set by setValidators(), which are also used for precondition checks.
	 *
	 * If the headers are already present in the response, the existing headers
	 * take precedence.
	 */
	public function applyResponseHeaders( ResponseInterface $response ) {
		if ( $response->getStatusCode() >= 400
			|| $response->getStatusCode() === 301
			|| $response->getStatusCode() === 307
		) {
			// Don't add Last-Modified and ETag for errors, including 412.
			// Note that 304 responses are required to have these headers set.
			// See IETF RFC 7232 section 4.
			return;
		}

		$lastModified = $this->getLastModified();
		if ( $lastModified !== null && !$response->hasHeader( 'Last-Modified' ) ) {
			$response->setHeader( 'Last-Modified', HttpDate::format( $lastModified ) );
		}

		$eTag = $this->getETag();
		if ( $eTag !== null && !$response->hasHeader( 'ETag' ) ) {
			$response->setHeader( 'ETag', $eTag );
		}
	}

	/**
	 * The weak comparison function, per RFC 7232, section 2.3.2.
	 *
	 * @param array|null $resourceETag ETag generated by the handler, parsed tag info array
	 * @param array|null $headerETag ETag supplied by the client, parsed tag info array
	 * @return bool
	 */
	private function weakCompare( $resourceETag, $headerETag ) {
		if ( $resourceETag === null || $headerETag === null ) {
			return false;
		}
		return $resourceETag['contents'] === $headerETag['contents'];
	}

	/**
	 * The strong comparison function
	 *
	 * A strong ETag returned by the server may have been "weakened" by Varnish when applying
	 * compression. So optionally ignore the weakness of the header.
	 * {@link https://varnish-cache.org/docs/6.0/users-guide/compression.html}.
	 * @see T238849 and T310710
	 *
	 * @param array|null $resourceETag ETag generated by the handler, parsed tag info array
	 * @param array|null $headerETag ETag supplied by the client, parsed tag info array
	 *
	 * @return bool
	 */
	private function strongCompare( $resourceETag, $headerETag ) {
		if ( $resourceETag === null || $headerETag === null ) {
			return false;
		}

		return !$resourceETag['weak']
			&& ( $this->varnishETagHack || !$headerETag['weak'] )
			&& $resourceETag['contents'] === $headerETag['contents'];
	}

}
