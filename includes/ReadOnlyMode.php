<?php

/**
 * A service class for fetching the wiki's current read-only mode.
 * To obtain an instance, use MediaWikiServices::getReadOnlyMode().
 *
 * @since 1.29
 */
class ReadOnlyMode {
	private $config;
	private $loadBalancers = [];
	private $reason = null;

	public function __construct( Config $config ) {
		$this->config = $config;
	}

	public function addLoadBalancer( \Wikimedia\Rdbms\LoadBalancer $loadBalancer ) {
		$this->loadBalancers[] = $loadBalancer;
	}

	/**
	 * Check whether the wiki is in read-only mode.
	 *
	 * @return bool
	 */
	public function isReadOnly() {
		return $this->getReason() !== false;
	}

	/**
	 * Check if the site is in read-only mode and return the message if so
	 *
	 * This checks the configuration and registered DB load balancers for
	 * read-only mode. This may result in DB connection being made.
	 *
	 * @return string|bool String when in read-only mode; false otherwise
	 */
	public function getReason() {
		$readOnly = $this->getConfiguredReason();
		if ( $readOnly !== false ) {
			return $readOnly;
		}
		foreach ( $this->loadBalancers as $lb ) {
			$lbReadOnly = $lb->getReadOnlyReason();
			if ( $lbReadOnly !== false && $lbReadOnly !== null ) {
				return $lbReadOnly;
			}
		}
		return false;
	}

	/**
	 * Get the value of $wgReadOnly or the contents of $wgReadOnlyFile.
	 *
	 * Use MediaWikiServices::getConfiguredReadOnlyMode()->getReason() for
	 * public access to this.
	 *
	 * @return string|bool String when in read-only mode; false otherwise
	 */
	private function getConfiguredReason() {
		if ( $this->reason === null ) {
			// Cache for faster access next time
			$this->reason = $this->config->get( 'ReadOnly' );

			if ( $this->reason === null ) {
				$readOnlyFile = $this->config->get( 'ReadOnlyFile' );
				if ( is_file( $readOnlyFile ) && filesize( $readOnlyFile ) > 0 ) {
					$this->reason = file_get_contents( $readOnlyFile );
				} else {
					$this->reason = false;
				}
			}
		}
		return $this->reason;
	}

	/**
	 * Set the read-only mode, which will apply for the remainder of the
	 * request or until a service reset.
	 */
	public function setReason( $msg ) {
		$this->reason = $msg;
	}
}
