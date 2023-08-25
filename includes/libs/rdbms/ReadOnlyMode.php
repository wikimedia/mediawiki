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
	/** @var ConfiguredReadOnlyMode */
	private $configuredReadOnly;

	/** @var ILoadBalancer */
	private $loadBalancer;

	public function __construct( ConfiguredReadOnlyMode $cro, ILoadBalancer $loadBalancer ) {
		$this->configuredReadOnly = $cro;
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * Check whether the site is in read-only mode.
	 *
	 * @return bool
	 */
	public function isReadOnly(): bool {
		return $this->getReason() !== false;
	}

	/**
	 * Check if the site is in read-only mode and return the message if so
	 *
	 * This checks both statically configured read-only mode, and (cached)
	 * whether the primary database host accepting writes.
	 *
	 * Calling this may result in database connection.
	 *
	 * @return string|false String when in read-only mode; false otherwise
	 */
	public function getReason() {
		$reason = $this->configuredReadOnly->getReason();
		if ( $reason !== false ) {
			return $reason;
		}
		$reason = $this->loadBalancer->getReadOnlyReason();
		return $reason ?? false;
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

/**
 * @deprecated since 1.41
 */
class_alias( ReadOnlyMode::class, 'ReadOnlyMode' );
