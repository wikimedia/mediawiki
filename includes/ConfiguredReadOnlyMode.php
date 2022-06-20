<?php

/**
 * A read-only mode service which does not depend on LoadBalancer.
 * To obtain an instance, use MediaWikiServices::getInstance()->getConfiguredReadOnlyMode().
 *
 * @since 1.29
 */
class ConfiguredReadOnlyMode {
	/** @var string|false|null */
	private $reason;

	/** @var string|null */
	private $reasonFile;

	/**
	 * @param string|false|null $reason Current reason for read-only mode, if known. null means look
	 *   in $reasonFile instead.
	 * @param string|null $reasonFile A file to look in for a reason, if $reason is null. If it
	 *   exists and is non-empty, its contents are treated as the reason for read-only mode.
	 *   Otherwise, the wiki is not read-only.
	 */
	public function __construct( $reason, ?string $reasonFile = null ) {
		$this->reason = $reason;
		$this->reasonFile = $reasonFile;
	}

	/**
	 * Check whether the wiki is in read-only mode.
	 *
	 * @return bool
	 */
	public function isReadOnly(): bool {
		return $this->getReason() !== false;
	}

	/**
	 * Get the value of $wgReadOnly or the contents of $wgReadOnlyFile.
	 *
	 * @return string|false String when in read-only mode; false otherwise
	 */
	public function getReason() {
		if ( $this->reason !== null ) {
			return $this->reason;
		}
		if ( $this->reasonFile === null ) {
			return false;
		}
		// Try the reason file
		if ( is_file( $this->reasonFile ) && filesize( $this->reasonFile ) > 0 ) {
			$this->reason = file_get_contents( $this->reasonFile );
		}
		// No need to try the reason file again
		$this->reasonFile = null;
		return $this->reason ?? false;
	}

	/**
	 * Set the read-only mode, which will apply for the remainder of the
	 * request or until a service reset.
	 *
	 * @param string|false|null $msg
	 */
	public function setReason( $msg ): void {
		$this->reason = $msg;
	}
}
