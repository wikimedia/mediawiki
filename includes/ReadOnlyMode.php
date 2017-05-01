<?php

use Wikimedia\Rdbms\LoadBalancer;

/**
 * A service class for fetching the wiki's current read-only mode.
 * To obtain an instance, use MediaWikiServices::getReadOnlyMode().
 *
 * @since 1.29
 */
class ReadOnlyMode {
	/** @var ConfiguredReadOnlyMode */
	private $configuredReadOnly;

	/** @var LoadBalancer */
	private $loadBalancer;

	public function __construct( ConfiguredReadOnlyMode $cro, LoadBalancer $loadBalancer ) {
		$this->configuredReadOnly = $cro;
		$this->loadBalancer = $loadBalancer;
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
		$reason = $this->configuredReadOnly->getReason();
		if ( $reason !== false ) {
			return $reason;
		}
		$reason = $this->loadBalancer->getReadOnlyReason();
		if ( $reason !== false && $reason !== null ) {
			return $reason;
		}
		return false;
	}

	/**
	 * Set the read-only mode, which will apply for the remainder of the
	 * request or until a service reset.
	 *
	 * @param string|null $msg
	 */
	public function setReason( $msg ) {
		$this->configuredReadOnly->setReason( $msg );
	}

	/**
	 * Clear the cache of the read only file
	 */
	public function clearCache() {
		$this->configuredReadOnly->clearCache();
	}
}

/**
 * A read-only mode service which does not depend on LoadBalancer.
 * To obtain an instance, use MediaWikiServices::getConfiguredReadOnlyMode().
 *
 * @since 1.29
 */
class ConfiguredReadOnlyMode {
	/** @var Config */
	private $config;

	/** @var string|bool|null */
	private $fileReason;

	/** @var string|null */
	private $overrideReason;

	public function __construct( Config $config ) {
		$this->config = $config;
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
	 * Get the value of $wgReadOnly or the contents of $wgReadOnlyFile.
	 *
	 * @return string|bool String when in read-only mode; false otherwise
	 */
	public function getReason() {
		if ( $this->overrideReason !== null ) {
			return $this->overrideReason;
		}
		$confReason = $this->config->get( 'ReadOnly' );
		if ( $confReason !== null ) {
			return $confReason;
		}
		if ( $this->fileReason === null ) {
			// Cache for faster access next time
			$readOnlyFile = $this->config->get( 'ReadOnlyFile' );
			if ( is_file( $readOnlyFile ) && filesize( $readOnlyFile ) > 0 ) {
				$this->fileReason = file_get_contents( $readOnlyFile );
			} else {
				$this->fileReason = false;
			}
		}
		return $this->fileReason;
	}

	/**
	 * Set the read-only mode, which will apply for the remainder of the
	 * request or until a service reset.
	 *
	 * @param string|null $msg
	 */
	public function setReason( $msg ) {
		$this->overrideReason = $msg;
	}

	/**
	 * Clear the cache of the read only file
	 */
	public function clearCache() {
		$this->fileReason = null;
	}
}
