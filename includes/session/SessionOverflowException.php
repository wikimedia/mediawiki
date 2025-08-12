<?php

namespace MediaWiki\Session;

use InvalidArgumentException;

/**
 * OverflowException specific to the SessionManager, used when the request had multiple possible
 * sessions tied for top priority.
 *
 * @since 1.34
 * @ingroup Session
 */
class SessionOverflowException extends \OverflowException {
	/** @var SessionInfo[] */
	private $sessionInfos;

	/**
	 * @param SessionInfo[] $sessionInfos Must have at least two elements
	 * @param string $msg
	 * @throws \InvalidArgumentException If $sessionInfos has less than 2 elements
	 */
	public function __construct( array $sessionInfos, $msg ) {
		if ( count( $sessionInfos ) < 2 ) {
			throw new InvalidArgumentException( 'Expected at least two SessionInfo objects.' );
		}
		parent::__construct( $msg );
		$this->sessionInfos = $sessionInfos;
	}

	/**
	 * @return SessionInfo[]
	 */
	public function getSessionInfos(): array {
		return $this->sessionInfos;
	}
}
