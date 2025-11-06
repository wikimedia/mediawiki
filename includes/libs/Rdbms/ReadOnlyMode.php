<?php

namespace Wikimedia\Rdbms;

/**
 * Determine whether a site is currently in read-only mode.
 *
 * To obtain an instance, use \MediaWiki\MediaWikiServices::getReadOnlyMode().
 *
 * @since 1.29
 */
class ReadOnlyMode {
	private ConfiguredReadOnlyMode $configuredReadOnly;
	private ILBFactory $lbFactory;

	/**
	 * @internal For ServiceWiring only
	 * @param ConfiguredReadOnlyMode $cro
	 * @param ILBFactory $lbFactory
	 */
	public function __construct( ConfiguredReadOnlyMode $cro, ILBFactory $lbFactory ) {
		$this->configuredReadOnly = $cro;
		$this->lbFactory = $lbFactory;
	}

	/**
	 * Check whether the site is in read-only mode.
	 *
	 * @param string|false $domain Domain ID, or false for the current domain
	 * @return bool
	 */
	public function isReadOnly( $domain = false ): bool {
		return $this->getReason( $domain ) !== false;
	}

	/**
	 * Check if the site is in read-only mode and return the message if so
	 *
	 * This checks both statically configured read-only mode, and (cached)
	 * whether the primary database host accepting writes.
	 *
	 * Calling this may result in database connection.
	 *
	 * This method accepts virtual domains
	 * ({@see \MediaWiki\MainConfigSchema::VirtualDomainsMapping}).
	 *
	 * @param string|false $domain Domain ID, or false for the current domain
	 * @return string|false String when in read-only mode; false otherwise
	 */
	public function getReason( $domain = false ) {
		$reason = $this->configuredReadOnly->getReason();
		if ( $reason !== false ) {
			return $reason;
		}
		$reason = $this->lbFactory->getLoadBalancer( $domain )->getReadOnlyReason();
		return $reason ?? false;
	}

	/**
	 * @since 1.41
	 * @return string|false String when site is configured to be in read-only mode; false otherwise
	 */
	public function getConfiguredReason() {
		return $this->configuredReadOnly->getReason();
	}

	/**
	 * Check whether the site is configured to be in read-only mode.
	 *
	 * @since 1.41
	 * @return bool
	 */
	public function isConfiguredReadOnly() {
		return $this->configuredReadOnly->getReason() !== false;
	}

	/**
	 * Override the read-only mode, which will apply for the remainder of the
	 * request or until a service reset.
	 *
	 * @param string|false|null $msg
	 */
	public function setReason( $msg ): void {
		$this->configuredReadOnly->setReason( $msg );
	}

}

/** @deprecated class alias since 1.41 */
class_alias( ReadOnlyMode::class, 'ReadOnlyMode' );
