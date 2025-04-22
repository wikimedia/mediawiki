<?php

namespace MediaWiki\Edit;

use InvalidArgumentException;
use MediaWiki\Parser\ParserOutput;
use Stringable;
use function count;

/**
 * Represents the identity of a specific rendering of a specific revision
 * at some point in time.
 *
 * @since 1.39
 * @unstable since 1.39, should be stable by 1.39 release.
 */
class ParsoidRenderID implements Stringable {
	private int $revisionID;
	private string $uniqueID;
	private string $stashKey;

	/**
	 * @param int $revisionID Revision that was rendered
	 * @param string $uniqueID An identifier for a point in time.
	 */
	public function __construct( int $revisionID, string $uniqueID ) {
		$this->revisionID = $revisionID;
		$this->uniqueID = $uniqueID;
		$this->stashKey = $revisionID . '/' . $uniqueID;
	}

	/**
	 * @param string $key String representation of render ID
	 * (synonymous with an etag with double quotes) as returned by ::getKey().
	 *
	 * @return self
	 * @see newFromETag()
	 *
	 */
	public static function newFromKey( string $key ): self {
		$parts = explode( '/', $key, 2 );

		if ( count( $parts ) < 2 ) {
			throw new InvalidArgumentException( 'Bad key: ' . $key );
		}

		[ $revisionID, $uniqueID ] = $parts;

		return new self( (int)$revisionID, $uniqueID );
	}

	/**
	 * Create a ParsoidRenderID from the revision and render id stored in
	 * a ParserOutput.
	 * @param ParserOutput $parserOutput
	 * @return self
	 */
	public static function newFromParserOutput( ParserOutput $parserOutput ): self {
		$revisionID = $parserOutput->getCacheRevisionId();
		$uniqueID = $parserOutput->getRenderId();

		if ( $revisionID === null || $uniqueID === null ) {
			throw new InvalidArgumentException( 'Missing render id' );
		}

		return new self( $revisionID, $uniqueID );
	}

	/**
	 * This constructs a new render ID from the given ETag.
	 *
	 * Any suffix after a second forward slash will be ignored e.g.
	 * ->newFromEtag( '1/abc/stash' ) will return '1/abc' when ->getKey()
	 * is called on the ParsoidRenderID object instance.
	 *
	 * @param string $eTag ETag with double quotes,
	 *   see https://www.rfc-editor.org/rfc/rfc7232#section-2.3
	 *
	 * @return ParsoidRenderID|null The render ID embedded in the ETag,
	 *         or null if the ETag was malformed.
	 * @see newFromKey() if ETag already has outside quotes trimmed
	 *
	 */
	public static function newFromETag( string $eTag ): ?self {
		if ( !preg_match( '@^(?:W/)?"(\d+)/([^/]+)(:?/.*)?"$@', $eTag, $m ) ) {
			return null;
		}

		[ , $revisionID, $uniqueID ] = $m;

		return new self( (int)$revisionID, $uniqueID );
	}

	/**
	 * This returns the canonical string representation from
	 * the parsoid render ID which can be used to in newFromString().
	 *
	 * @return string
	 */
	public function getKey(): string {
		return $this->stashKey;
	}

	public function __toString() {
		return $this->stashKey;
	}

	/**
	 * Get the revision ID from the parsoid render ID object.
	 *
	 * @return int
	 */
	public function getRevisionID(): int {
		return $this->revisionID;
	}

	/**
	 * Get the unique identifier from the parsoid render ID object.
	 *
	 * @return string
	 */
	public function getUniqueID(): string {
		return $this->uniqueID;
	}

}

/** @deprecated class alias since 1.42 */
class_alias( ParsoidRenderID::class, 'MediaWiki\\Parser\\Parsoid\\ParsoidRenderID' );
