<?php

/**
 * Object for holing configuration for a single Site.
 *
 * @since 1.20
 *
 * @file
 * @ingroup Sites
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SiteConfigObject implements SiteConfig {

	protected $localId;
	protected $linkInline;
	protected $linkNavigation;
	protected $forward;
	protected $extraConfig;

	/**
	 * Constructor.
	 *
	 * @since 1.20
	 *
	 * @param string $localId
	 * @param boolean $linkInline
	 * @param boolean $linkNavigation
	 * @param boolean $forward
	 * @param array $extraConfig
	 */
	public function __construct( $localId, $linkInline, $linkNavigation, $forward, array $extraConfig = array() ) {
		$this->localId = $localId;
		$this->linkInline = $linkInline;
		$this->linkNavigation = $linkNavigation;
		$this->forward = $forward;
		$this->extraConfig = $extraConfig;
	}

	/**
	 * @see SiteConfig::getLocalId()
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getLocalId() {
		return $this->localId;
	}

	/**
	 * @see SiteConfig::getLinkInline()
	 *
	 * @since 1.20
	 *
	 * @return boolean
	 */
	public function getLinkInline() {
		return $this->linkInline;
	}

	/**
	 * @see SiteConfig::getLinkNavigation()
	 *
	 * @since 1.20
	 *
	 * @return boolean
	 */
	public function getLinkNavigation() {
		return $this->linkNavigation;
	}

	/**
	 * @see SiteConfig::getForward()
	 *
	 * @since 1.20
	 *
	 * @return boolean
	 */
	public function getForward() {
		return $this->forward;
	}

	/**
	 * @see SiteConfig::getExtraInfo()
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getExtraInfo() {
		return $this->extraConfig;
	}

}
