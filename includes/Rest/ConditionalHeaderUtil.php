<?php

namespace MediaWiki\Rest;

use MediaWiki\Rest\HeaderParser\HttpDate;
use MediaWiki\Rest\HeaderParser\IfNoneMatch;
use Wikimedia\Timestamp\ConvertibleTimestamp;

class ConditionalHeaderUtil {
	private $validatorsHaveBeenSet = false;
	private $eTag;
	private $lastModified;
	private $hasRepresentation;

	/**
	 * Initialize the object with information about the requested resource.
	 *
	 * @param string|null $eTag The entity-tag (including quotes), or null if
	 *   it is unknown.
	 * @param string|int|null $lastModified The Last-Modified date in a format
	 *   accepted by ConvertibleTimestamp, or null if it is unknown.
	 * @param bool|null $hasRepresentation Whether the server has a current
	 *   representation of the target resource. This should be true if the
	 *   resource exists, and false if it does not exist. It is used for
	 *   wildcard validators -- the intended use case is to abort a PUT if the
	 *   resource does (or does not) exist. If null is passed, we assume that
	 *   the resource exists if an ETag was specified for it.
	 */
	public function setValidators( $eTag, $lastModified, $hasRepresentation ) {
		$this->validatorsHaveBeenSet = true;
		$this->eTag = $eTag;
		if ( $lastModified === null ) {
			$this->lastModified = null;
		} else {
			$this->lastModified = ConvertibleTimestamp::convert( TS_UNIX, $lastModified );
		}
		if ( $hasRepresentation === null ) {
			$hasRepresentation = $eTag !== null;
		}
		$this->hasRepresentation = $hasRepresentation;
	}

	/**
	 * Check conditional request headers in the order required by RFC 7232 section 6.
	 *
	 * @param RequestInterface $request
	 * @return int|null The status code to immediately return, or null to
	 *   continue processing the request.
	 */
	public function checkPreconditions( RequestInterface $request ) {
		$parser = new IfNoneMatch;
		if ( $this->eTag !== null ) {
			$resourceTag = $parser->parseETag( $this->eTag );
			if ( !$resourceTag ) {
				throw new \Exception( 'Invalid ETag returned by handler: ' .
					$parser->getLastError() );
			}
		} else {
			$resourceTag = null;
		}
		$getOrHead = in_array( $request->getMethod(), [ 'GET', 'HEAD' ] );
		if ( $request->hasHeader( 'If-Match' ) ) {
			$im = $request->getHeader( 'If-Match' );
			$match = false;
			foreach ( $parser->parseHeaderList( $im ) as $tag ) {
				if ( $tag['whole'] === '*' && $this->hasRepresentation ) {
					$match = true;
					break;
				}
				if ( $this->strongCompare( $resourceTag, $tag ) ) {
					$match = true;
					break;
				}
			}
			if ( !$match ) {
				return 412;
			}
		} elseif ( $request->hasHeader( 'If-Unmodified-Since' ) ) {
			$requestDate = HttpDate::parse( $request->getHeader( 'If-Unmodified-Since' )[0] );
			if ( $requestDate !== null
				&& ( $this->lastModified === null || $this->lastModified > $requestDate )
			) {
				return 412;
			}
		}
		if ( $request->hasHeader( 'If-None-Match' ) ) {
			$inm = $request->getHeader( 'If-None-Match' );
			foreach ( $parser->parseHeaderList( $inm ) as $tag ) {
				if ( $this->weakCompare( $resourceTag, $tag ) ) {
					if ( $getOrHead ) {
						return 304;
					} else {
						return 412;
					}
				}
			}
		} elseif ( $getOrHead && $request->hasHeader( 'If-Modified-Since' ) ) {
			$requestDate = HttpDate::parse( $request->getHeader( 'If-Modified-Since' )[0] );
			if ( $requestDate !== null && $this->lastModified !== null
				&& $this->lastModified <= $requestDate
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
	 *
	 * @param ResponseInterface $response
	 */
	public function applyResponseHeaders( ResponseInterface $response ) {
		if ( $this->lastModified !== null && !$response->hasHeader( 'Last-Modified' ) ) {
			$response->setHeader( 'Last-Modified', HttpDate::format( $this->lastModified ) );
		}
		if ( $this->eTag !== null && !$response->hasHeader( 'ETag' ) ) {
			$response->setHeader( 'ETag', $this->eTag );
		}
	}

	/**
	 * The weak comparison function
	 *
	 * @param array $tag1 Parsed tag info array
	 * @param array $tag2 Parsed tag info array
	 * @return bool
	 */
	private function weakCompare( $tag1, $tag2 ) {
		return $tag1['contents'] === $tag2['contents'];
	}

	/**
	 * The strong comparison function
	 *
	 * @param array|null $tag1 Parsed tag info array
	 * @param array|null $tag2 Parsed tag info array
	 * @return bool
	 */
	private function strongCompare( $tag1, $tag2 ) {
		if ( $tag1 === null || $tag2 === null ) {
			return false;
		}
		return !$tag1['weak'] && !$tag2['weak'] && $tag1['contents'] === $tag2['contents'];
	}
}
