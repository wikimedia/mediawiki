<?php

namespace MediaWiki\Rest;

/**
 * Defines key for usage in ParsoidOutputStash
 * @package MediaWiki\Rest
 */
class ParsoidRenderId {

	/** @var int */
	private $revisionID;

	/** @var string */
	private $tId;

	/** @var string */
	private $stashKey;

	public function __construct( int $revisionID, string $tId ) {
		$this->revisionID = $revisionID;
		$this->tId = $tId;
		$this->stashKey = $revisionID . '/' . $tId;
		// TODO: Assertion validation for params?
	}

	public static function newFromString( string $stashKey ) {
		[ $revisionID, $tId ] = explode( '/', $stashKey, 2 );
		return new self( (int)$revisionID, $tId );
	}

	public function __toString() {
		return $this->stashKey;
	}

	public function getRevisionID() {
		return $this->revisionID;
	}

	public function getTId() {
		return $this->tId;
	}

}
