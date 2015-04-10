<?php

/**
 * Container for Title restrictions
 */
class TitleRestrictions {
	/** @var array Array of groups allowed to edit this article */
	protected $restrictions = array();

	/** @var bool Cascade restrictions on this page to included templates and images? */
	protected $cascadeRestriction;

	/** @var array results of getCascadeProtectionSources */
	protected $cascadingRestrictions = array();

	/** @var array When do the restrictions on this page expire? */
	protected $restrictionsExpiry = array();

	/** @var bool Are cascading restrictions in effect on this page? */
	protected $hasCascadingRestrictions;

	/** @var array Where are the cascading restrictions coming from on this page? */
	protected $cascadeSources = array();

	/**
	 * @param string $action
	 * @param string $restriction
	 * @param int|bool $expiry Timestamp or false for no expiration.
	 */
	public function set( $action, $restriction, $expiry = false ) {
	}

	public function get( $action ) {
		return isset( $this->restrictions[$action] )
				? $this->restrictions[$action]
				: array();
	}

	public function getExpiry( $action ) {
		return isset( $this->restrictions[$action] )
				? $this->restrictions[$action]
				: false;
	}

	public function setIsCascadeRestriction( $value ) {
	}

	public function isCascadeRestriction() {
	}

	public function setCascadingSources( $sources ) {
	}

	public function setCascadingRestrictions( $restrictions ) {
	}
}
