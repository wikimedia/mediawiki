<?php

/**
 * Container for Title restrictions
 *
 * May contain either simple or cascading restrictions.
 *
 * @since 1.26
 */
class TitleRestrictions {
	/** @var array Array of groups allowed to edit this article */
	protected $restrictions = array();

	/** @var array When do the restrictions on this page expire? */
	protected $restrictionsExpiry = array();

	/** @var bool Flag to indicate whether we know there are cascading restrictions */
	protected $isCascading;

	/**
	 * @param string $action
	 * @param array $restrictions
	 * @param string $expiry An expiration date in database form, or
	 * alternatively, an empty string if there is no expiration.
	 */
	public function add( $action, $restrictions, $expiry = '' ) {
		global $wgContLang;

		if ( !array_key_exists( $action, $this->restrictions ) ) {
			$this->restrictions[$action] = array();
		}
		$this->restrictions[$action] = array_unique(
			array_merge( $this->restrictions[$action], $restriction ) );
		// FIXME: Language::formatExpiry actually normalizes to the db-specific
		// "infinity", which is wrong.  This will cause issues when using an
		// Oracle backend.
		$this->restrictionsExpiry[$action] = $wgContLang->formatExpiry( $expiry, TS_MW );
	}

	/**
	 * @param string $action
	 * @return array
	 */
	public function get( $action ) {
		return isset( $this->restrictions[$action] )
				? $this->restrictions[$action]
				: array();
	}

	public function getAll() {
		return $this->restrictions;
	}

	/**
	 * @param string $action
	 */
	public function clear( $action ) {
		$this->restrictions[$action] = array();
	}

	/**
	 * @param string $action
	 * @return string|false MW timestamp at which this restriction expires, or
	 * false in the unnerving event that no such key has been set.
	 */
	public function getExpiry( $action ) {
		return isset( $this->restrictionsExpiry[$action] )
				? $this->restrictionsExpiry[$action]
				: false;
	}

	/**
	 * @param bool $value
	 */
	public function setIsCascading( $value ) {
		$this->isCascading = $value;
	}

	/**
	 * @return bool
	 */
	public function isCascading() {
		return $this->isCascading;
	}
}
