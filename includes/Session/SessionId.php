<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

use Stringable;

/**
 * Value object holding the session ID in a manner that can be globally updated.
 *
 * This class exists because we want WebRequest to refer to the session, but it
 * can't hold the Session itself due to issues with circular references and it
 * can't just hold the ID as a string because we need to be able to update the
 * ID when SessionBackend::resetId() is called.
 *
 * @newable
 * @since 1.27
 * @ingroup Session
 */
final class SessionId implements Stringable {
	/** @var string */
	private $id;

	/**
	 * @stable to call
	 *
	 * @param string $id
	 */
	public function __construct( $id ) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @internal For use by \MediaWiki\Session\SessionManager only
	 * @param string $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	public function __toString() {
		return $this->id;
	}

}
